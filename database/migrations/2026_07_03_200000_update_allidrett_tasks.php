<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $flik = DB::table('companies')->where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }

        $event = DB::table('events')->where('company_id', $flik->id)
            ->where('title', 'FLIK Allidrett 2026/2027')->first();
        if (! $event) {
            return;
        }

        // Bytt ut taskene med den nye rekrutteringssekvensen (før sommer → august → oppstartsuke),
        // pluss idrett-påminnelser og avslutning.
        DB::table('tasks')->where('event_id', $event->id)->delete();

        $foersommer = "Planlegger dere høsten? Sett av onsdagene! 🌞\n\n"
            ."Til høsten braker det løs igjen med Allidrett for barn født i 2019 og 2020 (1.–2. klasse). "
            ."Gjennom året får barna prøve seg i friidrett, svømming, turn, sykling, volleyball, sandvolleyball, "
            ."håndball og fotball – minst to ganger i hver idrett.\n\n"
            ."Onsdager kl. 17–18. Gratis for FLIK-medlemmer (ingen treningsavgift). Oppstart onsdag 19. august på Vanse stadion.\n\n"
            ."Meld på allerede nå 👉 [sett inn Hoopit-lenke], så er dere klare når høsten starter. God sommer!";

        $august = "Nå starter vi opp igjen med Allidrett for de yngste! 🎉\n\n"
            ."Er barnet ditt født i 2019 eller 2020 (1.–2. klasse)? Da har vi et tilbud dere ikke vil gå glipp av. "
            ."Gjennom året får barna prøve de fleste idrettene vi har i kommunen – friidrett, svømming, turn, sykling, "
            ."volleyball, sandvolleyball, håndball og fotball. Hver idrett minst to ganger, så alle finner noe de liker.\n\n"
            ."Praktisk:\n"
            ."• Onsdager kl. 17–18 (stedet varierer med aktiviteten)\n"
            ."• Gratis for FLIK-medlemmer – ingen treningsavgift (medlemskap 500 kr kreves av forsikringshensyn)\n"
            ."• Trygt og sosialt, med voksne til stede\n\n"
            ."Oppstart / infomøte: onsdag 19. august kl. 17.00 ved klubbhuset på Vanse stadion. Barna har aktiviteter ute "
            ."på stadion, mens foreldrene samles inne på klubbhuset.\n\n"
            ."Meld på allerede nå 👉 [sett inn Hoopit-lenke] – eller bare møt opp på oppstarten. Alle deltakere legges til "
            ."i en egen Hoopit-gruppe med info til hver samling.\n\n"
            ."Spørsmål? Ta kontakt med Ida Marcussen: sportsligleder@flik.no / 482 37 800.\n\n"
            ."Velkommen til et nytt, gøy år med Allidrett!";

        $oppstartsuke = "Nå er det snart klart – oppstart for Allidrett! 🏃\n\n"
            ."Denne onsdagen, 19. august kl. 17.00, møtes vi ved klubbhuset på Vanse stadion. Barn født i 2019 og 2020 "
            ."(1.–2. klasse) er hjertelig velkomne. Barna har aktiviteter ute, mens foreldrene får info inne på klubbhuset.\n\n"
            ."Ikke rukket å melde på? Det er ikke for sent – bare møt opp, eller meld på her 👉 [sett inn Hoopit-lenke]. "
            ."Gratis for FLIK-medlemmer.\n\n"
            ."Vi gleder oss til et nytt år med Allidrett – vi sees på onsdag!";

        $avslutning = "Takk for et fantastisk år med Allidrett! 👏\n\n"
            ."I år har barna født i 2019 og 2020 fått prøve seg i alt fra friidrett og svømming til turn, sykling, "
            ."volleyball, håndball og fotball. For en gjeng!\n\n"
            ."Tusen takk til alle barn, foreldre og frivillige som har stilt opp onsdag etter onsdag. "
            ."Vi sees til høsten – følg med for oppstart i august!";

        $tasks = [
            // [label, date, platform, format, body]
            ['Allidrett til høsten – meld på før sommeren', '2026-06-10', 'facebook', 'post', $foersommer],
            ['Snart oppstart – Allidrett 19. august (meld på)', '2026-08-05', 'facebook', 'post', $august],
            ['Oppstartsuka – Allidrett onsdag 19. august', '2026-08-17', 'facebook', 'post', $oppstartsuke],
            ['Denne uka: friidrett på allidrett', '2026-08-19', 'instagram', 'story', null],
            ['Denne uka: sandvolleyball på allidrett', '2026-09-16', 'instagram', 'story', null],
            ['Denne uka: sykkel på allidrett', '2026-10-07', 'instagram', 'story', null],
            ['Denne uka: turn på allidrett', '2026-10-21', 'instagram', 'story', null],
            ['Denne uka: fotball på allidrett', '2026-11-04', 'instagram', 'story', null],
            ['Denne uka: svømming på allidrett', '2026-11-18', 'instagram', 'story', null],
            ['Denne uka: håndball på allidrett', '2026-12-02', 'instagram', 'story', null],
            ['Denne uka: volleyball på allidrett', '2027-01-06', 'instagram', 'story', null],
            ['Avslutning allidrett – takk for året!', '2027-05-19', 'facebook', 'post', $avslutning],
        ];

        $sort = 0;
        foreach ($tasks as [$label, $date, $platform, $format, $body]) {
            DB::table('tasks')->insert([
                'company_id' => $flik->id,
                'event_id' => $event->id,
                'label' => $label,
                'body_draft' => $body,
                'publish_date' => $date,
                'status' => 'planlagt',
                'platform' => $platform,
                'format' => $format,
                'sort_order' => $sort++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Ingen tilbakerulling – dette er innholdsdata.
    }
};
