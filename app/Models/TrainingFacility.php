<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class TrainingFacility extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'name', 'type', 'zones', 'allowed_sports', 'status', 'opening_hours',
    ];

    protected $casts = [
        'allowed_sports' => 'array',
        'opening_hours' => 'array',
    ];
}
