<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostType extends Model
{
    protected $fillable = [
        'company_id', 'key', 'name', 'purpose',
        'default_offset_days', 'template_body', 'sort_order',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
