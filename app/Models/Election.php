<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    protected $fillable = [
        'name',
        'election_type',
        'district',
        'panchayat_samiti',
        'gram_panchayat',
        'assembly_constituency',
        'year',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }

    public function pollingStations(): HasMany
    {
        return $this->hasMany(PollingStation::class);
    }
}
