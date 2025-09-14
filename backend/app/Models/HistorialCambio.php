<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialCambio extends Model
{
    protected $fillable = [
        'solicitud_id',
        'changed_by',
        'field_changed',
        'old_value',
        'new_value',
    ];

    // Relación: solicitud a la que pertenece el cambio
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    // Relación: usuario que hizo el cambio
    public function usuario()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}