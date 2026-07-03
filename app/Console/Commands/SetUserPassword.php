<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Sett nytt passord for en bruker med skjult inntasting, og sikre superadmin-rettigheter.
 * Bruk: php artisan user:password roger@havdurdesign.no
 */
class SetUserPassword extends Command
{
    protected $signature = 'user:password {email} {--admin : Sett brukeren som superadmin}';

    protected $description = 'Sett nytt passord for en bruker (skjult inntasting)';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Fant ingen bruker med e-post: {$email}");

            return self::FAILURE;
        }

        $password = $this->secret('Nytt passord');
        $confirm = $this->secret('Bekreft passord');

        if ($password !== $confirm) {
            $this->error('Passordene er ikke like.');

            return self::FAILURE;
        }
        if (strlen((string) $password) < 8) {
            $this->error('Passordet må være minst 8 tegn.');

            return self::FAILURE;
        }

        $user->password = Hash::make($password);
        if ($this->option('admin')) {
            $user->is_platform_admin = true;
        }
        $user->save();

        $this->info("Passord oppdatert for {$user->name} ({$email}).".
            ($user->is_platform_admin ? ' Superadmin: ja.' : ''));

        return self::SUCCESS;
    }
}
