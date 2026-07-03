<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Notifications\ResetPasswordNo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Bruk den norske, Vivu-profilerte reset-e-posten.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNo($token));
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_platform_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_admin' => 'boolean',
        ];
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)
            ->withPivot(['title', 'phone', 'area', 'is_agency', 'status', 'role'])
            ->withTimestamps();
    }

    /**
     * Er brukeren admin (kan redigere) i det aktive selskapet?
     * Plattform-superadmin har alltid full tilgang.
     */
    public function isCompanyAdmin(): bool
    {
        if ($this->is_platform_admin) {
            return true;
        }
        $company = app()->bound('currentCompany') ? app('currentCompany') : null;
        if (! $company) {
            return false;
        }
        $membership = $this->companies()->where('companies.id', $company->id)->first();

        return $membership && ($membership->pivot->role ?? 'medlem') === 'admin';
    }
}
