<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Services\NifTaService;
use Illuminate\Console\Command;

class ImportNifKamper extends Command
{
    protected $signature = 'kamper:import-nif {--company= : Selskaps-ID (ellers NIF_COMPANY_ID / slug flik)} {--orgid= : NIF orgid (ellers NIF_CLUB_ORGID)}';

    protected $description = 'Synk klubbens hjemmekamper fra NIF Turneringsadmin (håndball m.m.)';

    public function handle(NifTaService $service): int
    {
        $orgid = $this->option('orgid') ?: config('services.nif.club_orgid');
        if (! $orgid) {
            $this->error('Mangler orgid. Bruk --orgid=XXXXX eller sett NIF_CLUB_ORGID i .env.');

            return self::FAILURE;
        }

        $company = $this->option('company')
            ? Company::find($this->option('company'))
            : (config('services.nif.company_id')
                ? Company::find(config('services.nif.company_id'))
                : (Company::where('slug', 'flik')->first() ?: Company::first()));

        if (! $company) {
            $this->error('Fant ikke selskap.');

            return self::FAILURE;
        }

        // Bind aktivt selskap slik at tenant-skopet peker riktig under importen.
        app()->instance('currentCompany', $company);

        try {
            $r = $service->import($company, (string) $orgid);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info($company->name.': '.$r['synced'].' hjemmekamper synkronisert (av '.$r['total'].' kamper i feeden).');
        $this->line('Per idrett: '.json_encode($r['by_sport'], JSON_UNESCAPED_UNICODE));
        $this->line('Klubbens orgid i dataene (hjemmelag): '.$r['home_orgid']);
        $this->line('Fordeling per admOrgId: '.json_encode($r['breakdown']));

        return self::SUCCESS;
    }
}
