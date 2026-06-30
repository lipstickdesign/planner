<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    /**
     * Foreslå et tekstutkast (Facebook-innlegg) for en oppgave, i FLIKs stemme.
     */
    public function suggest(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sport' => ['nullable', 'string', 'max:100'],
            'label' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'string', 'max:30'],
            'goal' => ['nullable', 'string', 'max:100'],
            'extra' => ['nullable', 'string', 'max:1000'],
        ]);

        $key = config('services.anthropic.key');
        if (! $key) {
            return response()->json([
                'error' => 'AI er ikke aktivert ennå. Legg ANTHROPIC_API_KEY inn i .env på serveren.',
            ], 422);
        }

        $system = 'Du er innholdsansvarlig for Farsund og Lista Idrettsklubb (FLIK), '
            .'en lokal, frivillig allidrettsklubb. Skriv varme, inkluderende og engasjerende '
            .'Facebook-innlegg på norsk (bokmål), fulle av idrettsglede. Bruk gjerne noen passende '
            .'emojier. Hold det kort og konkret, med en tydelig oppfordring (CTA) til slutt. '
            .'Ikke bruk hashtags med mindre det er naturlig.';

        $user = "Lag et utkast til ett Facebook-innlegg.\n"
            ."Arrangement: {$data['title']}\n"
            .(! empty($data['sport']) ? "Idrett/gruppe: {$data['sport']}\n" : '')
            .(! empty($data['label']) ? "Type innlegg i forløpet: {$data['label']}\n" : '')
            .(! empty($data['date']) ? "Publiseringsdato: {$data['date']}\n" : '')
            .(! empty($data['goal']) ? "Hovedmål: {$data['goal']}\n" : '')
            .(! empty($data['extra']) ? "Ekstra info: {$data['extra']}\n" : '')
            .'Skriv kun selve innlegget, klart til å lime inn.';

        $resp = Http::withHeaders([
            'x-api-key' => $key,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(40)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model'),
            'max_tokens' => 700,
            'system' => $system,
            'messages' => [
                ['role' => 'user', 'content' => $user],
            ],
        ]);

        if (! $resp->successful()) {
            return response()->json([
                'error' => 'AI-tjenesten svarte ikke ('.$resp->status().'). Sjekk API-nøkkelen.',
            ], 502);
        }

        return response()->json([
            'text' => $resp->json('content.0.text', ''),
        ]);
    }
}
