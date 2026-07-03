<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ContentIdea;
use Illuminate\Database\Seeder;

class ContentIdeaSeeder extends Seeder
{
    public function run(): void
    {
        $flik = Company::where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }
        app()->instance('currentCompany', $flik);

        $ideas = [
            // gruppe, tittel, stikkord/brief
            ['verving', 'Vi trenger dommere til fotball', 'Oppfordre til å ta dommerkurs / stille som dommer. Lav terskel, kurs dekkes av klubben.'],
            ['verving', 'Frivillige til arrangement', 'Verv frivillige til neste arrangement – kiosk, rigging, parkering. Mange hender gjør lett arbeid.'],
            ['verving', 'Bli trener eller lagleder', 'Vi trenger flere trenere/lagledere. Ingen erfaring nødvendig – klubben stiller med kurs og støtte.'],
            ['verving', 'Kioskvakter søkes', 'Sett opp kioskvakter til hjemmekamper og arrangement.'],
            ['engasjement', 'Ukens bilde', 'Del et fint bilde fra trening eller kamp sist uke.'],
            ['engasjement', 'Medlemsportrett', 'Kort portrett av en utøver, trener eller frivillig – hvem, hvorfor de er med.'],
            ['engasjement', 'Takk til frivillige', 'Takk de som stilte opp på siste arrangement.'],
            ['engasjement', 'Milepæl eller jubileum', 'Marker en milepæl, rekord, opprykk eller jubileum.'],
            ['praktisk', 'Treningstider denne uka', 'Oversikt over hvilke grupper som trener når og hvor.'],
            ['praktisk', 'Væravhengig påminnelse', 'Minn om påkledning/utstyr ut fra været – regntøy, drikke, solkrem.'],
            ['praktisk', 'Baneendring eller oppmøte', 'Info om endret bane, tid eller oppmøtested.'],
            ['motivasjon', 'Idrettsglede – alle med', 'Inkluderingspost: alle er velkomne uansett nivå og bakgrunn.'],
            ['sesong', 'Skolestart – bli med på idrett', 'Rekrutteringspost ved skolestart – prøv en idrett hos FLIK.'],
            ['sesong', 'Sommeraktiviteter', 'Hva skjer i klubben i sommer.'],
            ['medlem', 'Medlemskontingent', 'Vennlig påminnelse om kontingent og hvorfor medlemskap betyr noe.'],
            ['medlem', 'Grasrotandelen', 'Oppfordre til å velge FLIK som grasrotmottaker hos Norsk Tipping.'],
            ['medlem', 'Kalendersalg og dugnad', 'Info om dugnad/kalendersalg og hva pengene går til.'],
        ];

        $order = 0;
        foreach ($ideas as [$group, $title, $desc]) {
            ContentIdea::firstOrCreate(
                ['company_id' => $flik->id, 'title' => $title],
                ['group' => $group, 'description' => $desc, 'is_active' => true, 'sort_order' => $order++]
            );
        }
    }
}
