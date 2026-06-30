<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'event_id', 'task_id', 'source',
        'drive_file_id', 'path', 'mime', 'alt_text',
    ];
}
