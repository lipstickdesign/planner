<?php

namespace App\Http\Controllers;

class TrainingController extends Controller
{
    /**
     * Treningstider – fordelingsverktøy. Foreløpig kontroll-/regelvisningen
     * (bit 2a), matet med FLIKs 2026/2027-data innebygd i visningen. Bit 2b
     * bytter datakilden til databasen (import av IR-tildeling + lagdata).
     */
    public function index()
    {
        // Skjult for alle andre enn superadmin mens modulen bygges.
        abort_unless((bool) auth()->user()?->is_platform_admin, 403);

        return view('training.index');
    }
}
