<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings')) {
            return;
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('session_type', 100); // e.g., 'Power Rack Priority', 'System Engineer PT', 'Open Gym'
            $table->date('booking_date');
            $table->string('time_slot', 50);     // e.g., '07:00 AM - 08:30 AM'
            $table->string('status', 30)->default('Confirmed'); // Confirmed, Completed, Cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
