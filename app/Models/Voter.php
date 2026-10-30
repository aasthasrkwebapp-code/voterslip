<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Voter extends Model
{
    protected $fillable = [
        'election_id',
        'ward_id',
        'polling_station_id',
        'serial_number',
        'epic_number',
        'name',
        'relative_name',
        'relation_type',
        'house_number',
        'age',
        'gender',
        'part_number',
        'is_verified',
        'locality',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'is_verified' => 'boolean',
        ];
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function pollingStation(): BelongsTo
    {
        return $this->belongsTo(PollingStation::class);
    }

    public function photo(): HasOne
    {
        return $this->hasOne(VoterPhoto::class);
    }
}
