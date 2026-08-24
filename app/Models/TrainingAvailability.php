<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAvailability extends Model
{
    use BelongsToCompany;

    protected $table = 'training_availability';

    protected $fillable = [
        'company_id', 'training_season_id', 'training_facility_id',
        'zone', 'weekday', 'from_time', 'to_time', 'owner', 'status',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(TrainingSeason::class, 'training_season_id');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(TrainingFacility::class, 'training_facility_id');
    }
}
