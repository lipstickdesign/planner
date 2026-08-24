<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingSeason extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'name', 'type', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function availability(): HasMany
    {
        return $this->hasMany(TrainingAvailability::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TrainingAssignment::class);
    }
}
