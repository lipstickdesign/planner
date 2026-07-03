<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KlubblivPost extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'content_idea_id', 'title', 'body_draft',
        'publish_date', 'destination_ids', 'status', 'sort_order',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'destination_ids' => 'array',
    ];

    public function idea(): BelongsTo
    {
        return $this->belongsTo(ContentIdea::class, 'content_idea_id');
    }

    public function card(?array $destMap = null): array
    {
        $ids = $this->destination_ids ?: [];
        $channels = [];
        if ($destMap) {
            foreach ($ids as $i) {
                if (isset($destMap[$i])) {
                    $channels[] = $destMap[$i];
                }
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body_draft,
            'date' => optional($this->publish_date)->format('Y-m-d'),
            'status' => $this->status,
            'destination_ids' => array_map('intval', $ids),
            'channels' => $channels,
            'idea_group' => optional($this->idea)->group,
        ];
    }
}
