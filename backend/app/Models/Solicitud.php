<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;
    protected $table = 'solicitudes'; 

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'assigned_to',
        'response', 
    ];

    // Relación: usuario que creó la solicitud
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function soporte()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function historialCambios()
    {
        return $this->hasMany(HistorialCambio::class);
    }
}