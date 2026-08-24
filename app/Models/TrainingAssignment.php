<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAssignment extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'training_season_id', 'training_team_id', 'training_facility_id',
        'zone', 'weekday', 'block_start', 'block_end', 'actual_start', 'actual_end',
        'reason', 'manual_override', 'version', 'changed_by',
    ];

    protected $casts = ['manual_override' => 'boolean'];

    public function season(): BelongsTo
    {
        return $this->belongsTo(TrainingSeason::class, 'training_season_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(TrainingTeam::class, 'training_team_id');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(TrainingFacility::class, 'training_facility_id');
    }
}
