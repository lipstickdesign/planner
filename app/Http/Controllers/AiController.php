<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    /**
     * Vurder en hel publiseringsplan for et arrangement.
     * Returnerer FORSLAG (add/adjust/flag) – oppretter/endrer INGENTING.
     * Brukeren godkjenner selv hva som skal brukes.
     */
    public function reviewPlan(Event $event)
    {
        $key = config('services.anthropic.key');
        if (! $key) {
            return response()->json([
                'error' => 'AI er ikke aktivert ennå. Legg ANTHROPIC_API_KEY inn i .env på serveren.',
            ], 422);
        }

        $event->loadMissing('tasks');
        $today = now()->format('Y-m-d');

        $existing = $event->tasks
            ->sortBy(fn ($t) => $t->publish_date?->format('Y-m-d') ?? '9999')
            ->map(fn ($t) => [
                'id' => $t->id,
                'label' => $t->label,
                'date' => optional($t->publish_date)->format('Y-m-d'),
                'platform' => $t->platform,
                'format' => $t->format,
                'har_tekst' => ! empty($t->body_draft),
                'status' => $t->status,
            ])->values();

        $ctx = "Arrangement: {$event->title}\n"
            .'Idrett/gruppe: '.($event->category->name ?? 'ukjent')."\n"
            .'Arrangementsdato: '.(optional($event->event_date)->format('Y-m-d') ?? 'ukjent')."\n"
            .'Type: '.($event->type ?? '')."\n"
            .'Hovedmål: '.($event->goal ?? '')."\n"
            .'I dag er: '.$today."\n\n"
            .'Eksisterende oppgaver (JSON):'."\n".$existing->toJson(JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $system = 'Du er innholdsstrateg for en idrettsklubb og vurderer en publiseringsplan (årshjul) for '
            .'sosiale medier for ETT arrangement. Målet er god, jevn kommunikasjon rundt arrangementet – '
            .'typisk teaser/«sett av dato» i forkant, påmelding, påminnelse rett før, noe på selve dagen, '
            .'og gjerne en oppfølging/takk etterpå. Tilpass antall og timing til hva slags arrangement det er. '
            .'VIKTIG: for faste eller roterende treningsplaner skal du IKKE foreslå én post per økt. '
            .'Returner KUN gyldig JSON (ingen forklaring, ingen kodeblokk) på formen: '
            .'{"add":[{"label":"...","date":"YYYY-MM-DD","platform":"facebook|instagram|tiktok","format":"post|story","reason":"kort begrunnelse"}],'
            .'"adjust":[{"id":<tall>,"date":"YYYY-MM-DD eller null","platform":"... eller null","format":"... eller null","reason":"kort begrunnelse"}],'
            .'"remove":[{"id":<tall>,"reason":"kort begrunnelse"}]}. '
            .'Regler: "add" = oppgaver som mangler for god dekning – men IKKE foreslå en ny oppgave dersom en '
            .'tilsvarende oppgave allerede finnes i lista (da heller la den stå, eller juster den). "adjust" = KUN når '
            .'en eksisterende oppgave har åpenbart feil dato/kanal (f.eks. publisering etter at arrangementet er over, '
            .'eller urealistisk timing) – ta bare med feltene som skal endres, resten null. "remove" = oppgaver som bør '
            .'fjernes: klare duplikater av hverandre, eller overflødige/feilplasserte oppgaver. Vær forsiktig med "remove" '
            .'– foreslå kun fjerning når oppgaven tydelig er unødvendig eller en duplikat. Målet er en REN, '
            .'ikke-overlappende plan i kronologisk rekkefølge. Hvis planen allerede er god og komplett, returner tomme '
            .'lister. Bruker godkjenner alt selv. Hold begrunnelsene korte. '
            .'Datoer må være realistiske i forhold til arrangementsdatoen og dagens dato.';

        $resp = Http::withHeaders([
            'x-api-key' => $key,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model'),
            'max_tokens' => 1800,
            'system' => $system,
            'messages' => [['role' => 'user', 'content' => $ctx]],
        ]);

        if (! $resp->successful()) {
            return response()->json([
                'error' => 'AI-tjenesten svarte ikke ('.$resp->status().'). Sjekk API-nøkkelen.',
            ], 502);
        }

        $json = $this->extractJson($resp->json('content.0.text', ''));
        $validIds = $event->tasks->pluck('id')->all();

        // Rens og valider mot faktiske oppgaver på DETTE arrangementet.
        $add = collect($json['add'] ?? [])->filter(fn ($a) => is_array($a) && ! empty($a['label']))
            ->map(fn ($a) => [
                'label' => mb_substr((string) $a['label'], 0, 255),
                'date' => $this->cleanDate($a['date'] ?? null),
                'platform' => $a['platform'] ?? null,
                'format' => $a['format'] ?? null,
                'reason' => isset($a['reason']) ? mb_substr((string) $a['reason'], 0, 300) : null,
            ])->values();

        $adjust = collect($json['adjust'] ?? [])
            ->filter(fn ($a) => is_array($a) && isset($a['id']) && in_array((int) $a['id'], $validIds, true))
            ->map(fn ($a) => [
                'id' => (int) $a['id'],
                'date' => $this->cleanDate($a['date'] ?? null),
                'platform' => ($a['platform'] ?? null) ?: null,
                'format' => ($a['format'] ?? null) ?: null,
                'reason' => isset($a['reason']) ? mb_substr((string) $a['reason'], 0, 300) : null,
            ])->values();

        $remove = collect($json['remove'] ?? [])
            ->filter(fn ($a) => is_array($a) && isset($a['id']) && in_array((int) $a['id'], $validIds, true))
            ->map(fn ($a) => [
                'id' => (int) $a['id'],
                'reason' => isset($a['reason']) ? mb_substr((string) $a['reason'], 0, 300) : null,
            ])->values();

        return response()->json(['add' => $add, 'adjust' => $adjust, 'remove' => $remove]);
    }

    private function extractJson(string $text): array
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');
        if ($start === false || $end === false || $end < $start) {
            return [];
        }
        $j = json_decode(substr($text, $start, $end - $start + 1), true);

        return is_array($j) ? $j : [];
    }

    private function cleanDate($d): ?string
    {
        if (! $d) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($d)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

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
