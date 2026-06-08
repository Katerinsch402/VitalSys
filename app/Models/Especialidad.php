<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_especialidad';
    public $table = 'especialidades';
    public $fillable = [
        'nombre'
    ];

    public function medicos (){
        return $this->hasMany('App\Models\Medico');
    }
}
