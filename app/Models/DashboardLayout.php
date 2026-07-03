<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class DashboardLayout extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'user_id', 'layout'];

    protected $casts = [
        'layout' => 'array',
    ];
}
