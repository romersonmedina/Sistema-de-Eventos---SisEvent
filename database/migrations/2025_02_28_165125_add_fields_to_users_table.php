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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();        // Adiciona o campo 'telefone'
            $table->date('birth_date')->nullable();     // Adiciona o campo 'data de nascimento'
            $table->enum('gender', ['masculino', 'feminino', 'outro'])->nullable();  // Adiciona o campo 'gênero'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'birth_date', 'gender']);
        });
    }
};
