<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoConsulta extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_consulta';
    public $table='tipo_consultas';
    protected $fillable = [
        'descripcion',
        'duracion'
    ];
}
