<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingPlanVersion extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'training_season_id', 'name', 'snapshot', 'is_auto', 'created_by',
    ];

    protected $casts = ['snapshot' => 'array', 'is_auto' => 'boolean'];

    public function season(): BelongsTo
    {
        return $this->belongsTo(TrainingSeason::class, 'training_season_id');
    }
}
