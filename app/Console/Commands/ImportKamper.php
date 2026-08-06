<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Services\KampImportService;
use Illuminate\Console\Command;

class ImportKamper extends Command
{
    protected $signature = 'kamper:import {--company= : Bare dette selskaps-ID-et}';

    protected $description = 'Synk hjemmekamper fra fotball.no-feed for selskaper som har satt opp en feed';

    public function handle(KampImportService $service): int
    {
        $companies = Company::query()
            ->when($this->option('company'), fn ($q, $id) => $q->where('id', $id))
            ->get()
            ->filter(fn (Company $c) => is_array($c->settings) && ! empty($c->settings['football_ical_url']));

        if ($companies->isEmpty()) {
            $this->info('Ingen selskaper med kamp-feed.');

            return self::SUCCESS;
        }

        foreach ($companies as $company) {
            // Bind aktivt selskap slik at tenant-skopet peker riktig under importen.
            app()->instance('currentCompany', $company);
            try {
                $result = $service->import($company);
                $this->info($company->name.': '.$result['synced'].' hjemmekamper synkronisert.');
            } catch (\Throwable $e) {
                $this->error($company->name.': '.$e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
