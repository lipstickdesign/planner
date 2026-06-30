<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    /**
     * Foreslå (eller oppdater) et tekstutkast for en oppgave, i FLIKs stemme.
     * - existing: oppdaterer fjorårets tekst til nytt år (rett årstall/årsklasser).
     * - draft: brukerens eget utkast/stikkord i tekstboksen – bygges videre på.
     * - brief: praktisk info på eventet (utstyr, hva de må ha med, datoer) – flettes inn.
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
            'brief' => ['nullable', 'string', 'max:3000'],
            'draft' => ['nullable', 'string', 'max:5000'],
            'existing' => ['nullable', 'string', 'max:5000'],
            'year' => ['nullable', 'string', 'max:10'],
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

        // Felles kontekst om arrangementet
        $ctx = "Arrangement: {$data['title']}\n"
            .(! empty($data['sport']) ? "Idrett/gruppe: {$data['sport']}\n" : '')
            .(! empty($data['label']) ? "Type innlegg i forløpet: {$data['label']}\n" : '')
            .(! empty($data['date']) ? "Publiseringsdato: {$data['date']}\n" : '')
            .(! empty($data['goal']) ? "Hovedmål: {$data['goal']}\n" : '')
            .(! empty($data['extra']) ? "Beskrivelse: {$data['extra']}\n" : '')
            .(! empty($data['brief']) ? "Praktisk info som bør med (stikkord): {$data['brief']}\n" : '');

        if (! empty($data['existing'])) {
            // Oppdater fjorårets tekst til nytt år
            $user = 'Her er fjorårets innlegg for et arrangement som nå arrangeres på nytt'
                .(! empty($data['year']) ? " i {$data['year']}" : '').".\n"
                .'Oppdater teksten til årets utgave: rett opp årstall, datoer og fødselsår/årsklasser '
                .'(flytt fødselsår og årsklasser ett år frem der det er nevnt), men behold stil, '
                ."budskap og omtrentlig lengde.\n".$ctx
                ."\nFjorårets tekst:\n{$data['existing']}\n\nSkriv kun den oppdaterte teksten.";
        } elseif (! empty($data['draft'])) {
            // Bygg videre på brukerens eget utkast/stikkord
            $user = "Brukeren har skrevet dette utkastet/stikkordene – bygg videre på det og behold "
                ."meningen, men gjør det til et ferdig, godt Facebook-innlegg:\n\"{$data['draft']}\"\n\n"
                .$ctx."\nSkriv kun selve innlegget, klart til å lime inn.";
        } else {
            // Helt nytt forslag
            $user = "Lag et utkast til ett Facebook-innlegg.\n".$ctx
                .'Skriv kun selve innlegget, klart til å lime inn.';
        }

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
