<?php

use Illuminate\Database\Migrations\Migration;

/**
 * NO-OP. Denne skulle opprinnelig bytte ut Allidrett-eventets tasks med en
 * ferdig rekrutteringssekvens + tekster. Vi valgte i stedet å bygge planen
 * i appen («Foreslå plan» + AI-tekst), så denne migrasjonen gjør nå ingenting
 * for ikke å overskrive tasks brukeren har lagt inn manuelt.
 * Beholdt (tom) fordi filen kan være registrert i migrations-tabellen allerede.
 */
return new class extends Migration
{
    public function up(): void
    {
        // med hensikt tom
    }

    public function down(): void
    {
        // med hensikt tom
    }
};
