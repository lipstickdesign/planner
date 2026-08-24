<?php

use App\Http\Controllers\AiController;
use App\Models\Company;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContentIdeaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardLayoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KampController;
use App\Http\Controllers\KlubblivPostController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PublicKampController;
use App\Http\Controllers\PublicWheelController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'dashboard' : 'login');
});

// Offentlig, innebygdbart årshjul (ingen innlogging)
Route::get('/embed/{slug}/arshjul', [PublicWheelController::class, 'wheel'])->name('embed.wheel');

// Offentlig, innebygdbar kampoversikt (i dag + 7 dager) for nettside
Route::get('/embed/{slug}/kamper.js', [PublicKampController::class, 'feedJs'])->name('embed.kamper.js');
Route::get('/embed/{slug}/kamper', [PublicKampController::class, 'feed'])->name('embed.kamper');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    return back()
        ->withErrors(['email' => 'Feil e-post eller passord.'])
        ->onlyInput('email');
})->middleware('throttle:10,1');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

/* ---- Selvbetjent «glemt passord» ---- */
Route::get('/forgot-password', fn () => view('auth.forgot-password'))
    ->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => ['required', 'email']]);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'Hvis e-posten finnes hos oss, har vi sendt en lenke for å nullstille passordet.')
        : back()->with('status', 'Hvis e-posten finnes hos oss, har vi sendt en lenke for å nullstille passordet.');
})->middleware(['guest', 'throttle:6,1'])->name('password.email');

Route::get('/reset-password/{token}', fn (string $token) => view('auth.reset-password', ['token' => $token]))
    ->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', 'Passordet er endret. Du kan nå logge inn.')
        : back()->withErrors(['email' => 'Lenken er ugyldig eller utløpt. Be om en ny.']);
})->middleware(['guest', 'throttle:6,1'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Treningstider – fordelingsverktøy (egen side, foreløpig kontroll-visningen)
    Route::get('/treningstider', [TrainingController::class, 'index'])->name('training');
    Route::get('/treningstider/lag', [TrainingController::class, 'teams'])->name('training.lag');
    Route::post('/treningstider/lag', [TrainingController::class, 'storeTeam']);
    Route::put('/treningstider/lag/{team}', [TrainingController::class, 'updateTeam']);
    Route::delete('/treningstider/lag/{team}', [TrainingController::class, 'destroyTeam']);
    Route::get('/treningstider/anlegg', [TrainingController::class, 'facilities'])->name('training.anlegg');
    Route::post('/treningstider/anlegg', [TrainingController::class, 'storeFacility']);
    Route::put('/treningstider/anlegg/{facility}', [TrainingController::class, 'updateFacility']);
    Route::delete('/treningstider/anlegg/{facility}', [TrainingController::class, 'destroyFacility']);

    // Personlig dashboard-oppsett (alle innloggede kan styre sitt eget)
    Route::post('/dashboard-layout', [DashboardLayoutController::class, 'save']);
    Route::delete('/dashboard-layout', [DashboardLayoutController::class, 'reset']);

    // Egen profil (alle innloggede kan endre sin egen bruker)
    Route::put('/me', [UserController::class, 'updateSelf']);

    // Superadmin: kundeadministrasjon (oppretter selskap + første admin). Vokter i controller.
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);

    // Bytte aktivt selskap (superadmin: alle; ellers kun egne)
    Route::post('/switch-company/{company}', function (Request $request, Company $company) {
        $u = $request->user();
        if ($u->is_platform_admin || $u->companies()->where('companies.id', $company->id)->exists()) {
            $request->session()->put('active_company_id', $company->id);
        }

        return redirect()->route('dashboard');
    });

    // Skrive-/adminoperasjoner – kun admin/superadmin
    Route::middleware('company.admin')->group(function () {
        Route::post('/dashboard-layout/default', [DashboardLayoutController::class, 'saveDefault']);

        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{event}', [EventController::class, 'update']);
        Route::delete('/events/{event}', [EventController::class, 'destroy']);

        Route::post('/events/{event}/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{task}', [TaskController::class, 'update']);
        Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

        Route::post('/events/{event}/generate-plan', [EventController::class, 'generatePlan']);
        Route::post('/events/{event}/review-plan', [AiController::class, 'reviewPlan']);
        Route::post('/events/{event}/apply-plan', [EventController::class, 'applyPlan']);
        Route::post('/events/{event}/duplicate-next-year', [EventController::class, 'duplicateNextYear']);
        Route::post('/events/{event}/reorder-tasks', [EventController::class, 'reorderTasks']);
        Route::post('/ai/suggest', [AiController::class, 'suggest']);

        // Brukeradministrasjon
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
        Route::put('/users/{user}/role', [UserController::class, 'updateRole']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        // Onboarding – AI tolker tekst/regneark til arrangement
        Route::post('/onboarding/parse', [OnboardingController::class, 'parse']);
        Route::post('/onboarding/import', [OnboardingController::class, 'import']);

        // Kategorier (idretter / avdelinger) – redigerbare per selskap
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::put('/categories/{category}/archive', [CategoryController::class, 'archive']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // Selskaps-/abonnementsinnstillinger (branding)
        Route::put('/company/{company}', [CompanyController::class, 'update']);
        Route::put('/company/{company}/goals', [CompanyController::class, 'updateGoals']);
        Route::post('/company/{company}/logo', [CompanyController::class, 'uploadLogo']);
        Route::delete('/company/{company}/logo', [CompanyController::class, 'removeLogo']);

        // Klubbliv-bibliotek (innholdsidéer)
        Route::post('/content-ideas', [ContentIdeaController::class, 'store']);
        Route::put('/content-ideas/{contentIdea}', [ContentIdeaController::class, 'update']);
        Route::delete('/content-ideas/{contentIdea}', [ContentIdeaController::class, 'destroy']);

        // Klubbliv-poster (planlagt innhold utenom arrangement)
        Route::post('/klubbliv', [KlubblivPostController::class, 'store']);
        Route::put('/klubbliv/{klubblivPost}', [KlubblivPostController::class, 'update']);
        Route::put('/klubbliv/{klubblivPost}/status', [KlubblivPostController::class, 'updateStatus']);
        Route::delete('/klubbliv/{klubblivPost}', [KlubblivPostController::class, 'destroy']);

        // Treningstider
        Route::post('/training-schedules', [TrainingScheduleController::class, 'store']);
        Route::put('/training-schedules/{trainingSchedule}', [TrainingScheduleController::class, 'update']);
        Route::delete('/training-schedules/{trainingSchedule}', [TrainingScheduleController::class, 'destroy']);

        // Kamper
        Route::post('/kamper/import', [KampController::class, 'import']);
        Route::post('/kamper', [KampController::class, 'store']);
        Route::put('/kamper/{kamp}', [KampController::class, 'update']);
        Route::delete('/kamper/{kamp}', [KampController::class, 'destroy']);
    });
});
