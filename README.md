# Vivu Planner

Fler-selskaps planleggingsplattform for sosiale medier og arrangement — et «årshjul» med
eventer, oppgaver, kanaler/destinasjoner, team og kunde-godkjenning. Bygget for FLIK som
første kunde, men multi-tenant fra start slik at flere selskaper kan bruke samme løsning.

> Dette er **Sprint 1**: datamodell, multi-tenancy og FLIK-import. Frontend (Inertia/Vue),
> godkjenningsflyt, posttyper/AI, abonnement og auto-publisering kommer i påfølgende sprinter.

## Stack
- PHP 8.3 · Laravel 13 · MySQL 8
- Multi-tenancy: row-level (`company_id` + global scope via `BelongsToCompany`)
- Hosting: Cloudways (samme server som Vivu / vivu consent), egen database `vivu_planner`

## Kom i gang (lokalt)
```bash
composer install
cp .env.example .env
php artisan key:generate
# sett DB-tilkobling i .env, så:
php artisan migrate --seed
php artisan serve
```
Seederen legger inn FLIK med idretter, FB-destinasjoner, hele årshjulet for 2026 og
publiseringsplaner for utvalgte eventer.

**Demo-innlogging** (etter at auth-scaffold er lagt til i neste sprint):
`roger@havdurdesign.no` / `password` — superadministrator.

## Datamodell (kjernen)
```
Selskap (company) → Kategori (idrett/gruppe) → Event → Oppgave (task)
                  → Destinasjon (FB-side / Instagram / nettside)
Oppgave ↔ Destinasjon (mange-til-mange: hvor publiseres innlegget)
```
Repeterende eventer (`recurrence = yearly`) beregnes inn i hver sesong i app-laget — de
dupliseres ikke i databasen. «Dupliser til neste år» lager derimot en konkret kopi.

## Multi-tenancy
Hver tenant-tabell har `company_id`. `App\Models\Concerns\BelongsToCompany` legger på en
global scope som filtrerer all data til innlogget selskap, og setter `company_id` automatisk
ved opprettelse. `App\Http\Middleware\SetCurrentCompany` bestemmer aktivt selskap (plattform-
admin og byrå-brukere kan bytte via `session('active_company_id')`).

## Prosjektstruktur (lagt til i Sprint 1)
- `database/migrations/2026_06_29_1000*` — alle tabeller
- `app/Models/*` — Company, Category, Destination, Event, Task, PostType, Comment, Asset, OrgTemplate, Plan, User
- `app/Models/Concerns/BelongsToCompany.php` — tenant-scope
- `app/Http/Middleware/SetCurrentCompany.php` — aktivt selskap
- `database/seeders/FlikSeeder.php` — import av FLIKs årshjul

## Deploy (Cloudways)
1. Opprett ny app på samme server som Vivu, egen MySQL-database `vivu_planner`.
2. Koble Cloudways Git-deploy til dette repoet, deploy `main`.
3. Deploy-hook: `composer install --no-dev`, `php artisan migrate --force`, `php artisan config:cache`.

## Grener
`main` = produksjon · `develop` = staging · feature-grener via Pull Requests (CI kjører i `.github/workflows/ci.yml`).

## Veikart
Se `../FLIK-Aarshjul-databaseskjema-og-teknisk-spek.md` og strategidokumentet for sprint-planen (V1 → V3).
