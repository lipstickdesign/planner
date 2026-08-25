<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingTeam extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'category_id', 'name', 'birth_year', 'grade',
        'players', 'coaches', 'area_indoor', 'area_outdoor',
        'sessions_per_week', 'requires_indoor', 'allowed_facilities', 'no_collide', 'external_ref',
        'coach_unavailable', 'notes', 'avoid_days', 'latest_end',
    ];

    public function wishes(): HasMany
    {
        return $this->hasMany(TrainingWish::class)->orderBy('priority');
    }

    protected $casts = [
        'requires_indoor' => 'boolean',
        'allowed_facilities' => 'array',
        'no_collide' => 'array',
        'avoid_days' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
