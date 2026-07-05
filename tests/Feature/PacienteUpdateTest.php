<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PacienteUpdateTest extends TestCase
{
    public function test_patient_can_be_updated_without_non_existing_columns(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'patient-update-' . uniqid() . '@example.com',
            'rol' => 'admin',
            'estado' => 'activo',
            'doc_id' => '99999999',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $pacienteId = DB::table('pacientes')->insertGetId([
            'cod_paciente' => 'PAC-TEST-1',
            'num_doc' => '11111111',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'ciudad' => 'Ciudad Test',
            'departamento' => 'Departamento Test',
            'direccion' => 'Calle 1',
            'edad' => '30',
            'sexo' => 'M',
            'tiene_ips' => 'SI',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->put(route('pacientes.actualizar', $pacienteId), [
                'cod_paciente' => 'PAC-UPDATED',
                'nombre' => 'Juan Carlos',
                'apellido' => 'Pérez',
                'num_doc' => '11111111',
                'ciudad' => 'Ciudad Test',
                'departamento' => 'Departamento Test',
                'direccion' => 'Calle 2',
                'edad' => '31',
                'sexo' => 'M',
                'tiene_IPS' => 'NO',
                'comentario' => 'Prueba',
            ]);

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseHas('pacientes', [
            'id_paciente' => $pacienteId,
            'nombre' => 'Juan Carlos',
            'tiene_ips' => 'NO',
        ]);
    }
}
