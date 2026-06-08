<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_sala';
    public $table='salas';
    protected $fillable = [
        'nombre',
        'tipo_sala',
        'num_sala'
    ];
}
