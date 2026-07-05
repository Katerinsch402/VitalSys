<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CitaStoreTest extends TestCase
{
    public function test_new_cita_can_be_created_with_pending_state_via_json(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser' . uniqid() . '@example.com',
            'rol' => 'admin',
            'estado' => 'activo',
            'doc_id' => '12345678',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $especialidadId = DB::table('especialidades')->insertGetId([
            'nombre' => 'Medicina General',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $medicoId = DB::table('medicos')->insertGetId([
            'nombre' => 'Dr. Test',
            'ci' => '12345678',
            'telefono' => '12345678',
            'registro' => 'REG-001',
            'especialidad_id' => $especialidadId,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pacienteId = DB::table('pacientes')->insertGetId([
            'cod_paciente' => 'PAC-001',
            'num_doc' => '12345678',
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'ciudad' => 'Ciudad Test',
            'departamento' => 'Departamento Test',
            'direccion' => 'Calle 1',
            'edad' => 30,
            'sexo' => 'M',
            'tiene_ips' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $salaId = DB::table('salas')->insertGetId([
            'tipo_sala' => 'Consulta',
            'num_sala' => '101',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tipoConsultaId = DB::table('tipo_consultas')->insertGetId([
            'descripcion' => 'Consulta general',
            'duracion' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'medico_id' => $medicoId,
            'paciente_id' => $pacienteId,
            'fec_inicio' => '2026-07-02T10:00',
            'fec_fin' => '2026-07-02T10:30',
            'sala_id' => $salaId,
            'tipo_consulta_id' => $tipoConsultaId,
            'observaciones' => 'Consulta de prueba',
        ];

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->postJson(route('citas.store'), $payload);

        $response->assertStatus(200);
        $response->assertSee('Cita creada correctamente', false);
        $this->assertDatabaseHas('citas', [
            'paciente_id' => $pacienteId,
            'estado' => 'Pendiente',
        ]);

        $cita = DB::table('citas')->where('paciente_id', $pacienteId)->latest('id_cita')->first();
        $this->assertNotNull($cita);
        $this->assertEquals(15, \Carbon\Carbon::parse($cita->fec_fin)->diffInMinutes(\Carbon\Carbon::parse($cita->fec_inicio)));
    }

    public function test_new_cita_can_be_created_with_short_observation(): void
    {
        $user = User::create([
            'name' => 'Test User 2',
            'email' => 'testuser2' . uniqid() . '@example.com',
            'rol' => 'admin',
            'estado' => 'activo',
            'doc_id' => '87654321',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $especialidadId = DB::table('especialidades')->insertGetId([
            'nombre' => 'Pediatría',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $medicoId = DB::table('medicos')->insertGetId([
            'nombre' => 'Dr. Short',
            'ci' => '87654321',
            'telefono' => '87654321',
            'registro' => 'REG-002',
            'especialidad_id' => $especialidadId,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pacienteId = DB::table('pacientes')->insertGetId([
            'cod_paciente' => 'PAC-002',
            'num_doc' => '87654321',
            'nombre' => 'Ana',
            'apellido' => 'Lopez',
            'ciudad' => 'Ciudad Test',
            'departamento' => 'Departamento Test',
            'direccion' => 'Calle 2',
            'edad' => 28,
            'sexo' => 'F',
            'tiene_ips' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $salaId = DB::table('salas')->insertGetId([
            'tipo_sala' => 'Consulta',
            'num_sala' => '102',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tipoConsultaId = DB::table('tipo_consultas')->insertGetId([
            'descripcion' => 'Consulta rápida',
            'duracion' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->postJson(route('citas.store'), [
                'medico_id' => $medicoId,
                'paciente_id' => $pacienteId,
                'fec_inicio' => '2026-07-02T11:00',
                'fec_fin' => '2026-07-02T11:15',
                'sala_id' => $salaId,
                'tipo_consulta_id' => $tipoConsultaId,
                'observaciones' => 'Cita',
            ]);

        $response->assertStatus(200);
        $response->assertSee('Cita creada correctamente', false);
        $this->assertDatabaseHas('citas', [
            'paciente_id' => $pacienteId,
            'observaciones' => 'Cita',
            'estado' => 'Pendiente',
        ]);
    }
}
