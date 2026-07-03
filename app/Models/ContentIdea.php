<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class ContentIdea extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'group', 'title', 'description', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function card(): array
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
