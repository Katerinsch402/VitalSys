<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = DB::table('users')->orderBy('id', 'asc')->paginate(25);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'rol' => 'required|string|in:admin,administrativo,recepcionista',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->rol = $request->rol;
        $user->doc_id = null;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->estado = 'activo';
        $user->save();

        $user->assignRole($request->rol);

        return redirect()->route('usuarios.index')->with('userCreateUpdate', 'Usuario creado correctamente');
    }

    public function update(Request $request)
    {
        $usuario = User::find($request->id);

        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'rol' => 'required|string|in:admin,administrativo,recepcionista'
        ]);

        $usuario->name = $request->name;
        $usuario->rol = $request->rol;
        $usuario->email = $request->email;
        $usuario->estado = 'activo';
        $usuario->doc_id = null;
        $usuario->save();

        $usuario->syncRoles($request->rol);

        return redirect()->route('usuarios.index')->with('userCreateUpdate', 'Usuario actualizado correctamente');
    }

    public function passChange(Request $request) {
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;

        if($password != $password_confirmation || $password == null || $password_confirmation == null) {
            return redirect()->route('usuarios.index')->with('passChangeError', 'Las contraseñas no coinciden');
        }
        
        if(strlen($request->password) < 8) {
            return redirect()->route('usuarios.index')->with('passChangeError', 'La contraseña debe tener al menos 8 caracteres');
        }
        
        $usuario = User::find($request->id);
        $usuario->password = bcrypt($password);
        $usuario->save();

        return redirect()->route('usuarios.index')->with('passChangeSuccess', 'Contraseña actualizada correctamente');
    }

    public function cambiarEstado(Request $request)
    {
        $usuario = User::find($request->id);
        $msg = '';
        
        if($usuario->estado == 'inactivo') {
            $msg = 'El usuario ' . $usuario->name . ' ha sido activado';
            $usuario->estado = 'activo';
            $usuario->save();
        }else if($usuario->estado == 'activo') {
            $msg = 'El usuario ' . $usuario->name . ' ha sido desactivado';
            $usuario->estado = 'inactivo';
            $usuario->save();
        }

        return redirect()->route('usuarios.index')->with('cambioEstado', $msg);
    }

    public function reportes()
    {
        $usuarios = User::all();
        return view('usuarios.reportes', compact('usuarios'));
    }
}
