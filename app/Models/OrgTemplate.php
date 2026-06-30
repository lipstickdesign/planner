<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgTemplate extends Model
{
    protected $fillable = ['org_type', 'name', 'definition', 'is_active'];

    protected $casts = [
        'definition' => 'array',
        'is_active' => 'boolean',
    ];
}
