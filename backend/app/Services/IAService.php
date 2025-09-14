<?php

namespace App\Services;

use OpenAI;
use Exception;

class IAService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.api_key'));
    }

    public function sugerirRespuesta(string $titulo, string $descripcion): string
    {
        try {
            $prompt = "Eres un agente de soporte. Basado en el título y la descripción, genera una respuesta breve y profesional.\n\n".
                      "Título: {$titulo}\n".
                      "Descripción: {$descripcion}\n\n".
                      "Respuesta:";

            $result = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente de soporte técnico.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 200,
            ]);

            return $result->choices[0]->message->content ?? 'No se pudo generar respuesta automática.';
        } catch (Exception $e) {
            $respuestasQuemadas = [
                "Gracias por su solicitud, estamos revisando su caso.",
                "Nuestro equipo de soporte analizará su situación y le responderá pronto.",
                "Hemos recibido su reporte y se encuentra en proceso de revisión.",
                "Estamos trabajando en su solicitud, le mantendremos informado.",
                "Recibido, te notificaremos cuando le demos seguimiento a tu caso.",
                "Este es el servicio de soporte, te notificaremos una vez le demos respuesta a tu ticket"
            ];

            return $respuestasQuemadas[array_rand($respuestasQuemadas)];
        }
    }
}
