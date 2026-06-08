<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepaCiudadController extends Controller
{
    public function index(){
        return view('departamentos.RegistroCiudades');
    }
}
