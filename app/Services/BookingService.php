<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\FlightSchedule;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Seat;
use App\Models\TicketClass;
use App\Models\TicketPrice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function expirePendingBookings(): void
    {
        Booking::query()
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->with('bookingSeats')
            ->get()
            ->each(function (Booking $booking): void {
                $booking->bookingSeats()->delete();
                $booking->update(['status' => 'cancelled']);
            });
    }

    public function bookedSeatIds(FlightSchedule $schedule, ?Booking $exceptBooking = null): array
    {
        $query = BookingSeat::query()
            ->where('flight_schedule_id', $schedule->id)
            ->whereHas('booking', fn ($booking) => $booking->whereIn('status', ['pending', 'paid', 'confirmed']));

        if ($exceptBooking) {
            $query->where('booking_id', '!=', $exceptBooking->id);
        }

        return $query->pluck('seat_id')->all();
    }

    public function createBooking(array $data, ?User $user, ?User $creator, string $source): Booking
    {
        return DB::transaction(function () use ($data, $user, $creator, $source): Booking {
            $schedule = FlightSchedule::with(['aircraft.seats', 'route.originAirport.city', 'route.destinationAirport.city'])->findOrFail($data['flight_schedule_id']);
            $returnSchedule = filled($data['return_flight_schedule_id'] ?? null)
                ? FlightSchedule::with(['aircraft.seats', 'route.originAirport.city', 'route.destinationAirport.city'])->findOrFail($data['return_flight_schedule_id'])
                : null;
            $ticketClass = TicketClass::findOrFail($data['ticket_class_id']);
            $passengers = array_values($data['passengers'] ?? []);
            $tripType = $returnSchedule ? 'round_trip' : ($data['trip_type'] ?? 'one_way');
            $outboundSeatIds = array_values($data['outbound_seat_ids'] ?? $data['seat_ids'] ?? []);
            $returnSeatIds = array_values($data['return_seat_ids'] ?? []);

            if (count($passengers) === 0 || count($passengers) !== count($outboundSeatIds)) {
                throw ValidationException::withMessages(['passengers' => 'Jumlah penumpang harus sama dengan jumlah kursi pergi.']);
            }

            if ($returnSchedule && count($passengers) !== count($returnSeatIds)) {
                throw ValidationException::withMessages(['return_seat_ids' => 'Jumlah kursi pulang harus sama dengan jumlah penumpang.']);
            }

            $validSeatIds = Seat::where('aircraft_id', $schedule->aircraft_id)
                ->whereIn('id', $outboundSeatIds)
                ->where('is_active', true)
                ->pluck('id')
                ->all();

            if (count($validSeatIds) !== count($outboundSeatIds)) {
                throw ValidationException::withMessages(['outbound_seat_ids' => 'Pilihan kursi pergi tidak valid untuk pesawat ini.']);
            }

            $takenSeatIds = $this->bookedSeatIds($schedule);
            if (array_intersect($outboundSeatIds, $takenSeatIds) !== []) {
                throw ValidationException::withMessages(['outbound_seat_ids' => 'Salah satu kursi pergi sudah dipilih atau dibooking.']);
            }

            if ($returnSchedule) {
                $validReturnSeatIds = Seat::where('aircraft_id', $returnSchedule->aircraft_id)
                    ->whereIn('id', $returnSeatIds)
                    ->where('is_active', true)
                    ->pluck('id')
                    ->all();

                if (count($validReturnSeatIds) !== count($returnSeatIds)) {
                    throw ValidationException::withMessages(['return_seat_ids' => 'Pilihan kursi pulang tidak valid untuk pesawat ini.']);
                }

                $takenReturnSeatIds = $this->bookedSeatIds($returnSchedule);
                if (array_intersect($returnSeatIds, $takenReturnSeatIds) !== []) {
                    throw ValidationException::withMessages(['return_seat_ids' => 'Salah satu kursi pulang sudah dipilih atau dibooking.']);
                }
            }

            $price = TicketPrice::where('flight_schedule_id', $schedule->id)
                ->where('ticket_class_id', $ticketClass->id)
                ->firstOrFail();
            $returnPrice = $returnSchedule
                ? TicketPrice::where('flight_schedule_id', $returnSchedule->id)->where('ticket_class_id', $ticketClass->id)->firstOrFail()
                : null;

            $subtotal = ((float) $price->price + (float) ($returnPrice?->price ?? 0)) * count($passengers);
            $discount = (float) ($data['discount'] ?? 0);
            $total = max(0, $subtotal - $discount);

            $booking = Booking::create([
                'user_id' => $user?->id,
                'flight_schedule_id' => $schedule->id,
                'return_flight_schedule_id' => $returnSchedule?->id,
                'ticket_class_id' => $ticketClass->id,
                'created_by' => $creator?->id,
                'booking_code' => $this->bookingCode(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'passenger_count' => count($passengers),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_amount' => $total,
                'status' => $source === 'offline' ? 'paid' : 'pending',
                'source' => $source,
                'trip_type' => $tripType,
                'expires_at' => $source === 'offline' ? null : now()->addHours(6),
            ]);

            foreach ($passengers as $passenger) {
                $booking->passengers()->create($passenger);
            }

            foreach ($outboundSeatIds as $seatId) {
                $booking->bookingSeats()->create([
                    'seat_id' => $seatId,
                    'flight_schedule_id' => $schedule->id,
                ]);
            }

            foreach ($returnSeatIds as $seatId) {
                $booking->bookingSeats()->create([
                    'seat_id' => $seatId,
                    'flight_schedule_id' => $returnSchedule->id,
                ]);
            }

            Payment::create([
                'booking_id' => $booking->id,
                'method' => $data['payment_method'] ?? 'bank_transfer',
                'amount' => $total,
                'status' => $source === 'offline' ? 'paid' : 'pending',
                'paid_at' => $source === 'offline' ? now() : null,
            ]);

            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Booking dibuat',
                    'message' => 'Booking '.$booking->booking_code.' berhasil dibuat dengan status '.$booking->status.'.',
                ]);
            }

            return $booking->load(['flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city', 'ticketClass', 'passengers', 'bookingSeats.seat', 'payment']);
        });
    }

    public function cancel(Booking $booking): void
    {
        DB::transaction(function () use ($booking): void {
            if ($booking->status === 'confirmed') {
                throw ValidationException::withMessages(['booking' => 'Booking confirmed tidak bisa dibatalkan dari menu ini.']);
            }

            $booking->bookingSeats()->delete();
            $booking->payment?->update(['status' => 'cancelled']);
            $booking->update(['status' => 'cancelled']);
        });
    }

    public function confirmPayment(Booking $booking, User $admin): void
    {
        DB::transaction(function () use ($booking, $admin): void {
            $booking->payment?->update([
                'status' => 'confirmed',
                'confirmed_by' => $admin->id,
                'confirmed_at' => now(),
            ]);

            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            if ($booking->user_id) {
                Notification::create([
                    'user_id' => $booking->user_id,
                    'title' => 'Booking dikonfirmasi',
                    'message' => 'Booking '.$booking->booking_code.' sudah dikonfirmasi admin.',
                ]);
            }
        });
    }

    private function bookingCode(): string
    {
        do {
            $code = 'AIR'.now()->format('ymd').Str::upper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
