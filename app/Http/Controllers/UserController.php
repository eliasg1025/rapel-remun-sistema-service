<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get(Request $request)
    {
        $activo = filter_var($request->query('activo'), FILTER_VALIDATE_BOOLEAN);

        $result = Usuario::_get($activo);

        return response()->json([
            'data' => $result
        ]);
    }

    public function store(Request $request)
    {
        $result = Usuario::register($request->all());
        return response()->json([
            'message' => $result['message']
        ], $result['error'] ? 400 : 200);
    }

    public function update(Request $request, Usuario $usuario)
    {
        $result = Usuario::_update($request->all(), $usuario);
        return response()->json([
            'message' => $result['message']
        ], $result['error'] ? 400 : 200);
    }

    public function toggleActivate(Usuario $usuario)
    {
        $usuario->activo = !$usuario->activo;
        $usuario->save();

        $message = $usuario->activo ? 'activado' : 'desactivado';
        return response()->json([
            'message' => "Usuario " . $message . " correctamente"
        ], 200);
    }
}