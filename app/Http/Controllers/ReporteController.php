<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;

class ReporteController extends Controller
{
    public function index()
    {
        $resumen = Solicitud::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        return response()->json($resumen);
    }
}
