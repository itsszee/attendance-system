<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('mode', ['WFH', 'WFO']);
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->enum('status', ['on_time', 'late', 'flagged'])->default('on_time');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->unique(['user_id', 'date']);
            $table->index(['date']);
            $table->index(['date', 'mode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
