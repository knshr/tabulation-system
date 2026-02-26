<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\ScoringMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'venue', 'event_date', 'status', 'scoring_mode', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'status' => EventStatus::class,
            'scoring_mode' => ScoringMode::class,
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contestants(): BelongsToMany
    {
        return $this->belongsToMany(Contestant::class, 'event_contestant')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function judges(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_judge', 'event_id', 'judge_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(Criteria::class)->orderBy('order');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
