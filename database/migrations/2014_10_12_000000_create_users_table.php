<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('rol');
            $table->unsignedBigInteger('doc_id')->nullable();
            $table->string('estado')->default('activo');
            $table->rememberToken();
            $table->timestamps();
        });

        User::create([
            'name' => 'Admin',
            'email' => 'vitalsys3@gmail.com',
            'rol' => 'admin',
            'doc_id' => null,
            'estado' => 'activo',
            'password' => bcrypt('vital2026')
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};