<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingWish extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'training_team_id', 'priority', 'weekday', 'time', 'note',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(TrainingTeam::class, 'training_team_id');
    }
}
