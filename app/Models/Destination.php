<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'platform', 'name', 'external_account_id',
        'access_token', 'token_expires_at', 'category_id', 'status',
    ];

    protected $hidden = ['access_token'];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'access_token' => 'encrypted',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)
            ->withPivot(['publish_status', 'external_post_id', 'published_at']);
    }
}
