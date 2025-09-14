<?php

namespace App\Services;

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
        $solicitud = Solicitud::create([
            'user_id'     => $user->id,
            'title'       => $data['title'],
            'description' => $data['description'],
            'status'      => 'abierto',
            'assigned_to' => $data['assigned_to'] ?? null,
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

        if (isset($data['status']) && $solicitud->status !== $data['status']) {
            $cambios[] = ['campo'=>'status','anterior'=>$solicitud->status,'nuevo'=>$data['status']];
            $solicitud->status = $data['status'];
        }

        if (isset($data['response']) && $solicitud->response !== $data['response']) {
            $cambios[] = ['campo'=>'response','anterior'=>$solicitud->response,'nuevo'=>$data['response']];
            $solicitud->response = $data['response'];
        }

        $solicitud->save();

        foreach ($cambios as $c) {
            HistorialCambio::create([
                'solicitud_id'  => $solicitud->id,
                'changed_by'    => $user->id,
                'field_changed' => $c['campo'],
                'old_value'     => $c['anterior'],
                'new_value'     => $c['nuevo'],
            ]);
        }

        Mail::to($solicitud->user->email)->send(
            new SolicitudNotificacion($solicitud, "Tu solicitud ha sido actualizada.")
        );

        return $solicitud;
    }
}
