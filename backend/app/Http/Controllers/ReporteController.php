<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;

class ReporteController extends Controller
{
    public function index()
    {
        $resumen = Solicitud::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status'); 

        return response()->json([
            'success' => true,
            'message' => 'Resumen de solicitudes por estado obtenido correctamente',
            'data'    => $resumen
        ], 200);
    }
}
