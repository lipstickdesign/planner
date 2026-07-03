<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TaskController;
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
});

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
})->middleware('guest')->name('password.email');

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
})->middleware('guest')->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Skrive-/adminoperasjoner – kun admin/superadmin
    Route::middleware('company.admin')->group(function () {
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{event}', [EventController::class, 'update']);
        Route::delete('/events/{event}', [EventController::class, 'destroy']);

        Route::post('/events/{event}/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{task}', [TaskController::class, 'update']);
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

        Route::post('/events/{event}/generate-plan', [EventController::class, 'generatePlan']);
        Route::post('/events/{event}/duplicate-next-year', [EventController::class, 'duplicateNextYear']);
        Route::post('/events/{event}/reorder-tasks', [EventController::class, 'reorderTasks']);
        Route::post('/ai/suggest', [AiController::class, 'suggest']);

        // Brukeradministrasjon
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
        Route::put('/users/{user}/role', [UserController::class, 'updateRole']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
});
