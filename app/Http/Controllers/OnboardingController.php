<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OnboardingController extends Controller
{
    /** Tolk fritekst/regneark-tekst til strukturerte arrangement-forslag (oppretter ikke ennå). */
    public function parse(Request $request)
    {
        $data = $request->validate(['text' => ['required', 'string', 'max:20000']]);

        $key = config('services.anthropic.key');
        if (! $key) {
            return response()->json(['error' => 'AI er ikke aktivert (mangler ANTHROPIC_API_KEY).'], 422);
        }

        $system = 'Du er en assistent som strukturerer en idrettsklubbs årshjul for SOSIALE MEDIER. '
            .'Årshjulet handler om KOMMUNIKASJONSØYEBLIKK (hva klubben skal si utad), IKKE om selve treningskalenderen. '
            .'Returner KUN gyldig JSON, uten forklaring eller kodeblokk, på formen: '
            .'{"events":[{"title":"...","date":"YYYY-MM-DD eller null","sport":"idrett eller gruppe eller null",'
            .'"type":"Event","goal":"kort hovedmål eller null","description":"kort beskrivelse eller null"}]}. '
            .'VIKTIGSTE REGEL – IKKE ETT ARRANGEMENT PER TRENING: Hvis teksten inneholder en fast eller roterende '
            .'trenings-/aktivitetsplan (f.eks. en ukentlig plan der samme tilbud går igjen, eller idretten roterer uke for uke), '
            .'skal du IKKE lage ett arrangement per rad/økt/uke. Slå det sammen til noen få arrangement: '
            .'typisk ETT for oppstart/første samling og ETT for avslutning. Da bør beskrivelsen nevne at det er et gjentakende '
            .'tilbud, og goal kan være «rekruttering» eller «avslutning». '
            .'Ett enkeltstående arrangement (turnering, årsmøte, dugnad, cup, stevne, kick-off) blir ett arrangement hver. '
            .'Hvis bare måned eller uke er nevnt, gjett en fornuftig dato. Ikke finn på arrangement som ikke står i teksten. '
            .'Vær heller for gjerrig enn for rundhåndet – det er bedre å foreslå for få enn for mange.';

        $resp = Http::withHeaders([
            'x-api-key' => $key,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model'),
            'max_tokens' => 4000,
            'system' => $system,
            'messages' => [['role' => 'user', 'content' => $data['text']]],
        ]);

        if (! $resp->successful()) {
            return response()->json(['error' => 'AI-tjenesten svarte ikke ('.$resp->status().').'], 502);
        }

        $json = $this->extractJson($resp->json('content.0.text', ''));
        $raw = is_array($json['events'] ?? null) ? $json['events'] : [];

        $events = [];
        foreach ($raw as $e) {
            if (! is_array($e) || empty($e['title'])) {
                continue;
            }
            $events[] = [
                'title' => mb_substr((string) $e['title'], 0, 255),
                'date' => $this->normDate($e['date'] ?? null),
                'sport' => isset($e['sport']) && $e['sport'] !== '' ? (string) $e['sport'] : null,
                'type' => isset($e['type']) && $e['type'] !== '' ? (string) $e['type'] : 'Event',
                'goal' => isset($e['goal']) && $e['goal'] !== '' ? (string) $e['goal'] : null,
                'description' => isset($e['description']) && $e['description'] !== '' ? (string) $e['description'] : null,
            ];
        }

        return response()->json(['events' => $events]);
    }

    /** Opprett de bekreftede arrangementene. */
    public function import(Request $request)
    {
        $data = $request->validate([
            'events' => ['required', 'array', 'min:1'],
            'events.*.title' => ['required', 'string', 'max:255'],
            'events.*.date' => ['nullable', 'date'],
            'events.*.sport' => ['nullable', 'string', 'max:100'],
            'events.*.type' => ['nullable', 'string', 'max:50'],
            'events.*.goal' => ['nullable', 'string', 'max:100'],
            'events.*.description' => ['nullable', 'string', 'max:2000'],
        ]);

        $cats = Category::pluck('id', 'name'); // skopert til aktivt selskap
        $created = 0;

        foreach ($data['events'] as $e) {
            $catId = null;
            if (! empty($e['sport'])) {
                foreach ($cats as $name => $id) {
                    if (mb_strtolower($name) === mb_strtolower($e['sport'])) {
                        $catId = $id;
                        break;
                    }
                }
            }

            Event::create([
                'category_id' => $catId,
                'title' => $e['title'],
                'type' => $e['type'] ?? 'Event',
                'goal' => $e['goal'] ?? null,
                'description' => $e['description'] ?? null,
                'event_date' => $e['date'] ?? null,
                'recurrence' => 'none',
                'approval_status' => 'utkast',
                'created_by' => $request->user()->id,
            ]);
            $created++;
        }

        return response()->json(['created' => $created]);
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

    private function normDate($d): ?string
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
}
