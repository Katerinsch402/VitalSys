<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('id_paciente');
            $table->string('cod_paciente', 100);
            $table->string('num_doc', 100);
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('ciudad', 100);
            $table->string('departamento', 100);
            $table->string('direccion', 255);
            $table->string('edad', 10);
            $table->string('sexo', 50);
            $table->string('tiene_ips', 50);
            $table->text('diagnostico')->nullable();
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pacientes');
        Schema::enableForeignKeyConstraints();
    }
};