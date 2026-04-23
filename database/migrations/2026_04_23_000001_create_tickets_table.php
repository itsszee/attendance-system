<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ticket_code', 20)->unique();
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low', 'mid', 'high'])->default('low');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // Full-Text Search index untuk anti-duplikasi
            $table->fullText(['subject', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
