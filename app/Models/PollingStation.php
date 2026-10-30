<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollingStation extends Model
{
    protected $fillable = [
        'election_id',
        'ward_id',
        'booth_number',
        'polling_station_name',
        'building_name',
        'room_number',
        'address',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class);
    }
}
