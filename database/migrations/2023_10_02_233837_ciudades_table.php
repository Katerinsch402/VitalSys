<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id('id_ciudad');
            $table->string('nombre', 100);
            $table->unsignedBigInteger('departamento_id');
            $table->foreign('departamento_id')->references('id_departamento')->on('departamentos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ciudades');
        Schema::enableForeignKeyConstraints();
    }
};