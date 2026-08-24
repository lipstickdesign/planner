<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingTeam extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'category_id', 'name', 'birth_year', 'grade',
        'players', 'coaches', 'area_indoor', 'area_outdoor',
        'sessions_per_week', 'requires_indoor', 'allowed_facilities', 'external_ref',
    ];

    protected $casts = [
        'requires_indoor' => 'boolean',
        'allowed_facilities' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
