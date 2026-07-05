<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PacienteRegistrationFormTest extends TestCase
{
    public function test_registration_form_can_be_rendered_without_tipo_de_enfermedad_model(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'patient-form-' . uniqid() . '@example.com',
            'rol' => 'admin',
            'estado' => 'activo',
            'doc_id' => '77777777',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($user)
            ->get(route('registro-paciente'));

        $response->assertStatus(200);
    }
}
