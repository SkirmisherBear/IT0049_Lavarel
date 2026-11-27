<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = Schema::getColumnListing('bookings');

        Schema::table('bookings', function (Blueprint $table) use ($columns): void {
            foreach (['customer_id' => 'customers', 'user_id' => 'users'] as $column => $relatedTable) {
                if (in_array($column, $columns, true)) {
                    $table->foreignId($column)->nullable()->change();
                } else {
                    $table->foreignId($column)->nullable()->constrained($relatedTable)->cascadeOnDelete();
                }
            }

            foreach (['session_type' => 100, 'time_slot' => 50, 'trainer_level' => 20] as $column => $length) {
                $definition = $table->string($column, $length)->nullable();

                if (in_array($column, $columns, true)) {
                    $definition->change();
                }
            }

            $bookingDate = $table->date('booking_date')->nullable();

            if (in_array('booking_date', $columns, true)) {
                $bookingDate->change();
            }

            $scheduledAt = $table->timestamp('scheduled_at')->nullable();

            if (in_array('scheduled_at', $columns, true)) {
                $scheduledAt->change();
            } else {
                $scheduledAt->index();
            }

            if (! in_array('focus', $columns, true)) {
                $table->string('focus', 120)->nullable();
            }
        });
    }

    /**
     * Both branches may already contain bookings, so removing either schema would lose data.
     */
    public function down(): void {}
};
