<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Campos opcionais para preservar usuários antigos já cadastrados.
            $table->string('cpf', 11)->nullable()->unique();
            $table->date('birth_date')->nullable();

            // O cadastro novo usa CPF, nascimento e senha, sem exigir nome/e-mail.
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
            $table->dropColumn(['cpf', 'birth_date']);
            // Não tornar name/email obrigatórios novamente: pode haver contas sem esses dados.
        });
    }
};
