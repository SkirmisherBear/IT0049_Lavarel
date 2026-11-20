<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('transaction_code')->unique();
            $table->decimal('total', 10, 2);
            $table->string('status', 20)->default('placed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_orders');
    }
};
