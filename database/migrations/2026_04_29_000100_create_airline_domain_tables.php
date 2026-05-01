<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('province')->nullable();
            $table->string('country')->default('Indonesia');
            $table->timestamps();
        });

        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('aircrafts', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code')->unique();
            $table->string('model');
            $table->unsignedInteger('seat_rows')->default(8);
            $table->string('seat_columns')->default('A,B,C,D');
            $table->unsignedInteger('capacity')->default(32);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aircraft_id')->constrained('aircrafts')->cascadeOnDelete();
            $table->string('code');
            $table->unsignedInteger('row_number');
            $table->string('column_letter', 2);
            $table->string('seat_type')->default('standard');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['aircraft_id', 'code']);
        });

        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_airport_id')->constrained('airports')->restrictOnDelete();
            $table->foreignId('destination_airport_id')->constrained('airports')->restrictOnDelete();
            $table->string('code')->unique();
            $table->unsignedInteger('distance_km')->default(0);
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('flight_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aircraft_id')->constrained('aircrafts')->restrictOnDelete();
            $table->foreignId('route_id')->constrained('routes')->restrictOnDelete();
            $table->string('flight_number')->unique();
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->string('status')->default('scheduled');
            $table->timestamps();
        });

        Schema::create('ticket_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 10)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_class_id')->constrained()->restrictOnDelete();
            $table->decimal('price', 14, 2);
            $table->unsignedInteger('quota')->default(0);
            $table->timestamps();
            $table->unique(['flight_schedule_id', 'ticket_class_id']);
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('flight_schedule_id')->constrained()->restrictOnDelete();
            $table->foreignId('ticket_class_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('booking_code')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->unsignedInteger('passenger_count');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('source')->default('online');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('identity_number')->nullable();
            $table->string('gender', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained()->restrictOnDelete();
            $table->foreignId('flight_schedule_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['flight_schedule_id', 'seat_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('method')->default('bank_transfer');
            $table->decimal('amount', 14, 2);
            $table->string('status')->default('pending');
            $table->string('proof_path')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('new');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_seats');
        Schema::dropIfExists('booking_passengers');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('ticket_prices');
        Schema::dropIfExists('ticket_classes');
        Schema::dropIfExists('flight_schedules');
        Schema::dropIfExists('routes');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('aircrafts');
        Schema::dropIfExists('airports');
        Schema::dropIfExists('cities');
    }
};
