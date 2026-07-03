<?php

namespace App\Services;

use App\Models\KlubblivPost;
use App\Models\Task;
use Carbon\Carbon;

/**
 * Kalender-bevisst datoforslag: sprer poster fornuftig utover slik at det ikke
 * havner mange på samme dag / samme kanal. Brukes av «Foreslå plan» og ved
 * plassering av enkeltposter.
 */
class PublishingPlanner
{
    /** 'Y-m-d' => ['total' => int, 'dest' => [destId => count]] */
    protected array $map = [];

    protected bool $loaded = false;

    protected int $maxPerDay = 2;          // maks poster totalt per dag

    protected int $maxPerDestPerDay = 1;   // maks poster per kanal per dag

    public function loadCalendar(?int $ignoreTaskId = null, ?int $ignoreKlubblivId = null): void
    {
        $this->map = [];

        Task::with('destinations')
            ->whereNotNull('publish_date')
            ->when($ignoreTaskId, fn ($q) => $q->where('id', '!=', $ignoreTaskId))
            ->get()
            ->each(function (Task $t) {
                $dests = $t->destinations->pluck('id')->all();
                $this->bump($t->publish_date->format('Y-m-d'), $dests ?: [0]);
            });

        KlubblivPost::whereNotNull('publish_date')
            ->when($ignoreKlubblivId, fn ($q) => $q->where('id', '!=', $ignoreKlubblivId))
            ->get()
            ->each(function (KlubblivPost $p) {
                $this->bump($p->publish_date->format('Y-m-d'), $p->destination_ids ?: [0]);
            });

        $this->loaded = true;
    }

    protected function bump(string $key, array $dests): void
    {
        if (! isset($this->map[$key])) {
            $this->map[$key] = ['total' => 0, 'dest' => []];
        }
        $this->map[$key]['total']++;
        foreach ($dests as $d) {
            $this->map[$key]['dest'][$d] = ($this->map[$key]['dest'][$d] ?? 0) + 1;
        }
    }

    /**
     * Finn nærmeste fornuftige dato til ønsket dato, gitt kanalene posten skal på.
     * Reserverer valgt dato i kartet slik at flere kall (samme plan) sprer seg.
     */
    public function suggestDate(Carbon $desired, array $destIds = []): Carbon
    {
        if (! $this->loaded) {
            $this->loadCalendar();
        }

        $today = Carbon::today();
        $base = $desired->copy()->startOfDay();
        if ($base->lt($today)) {
            $base = $today->copy();
        }

        foreach ([0, -1, 1, -2, 2, -3, 3, -4, 4, -5, 5, -6, 6, -7, 7] as $off) {
            $cand = $base->copy()->addDays($off);
            if ($cand->lt($today)) {
                continue;
            }
            if ($this->isFree($cand, $destIds)) {
                $this->bump($cand->format('Y-m-d'), $destIds ?: [0]);

                return $cand;
            }
        }

        // Ingen ledig funnet – bruk ønsket dato likevel
        $this->bump($base->format('Y-m-d'), $destIds ?: [0]);

        return $base;
    }

    protected function isFree(Carbon $date, array $destIds): bool
    {
        $day = $this->map[$date->format('Y-m-d')] ?? null;
        if (! $day) {
            return true;
        }
        if ($day['total'] >= $this->maxPerDay) {
            return false;
        }
        foreach (($destIds ?: [0]) as $d) {
            if (($day['dest'][$d] ?? 0) >= $this->maxPerDestPerDay) {
                return false;
            }
        }

        return true;
    }
}
