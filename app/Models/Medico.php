<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_medico';
    public $table = 'medicos';
    public $fillable = [
        'nombre',
        'ci',
        'email',
        'telefono',
        'registro',
        'especialidad_id',
        'estado'
    ];

    public function especialidad()
    {
        return $this->belongsTo('App\Models\Especialidad', 'especialidad_id');
    }
}
