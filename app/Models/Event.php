<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id', 'category_id', 'title', 'description', 'type', 'goal',
        'event_date', 'recurrence', 'approval_status', 'landing_url',
        'signup_url', 'internal_note', 'responsible_user_id', 'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public const APPROVAL_LABELS = [
        'utkast' => 'Utkast',
        'til_godkjenning' => 'Til godkjenning',
        'godkjent' => 'Godkjent',
        'internt' => 'Internt',
    ];

    public const STATUS_LABELS = [
        'planlagt' => 'Planlagt',
        'under_arbeid' => 'Under arbeid',
        'klar' => 'Klar for publisering',
        'publisert' => 'Publisert',
    ];

    /**
     * Felles dataformat brukt av både dashboard-visning og redigering (JSON til frontend).
     */
    public function toCard(): array
    {
        $this->loadMissing(['category', 'responsible', 'tasks.destinations']);

        return [
            'id' => $this->id,
            'date' => optional($this->event_date)->format('Y-m-d'),
            'sport' => $this->category->name ?? 'Administrasjon',
            'color' => $this->category->color ?? '#5a7184',
            'category_id' => $this->category_id,
            'type' => $this->type,
            'title' => $this->title,
            'mal' => $this->goal,
            'desc' => $this->description,
            'ansvarlig' => $this->responsible->name ?? null,
            'responsible_user_id' => $this->responsible_user_id,
            'recur' => $this->recurrence,
            'approval' => self::APPROVAL_LABELS[$this->approval_status] ?? 'Utkast',
            'approval_status' => $this->approval_status,
            'landing' => $this->landing_url,
            'hoopit' => $this->signup_url,
            'notat' => $this->internal_note,
            'posts' => $this->tasks->sortBy('publish_date')->values()->map(function ($t) {
                return [
                    'id' => $t->id,
                    'label' => $t->label,
                    'date' => optional($t->publish_date)->format('Y-m-d'),
                    'status' => self::STATUS_LABELS[$t->status] ?? 'Planlagt',
                    'status_raw' => $t->status,
                    'pages' => $t->destinations->pluck('name')->all(),
                    'destination_ids' => $t->destinations->pluck('id')->all(),
                    'text' => $t->draft_url,
                    'body' => $t->body_draft,
                ];
            })->all(),
        ];
    }
}
