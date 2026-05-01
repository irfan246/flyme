<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('trip_type')->default('one_way')->after('source');
            $table->foreignId('return_flight_schedule_id')->nullable()->after('flight_schedule_id')->constrained('flight_schedules')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['return_flight_schedule_id']);
            $table->dropColumn(['return_flight_schedule_id', 'trip_type']);
        });
    }
};
