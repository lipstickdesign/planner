<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class KampTemplate extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'sport', 'mode', 'name', 'data', 'created_by',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function card(): array
    {
        return [
            'id' => $this->id,
            'sport' => $this->sport,
            'mode' => $this->mode,
            'name' => $this->name,
            'data' => $this->data ?: [],
        ];
    }
}
