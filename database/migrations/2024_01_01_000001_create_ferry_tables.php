<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('origin_code', 10);
            $table->string('origin_name');
            $table->string('origin_location');
            $table->string('destination_code', 10);
            $table->string('destination_name');
            $table->string('destination_location');
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->unsignedInteger('trips_per_day')->default(1);
            $table->enum('status', ['active', 'seasonal', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->cascadeOnDelete();
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time')->nullable();
            $table->string('vessel_name')->nullable();
            $table->enum('status', ['scheduled', 'delayed', 'cancelled', 'completed'])->default('scheduled');
            $table->unsignedInteger('available_seats')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('passenger_fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->cascadeOnDelete();
            $table->enum('fare_type', ['regular', 'student', 'senior', 'pwd', 'children']);
            $table->string('label')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_pct', 5, 2)->default(0);
            $table->string('required_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->cascadeOnDelete();
            $table->string('vehicle_type');
            $table->string('label')->nullable();
            $table->string('size_description')->nullable();
            $table->decimal('fare_min', 10, 2);
            $table->decimal('fare_max', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->string('booking_reference')->unique();
            $table->date('travel_date');
            $table->string('contact_name', 150);
            $table->string('contact_email', 180);
            $table->string('contact_phone', 20);
            $table->unsignedInteger('total_passengers')->default(1);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['gcash', 'maya', 'visa', 'mastercard', 'cash'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'boarded'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('fare_type')->default('regular');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_fare_id')->nullable()->constrained()->nullOnDelete();
            $table->string('plate_number')->nullable();
            $table->string('vehicle_type');
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_vehicles');
        Schema::dropIfExists('passengers');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('vehicle_fares');
        Schema::dropIfExists('passenger_fares');
        Schema::dropIfExists('trips');
        Schema::dropIfExists('routes');
    }
};