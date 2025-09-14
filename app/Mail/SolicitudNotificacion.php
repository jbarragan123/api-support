<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Solicitud;

class SolicitudNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    public $mensaje;

    /**
     * Create a new message instance.
     */
    public function __construct(Solicitud $solicitud, string $mensaje)
    {
        $this->solicitud = $solicitud;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Notificación de Solicitud')
                    ->view('emails.solicitud_notificacion')
                    ->with([
                        'solicitud' => $this->solicitud,
                        'mensaje' => $this->mensaje,
                    ]);
    }
}
