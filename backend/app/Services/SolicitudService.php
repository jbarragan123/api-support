<?php

namespace App\Services;

use App\Models\User;
use App\Models\Solicitud;
use App\Models\HistorialCambio;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudNotificacion;

class SolicitudService
{
    public function listarPorRol($user)
    {
        if ($user->role->name === 'role_admin') {
            return Solicitud::with(['user','soporte'])->get();
        } elseif ($user->role->name === 'role_support') {
            return Solicitud::with(['user','soporte'])
                ->where('assigned_to', $user->id)
                ->get();
        } else {
            return Solicitud::with(['user','soporte'])
                ->where('user_id', $user->id)
                ->get();
        }
    }

    public function crear($user, array $data)
    {
        // Encontrar el usuario de soporte con menos solicitudes abiertas
        $soporteUser = User::whereHas('role', function ($query) {
            $query->where('name', 'role_support');
        })
        ->withCount(['solicitudesAsignadas' => function ($query) {
            $query->where('status', 'abierto');
        }])
        ->orderBy('solicitudes_asignadas_count', 'asc')
        ->first();

        if (!$soporteUser) {
            throw new \Exception('No hay usuarios de soporte disponibles');
        }

        $solicitud = Solicitud::create([
            'user_id'     => $user->id,
            'title'       => $data['title'],
            'description' => $data['description'],
            'status'      => 'abierto',
            'assigned_to' => $soporteUser->id,
        ]);

        // Enviar correo de notificación al usuario
        Mail::to($user->email)->send(
            new SolicitudNotificacion($solicitud, "Tu solicitud ha sido creada correctamente.")
        );

        return $solicitud;
    }

    public function actualizar($user, $id, array $data)
    {
        $solicitud = Solicitud::find($id);
        if (!$solicitud) return null;

        $cambios = [];

        if (isset($data['status']) && in_array($data['status'], ['abierto', 'en progreso', 'cerrado'])) {
            if ($solicitud->status !== $data['status']) {
                $cambios[] = ['campo' => 'status', 'anterior' => $solicitud->status, 'nuevo' => $data['status']];
                $solicitud->status = $data['status'];
            }
        }

        if (isset($data['response']) && trim($data['response']) !== '') {
            if ($solicitud->response !== $data['response']) {
                $cambios[] = ['campo' => 'response', 'anterior' => $solicitud->response, 'nuevo' => $data['response']];
                $solicitud->response = $data['response'];
            }
        }

        if (!empty($cambios)) {
            $solicitud->save();

            foreach ($cambios as $c) {
                HistorialCambio::create([
                    'solicitud_id' => $solicitud->id,
                    'changed_by' => $user->id,
                    'field_changed' => $c['campo'],
                    'old_value' => $c['anterior'],
                    'new_value' => $c['nuevo'],
                ]);
            }

            Mail::to($solicitud->user->email)->send(
                new SolicitudNotificacion($solicitud, "Tu solicitud ha sido actualizada: {$solicitud->status}")
            );
        }

        return $solicitud->load('user');
    }
}
