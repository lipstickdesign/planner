<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kamp extends Model
{
    use BelongsToCompany;

    protected $table = 'kamper';

    protected $fillable = [
        'company_id', 'category_id', 'title', 'match_date', 'match_time',
        'location', 'home', 'note',
    ];

    protected $casts = [
        'match_date' => 'date',
        'home' => 'boolean',
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
            'title' => $this->title,
            'date' => optional($this->match_date)->format('Y-m-d'),
            'time' => $this->match_time ? substr((string) $this->match_time, 0, 5) : null,
            'location' => $this->location,
            'home' => (bool) $this->home,
            'note' => $this->note,
        ];
    }
}
