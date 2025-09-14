<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Services\SolicitudService;
use App\Models\User;
use App\Models\Solicitud;

class SolicitudController extends Controller
{
    protected $solicitudService;

    public function __construct(SolicitudService $solicitudService)
    {
        $this->solicitudService = $solicitudService;
    }

    // Listar solicitudes según rol
    public function index()
    {
        $user = Auth::guard('api')->user();
        $solicitudes = $this->solicitudService->listarPorRol($user);

        return response()->json([
            'success' => true,
            'message' => 'Listado de solicitudes obtenido correctamente',
            'data'    => $solicitudes
        ], 200);
    }

    // Cliente crea solicitud
    public function store(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();

            $request->validate([
                'title' => 'required|string|max:100|filled',
                'description' => 'required|string|max:1000|filled',
                'assigned_to' => [
                    'nullable',
                    'integer',
                    'exists:users,id',
                    function ($attribute, $value, $fail) {
                        if ($value) {
                            $assignedUser = User::find($value);
                            if ($assignedUser && $assignedUser->role->name !== 'role_support') {
                                $fail('El usuario asignado debe ser de soporte.');
                            }
                        }
                    }
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors'  => $e->errors()
            ], 422);
        }

        $user = Auth::guard('api')->user();

        if ($user->role->name !== 'role_client') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los clientes pueden crear solicitudes.'
            ], 403);
        }

        $solicitud = $this->solicitudService->crear($user, $request->only(['title', 'description', 'assigned_to']));

        return response()->json([
            'success' => true,
            'message' => 'Solicitud creada correctamente',
            'data'    => $solicitud
        ], 201);
    }

    // Actualizar solicitud (solo soporte/admin)
    public function update(Request $request, $id)
    {
        $solicitud = Solicitud::find($id);

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        $user = Auth::guard('api')->user();

        if (!in_array($user->role->name, ['role_admin','role_support'])) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        try {
            $request->validate([
                'status' => [
                    'sometimes',
                    'string',
                    'in:abierto,en progreso,cerrado',
                    function ($attribute, $value, $fail) use ($solicitud) {
                        if ($solicitud->status === 'cerrado' && $value !== 'cerrado') {
                            $fail('No se puede reabrir una solicitud cerrada.');
                        }
                    }
                ],
                'response' => [
                    'sometimes',
                    'string',
                    'max:1000',
                    function ($attribute, $value, $fail) use ($request) {
                        if (($request->status ?? null) === 'cerrado' && empty(trim($value))) {
                            $fail('Debe agregar una respuesta al cerrar la solicitud.');
                        }
                    }
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors'  => $e->errors()
            ], 422);
        }

        $solicitud = $this->solicitudService->actualizar($user, $id, $request->only(['status', 'response']));

        return response()->json([
            'success' => true,
            'message' => 'Solicitud actualizada correctamente',
            'data'    => $solicitud
        ], 200);
    }
}
