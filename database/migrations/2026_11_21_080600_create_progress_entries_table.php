<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('activity', 100);
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->date('recorded_on')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_entries');
    }
};
