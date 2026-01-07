<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome da Escola/Empresa
            $table->string('document')->nullable(); // CNPJ
            $table->string('domain')->unique()->nullable(); // Para identificar acesso (ex: escola.sistema.com)
            $table->boolean('is_active')->default(true); // Se o cliente pagou
            $table->timestamps();
        });
        
        // Agora adicionamos a foreign key na tabela users que criamos no passo anterior.
        // Como a ordem de execução importa, fazemos um alter table aqui para garantir.
        Schema::table('users', function (Blueprint $table) {
             $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};