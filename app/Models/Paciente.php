<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_paciente';
    public $table = 'pacientes';
    protected $fillable = [
        'cod_paciente',
        'num_doc',
        'nombre',
        'apellido',
        'ciudad',
        'departamento',
        'direccion',
        'edad',
        'sexo',
        'tiene_ips',
        'diagnostico',
        'comentario'
    ];

    public function tiposDeEnfermedad()
    {
        return $this->belongsToMany(TipoDeEnfermedad::class, 'paciente_tipo_enfermedad', 'paciente_id', 'tipo_enfermedad_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id', 'id_paciente');
    }
}