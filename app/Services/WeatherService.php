<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Henter værmelding for Farsund/Lista fra met.no (yr.no). Cachet 3 timer,
 * best-effort – feiler stille (tom liste) om tjenesten ikke svarer.
 */
class WeatherService
{
    protected float $lat = 58.0955;   // Farsund

    protected float $lon = 6.8047;

    public function week(): array
    {
        return Cache::remember('weather.farsund.v1', now()->addHours(3), fn () => $this->fetch());
    }

    protected function fetch(): array
    {
        try {
            $resp = Http::withHeaders([
                'User-Agent' => 'VivuPlanner/1.0 (roger@havdurdesign.no)',
            ])->timeout(8)->get('https://api.met.no/weatherapi/locationforecast/2.0/compact', [
                'lat' => $this->lat,
                'lon' => $this->lon,
            ]);

            if (! $resp->successful()) {
                return [];
            }

            $byDay = [];
            foreach ($resp->json('properties.timeseries', []) as $pt) {
                $t = Carbon::parse($pt['time'])->timezone('Europe/Oslo');
                $day = $t->format('Y-m-d');
                $diff = abs((int) $t->format('H') - 12);

                if (isset($byDay[$day]) && $diff >= $byDay[$day]['_diff']) {
                    continue;
                }

                $symbol = $pt['data']['next_6_hours']['summary']['symbol_code']
                    ?? $pt['data']['next_1_hours']['summary']['symbol_code']
                    ?? null;
                $temp = $pt['data']['instant']['details']['air_temperature'] ?? null;

                $byDay[$day] = [
                    'date' => $day,
                    'temp' => $temp !== null ? (int) round($temp) : null,
                    'symbol' => $symbol,
                    'label' => $this->label($symbol),
                    '_diff' => $diff,
                ];
            }

            $out = [];
            foreach (array_slice(array_values($byDay), 0, 7) as $d) {
                unset($d['_diff']);
                $out[] = $d;
            }

            return $out;
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function label(?string $symbol): string
    {
        if (! $symbol) {
            return '';
        }
        $s = preg_replace('/_(day|night|polartwilight)$/', '', $symbol);
        $map = [
            'clearsky' => 'Klarvær', 'fair' => 'Lettskyet', 'partlycloudy' => 'Delvis skyet',
            'cloudy' => 'Skyet', 'fog' => 'Tåke',
            'lightrain' => 'Lett regn', 'rain' => 'Regn', 'heavyrain' => 'Kraftig regn',
            'lightrainshowers' => 'Lette regnbyger', 'rainshowers' => 'Regnbyger', 'heavyrainshowers' => 'Kraftige regnbyger',
            'lightsleet' => 'Lett sludd', 'sleet' => 'Sludd', 'heavysleet' => 'Kraftig sludd',
            'lightsnow' => 'Lett snø', 'snow' => 'Snø', 'heavysnow' => 'Kraftig snø',
            'lightsnowshowers' => 'Lette snøbyger', 'snowshowers' => 'Snøbyger',
            'thunderstorm' => 'Torden', 'rainshowersandthunder' => 'Regnbyger og torden',
            'sleetshowers' => 'Sluddbyger',
        ];

        return $map[$s] ?? ucfirst(str_replace('_', ' ', $s));
    }
}
