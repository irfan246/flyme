<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use App\Models\Airport;
use App\Models\City;
use App\Models\FlightRoute;
use App\Models\FlightSchedule;
use App\Models\Seat;
use App\Models\TicketClass;
use App\Models\TicketPrice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    private array $resources = [
        'customers' => ['model' => User::class, 'title' => 'Customer', 'view' => 'customers'],
        'cities' => ['model' => City::class, 'title' => 'Kota', 'view' => 'cities'],
        'airports' => ['model' => Airport::class, 'title' => 'Bandara', 'view' => 'airports'],
        'aircrafts' => ['model' => Aircraft::class, 'title' => 'Pesawat', 'view' => 'aircrafts'],
        'routes' => ['model' => FlightRoute::class, 'title' => 'Rute Flyme', 'view' => 'routes'],
        'flight-schedules' => ['model' => FlightSchedule::class, 'title' => 'Jadwal Flyme', 'view' => 'flight_schedules'],
        'ticket-classes' => ['model' => TicketClass::class, 'title' => 'Kelas Tiket', 'view' => 'ticket_classes'],
        'ticket-prices' => ['model' => TicketPrice::class, 'title' => 'Harga Tiket', 'view' => 'ticket_prices'],
    ];

    public function index(Request $request, string $resource): View
    {
        $config = $this->config($resource);
        $query = $this->query($resource)->latest();

        if ($search = $request->string('search')->toString()) {
            $query = $this->applySearch($query, $resource, $search);
        }

        return view('admin.master.index', [
            'resource' => $resource,
            'title' => $config['title'],
            'items' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function create(string $resource): View
    {
        return view('admin.master.form', $this->formData($resource));
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $data = $request->validate($this->rules($resource));
        $this->storeResource($resource, $data);

        return redirect()->route('admin.master.index', $resource)->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(string $resource, int $id): View
    {
        return view('admin.master.form', [
            ...$this->formData($resource),
            'item' => $this->query($resource)->findOrFail($id),
        ]);
    }

    public function update(Request $request, string $resource, int $id): RedirectResponse
    {
        $item = $this->query($resource)->findOrFail($id);
        $data = $request->validate($this->rules($resource, $id));
        $this->updateResource($resource, $item, $data);

        return redirect()->route('admin.master.index', $resource)->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $resource, int $id): RedirectResponse
    {
        $this->query($resource)->findOrFail($id)->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function seats(Aircraft $aircraft): View
    {
        $aircraft->load(['seats' => fn ($query) => $query->orderBy('row_number')->orderBy('column_letter')]);

        return view('admin.master.seats', compact('aircraft'));
    }

    public function generateSeats(Request $request, Aircraft $aircraft): RedirectResponse
    {
        $data = $request->validate([
            'seat_rows' => ['required', 'integer', 'min:1', 'max:60'],
            'seat_columns' => ['required', 'string', 'max:50'],
        ]);

        $columns = collect(explode(',', strtoupper($data['seat_columns'])))
            ->map(fn ($column) => trim($column))
            ->filter()
            ->unique()
            ->values();

        $aircraft->seats()->delete();
        for ($row = 1; $row <= $data['seat_rows']; $row++) {
            foreach ($columns as $column) {
                $aircraft->seats()->create([
                    'code' => $column.$row,
                    'row_number' => $row,
                    'column_letter' => $column,
                    'seat_type' => $row <= 2 ? 'premium' : 'standard',
                    'is_active' => true,
                ]);
            }
        }

        $aircraft->update([
            'seat_rows' => $data['seat_rows'],
            'seat_columns' => $columns->implode(','),
            'capacity' => $data['seat_rows'] * $columns->count(),
        ]);

        return back()->with('success', 'Seat map berhasil dibuat ulang.');
    }

    private function config(string $resource): array
    {
        abort_unless(isset($this->resources[$resource]), 404);

        return $this->resources[$resource];
    }

    private function query(string $resource)
    {
        $model = $this->config($resource)['model'];
        $query = $model::query();

        return match ($resource) {
            'customers' => $query->whereHas('role', fn ($role) => $role->where('name', 'customer'))->with('role'),
            'airports' => $query->with('city'),
            'routes' => $query->with(['originAirport.city', 'destinationAirport.city']),
            'flight-schedules' => $query->with(['aircraft', 'route.originAirport.city', 'route.destinationAirport.city']),
            'ticket-prices' => $query->with(['flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city', 'ticketClass']),
            default => $query,
        };
    }

    private function applySearch($query, string $resource, string $search)
    {
        return match ($resource) {
            'customers' => $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")),
            'cities' => $query->where('name', 'like', "%{$search}%"),
            'airports' => $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%")),
            'aircrafts' => $query->where(fn ($q) => $q->where('model', 'like', "%{$search}%")->orWhere('registration_code', 'like', "%{$search}%")),
            'routes' => $query->where('code', 'like', "%{$search}%"),
            'flight-schedules' => $query->where('flight_number', 'like', "%{$search}%"),
            'ticket-classes' => $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%")),
            default => $query,
        };
    }

    private function rules(string $resource, ?int $id = null): array
    {
        return match ($resource) {
            'customers' => [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,'.($id ?? 'NULL')],
                'phone' => ['nullable', 'string', 'max:30'],
                'address' => ['nullable', 'string', 'max:1000'],
                'password' => [$id ? 'nullable' : 'required', 'string', 'min:8'],
            ],
            'cities' => ['name' => ['required', 'string', 'max:255'], 'province' => ['nullable', 'string', 'max:255'], 'country' => ['required', 'string', 'max:255']],
            'airports' => ['city_id' => ['required', 'exists:cities,id'], 'code' => ['required', 'string', 'max:10', 'unique:airports,code,'.($id ?? 'NULL')], 'name' => ['required', 'string', 'max:255'], 'address' => ['nullable', 'string']],
            'aircrafts' => ['registration_code' => ['required', 'string', 'max:255', 'unique:aircrafts,registration_code,'.($id ?? 'NULL')], 'model' => ['required', 'string', 'max:255'], 'seat_rows' => ['required', 'integer', 'min:1'], 'seat_columns' => ['required', 'string', 'max:50'], 'capacity' => ['required', 'integer', 'min:1'], 'status' => ['required', 'string', 'max:50']],
            'routes' => ['origin_airport_id' => ['required', 'exists:airports,id'], 'destination_airport_id' => ['required', 'different:origin_airport_id', 'exists:airports,id'], 'code' => ['required', 'string', 'max:255', 'unique:routes,code,'.($id ?? 'NULL')], 'distance_km' => ['required', 'integer', 'min:1'], 'duration_minutes' => ['required', 'integer', 'min:1'], 'is_active' => ['nullable', 'boolean']],
            'flight-schedules' => ['aircraft_id' => ['required', 'exists:aircrafts,id'], 'route_id' => ['required', 'exists:routes,id'], 'flight_number' => ['required', 'string', 'max:255', 'unique:flight_schedules,flight_number,'.($id ?? 'NULL')], 'departure_time' => ['required', 'date'], 'arrival_time' => ['required', 'date', 'after:departure_time'], 'status' => ['required', 'string', 'max:50']],
            'ticket-classes' => ['name' => ['required', 'string', 'max:255', 'unique:ticket_classes,name,'.($id ?? 'NULL')], 'code' => ['required', 'string', 'max:10', 'unique:ticket_classes,code,'.($id ?? 'NULL')], 'description' => ['nullable', 'string']],
            'ticket-prices' => ['flight_schedule_id' => ['required', 'exists:flight_schedules,id'], 'ticket_class_id' => ['required', 'exists:ticket_classes,id'], 'price' => ['required', 'numeric', 'min:0'], 'quota' => ['required', 'integer', 'min:1']],
            default => [],
        };
    }

    private function formData(string $resource): array
    {
        return [
            'resource' => $resource,
            'title' => $this->config($resource)['title'],
            'item' => null,
            'cities' => City::orderBy('name')->get(),
            'airports' => Airport::with('city')->orderBy('code')->get(),
            'aircrafts' => Aircraft::orderBy('registration_code')->get(),
            'routes' => FlightRoute::with(['originAirport.city', 'destinationAirport.city'])->orderBy('code')->get(),
            'schedules' => FlightSchedule::with(['route.originAirport.city', 'route.destinationAirport.city'])->orderByDesc('departure_time')->get(),
            'ticketClasses' => TicketClass::orderBy('id')->get(),
        ];
    }

    private function storeResource(string $resource, array $data): void
    {
        if ($resource === 'customers') {
            $roleId = \App\Models\Role::where('name', 'customer')->value('id');
            User::create([...$data, 'role_id' => $roleId]);
            return;
        }

        $this->config($resource)['model']::create($this->normalize($resource, $data));
    }

    private function updateResource(string $resource, $item, array $data): void
    {
        if ($resource === 'customers' && blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $item->update($this->normalize($resource, $data));
    }

    private function normalize(string $resource, array $data): array
    {
        if ($resource === 'routes') {
            $data['is_active'] = (bool) ($data['is_active'] ?? false);
        }

        return $data;
    }
}
