<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicos', function (Blueprint $table) {
            $table->id('id_medico');
            $table->string('nombre');
            $table->string('ci');
            $table->string('telefono');
            $table->string('registro');
            $table->unsignedBigInteger('especialidad_id');
            $table->foreign('especialidad_id')->references('id_especialidad')->on('especialidades');
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('medicos');
        Schema::enableForeignKeyConstraints();
    }
};
