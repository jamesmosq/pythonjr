<?php

namespace App\Services;

use App\Models\Ejercicio;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnthropicGradingService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.key');
        $this->model  = config('services.anthropic.model', 'claude-sonnet-4-6');
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        $client = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(30);

        // En Windows/dev el bundle de certificados de cURL suele estar incompleto
        if (! app()->isProduction()) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    public function calificarEjercicio(User $user, Ejercicio $ejercicio, string $respuesta): array
    {
        $sistema = "Eres el evaluador de ejercicios de programación para {$user->name}. "
            . "Evalúa la respuesta del estudiante y responde ÚNICAMENTE con JSON válido en este formato exacto, "
            . "sin texto adicional: "
            . '{\"correcto\": boolean, \"es_perfecto\": boolean, \"feedback\": \"string motivador en español\", \"sugerencia\": \"string o null\"}. '
            . "El feedback debe ser alentador, directo y en español colombiano. "
            . "es_perfecto=true solo si la respuesta es impecable: comandos correctos, mensajes de commit descriptivos y flujo limpio.";

        $mensaje = "EJERCICIO:\n{$ejercicio->titulo}\n\n"
            . "DESCRIPCIÓN:\n{$ejercicio->enunciado}\n\n"
            . "CRITERIOS DE EVALUACIÓN (NO mostrar al estudiante):\n{$ejercicio->solucion}\n\n"
            . "RESPUESTA DEL ESTUDIANTE:\n{$respuesta}";

        try {
            $response = $this->http()->post('https://api.anthropic.com/v1/messages', [
                'model'      => $this->model,
                'max_tokens' => 512,
                'system'     => $sistema,
                'messages'   => [['role' => 'user', 'content' => $mensaje]],
            ]);

            $texto = $response->json('content.0.text', '{}');
            // Extraer JSON si viene envuelto en markdown
            if (preg_match('/\{.*\}/s', $texto, $matches)) {
                $texto = $matches[0];
            }
            $data = json_decode($texto, true) ?? [];

            return [
                'correcto'    => (bool) ($data['correcto'] ?? false),
                'es_perfecto' => (bool) ($data['es_perfecto'] ?? false),
                'feedback'    => $data['feedback'] ?? '¡Buen intento! Revisa tu respuesta e inténtalo de nuevo.',
                'sugerencia'  => $data['sugerencia'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('AnthropicGradingService error: ' . $e->getMessage());

            return [
                'correcto'    => false,
                'es_perfecto' => false,
                'feedback'    => 'No se pudo evaluar automáticamente. Intenta de nuevo en un momento.',
                'sugerencia'  => null,
            ];
        }
    }

    public function generarReporteHackathon(User $user, int $completados, int $perfectos, int $total, string $nombreHackathon): string
    {
        $sistema = "Eres el tutor de {$user->name}. Genera un reporte breve y motivador en español colombiano "
            . "sobre el desempeño en un hackathon. Máximo 3 párrafos cortos. Sin markdown ni listas.";

        $mensaje = "Hackathon: {$nombreHackathon}\n"
            . "Ejercicios completados: {$completados}/{$total}\n"
            . "Ejercicios perfectos: {$perfectos}/{$completados}\n"
            . "Genera un reporte motivador y constructivo para {$user->name}.";

        try {
            $response = $this->http()->post('https://api.anthropic.com/v1/messages', [
                'model'      => $this->model,
                'max_tokens' => 300,
                'system'     => $sistema,
                'messages'   => [['role' => 'user', 'content' => $mensaje]],
            ]);

            return $response->json('content.0.text', '¡Excelente participación en el hackathon!');
        } catch (\Throwable $e) {
            Log::error('AnthropicGradingService reporte error: ' . $e->getMessage());
            return "¡Muy bien, {$user->name}! Completaste {$completados} de {$total} ejercicios. ¡Sigue practicando!";
        }
    }
}
