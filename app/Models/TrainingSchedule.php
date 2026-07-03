<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSchedule extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'category_id', 'weekday', 'group_label',
        'start_time', 'end_time', 'location', 'note',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function card(): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => $this->category?->name,
            'color' => $this->category?->color,
            'weekday' => (int) $this->weekday,
            'group' => $this->group_label,
            'start' => $this->start_time ? substr((string) $this->start_time, 0, 5) : null,
            'end' => $this->end_time ? substr((string) $this->end_time, 0, 5) : null,
            'location' => $this->location,
            'note' => $this->note,
        ];
    }
}
