<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contestant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'nickname', 'description', 'photo', 'contestant_number',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_contestant')
            ->withPivot('order')
            ->withTimestamps();
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
