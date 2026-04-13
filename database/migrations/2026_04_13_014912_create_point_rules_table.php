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
        Schema::create('point_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name');
            $table->string('target_role')->nullable()->comment('Siswa/Karyawan/All');
            $table->enum('condition_operator', ['<', '>', '<=', '>=', '=', 'BETWEEN']);
            $table->string('condition_value');
            $table->integer('point_modifier');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_rules');
    }
};
