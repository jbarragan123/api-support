<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use App\Models\HistorialCambio;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    // Cliente crea solicitud
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
        ]);

        $user = Auth::guard('api')->user();

        $solicitud = Solicitud::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($solicitud, 201);
    }

    // Listar solicitudes según rol
    public function index()
    {
        $user = Auth::guard('api')->user();

        if ($user->role->name === 'admin') {
            $solicitudes = Solicitud::all();
        } elseif ($user->role->name === 'soporte') {
            $solicitudes = Solicitud::where('assigned_to', $user->id)->get();
        } else {
            $solicitudes = Solicitud::where('user_id', $user->id)->get();
        }

        return response()->json($solicitudes);
    }

    // Actualizar solicitud (estado y respuesta)
    public function update(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $user = Auth::guard('api')->user();

        if (!in_array($user->role->name, ['admin','soporte'])) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $cambios = [];

        if ($request->has('status')) {
            $cambios[] = ['campo'=>'status', 'anterior'=>$solicitud->status, 'nuevo'=>$request->status];
            $solicitud->status = $request->status;
        }

        if ($request->has('respuesta')) {
            $cambios[] = ['campo'=>'respuesta', 'anterior'=>$solicitud->respuesta, 'nuevo'=>$request->respuesta];
            $solicitud->respuesta = $request->respuesta;
        }

        $solicitud->save();

        foreach ($cambios as $c) {
            HistorialCambio::create([
                'solicitud_id' => $solicitud->id,
                'user_id' => $user->id,
                'campo' => $c['campo'],
                'valor_anterior' => $c['anterior'],
                'valor_nuevo' => $c['nuevo'],
            ]);
        }

        return response()->json($solicitud);
    }
}