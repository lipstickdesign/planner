<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id', 'event_id', 'post_type_id', 'label', 'body_draft',
        'draft_url', 'publish_date', 'scheduled_time', 'status',
        'approval_status', 'responsible_user_id', 'sort_order',
    ];

    protected $casts = [
        'publish_date' => 'date',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function postType(): BelongsTo
    {
        return $this->belongsTo(PostType::class);
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class)
            ->withPivot(['publish_status', 'external_post_id', 'published_at']);
    }
}
