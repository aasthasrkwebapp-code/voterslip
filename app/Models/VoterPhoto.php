<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoterPhoto extends Model
{
    protected $fillable = [
        'voter_id',
        'photo_path',
        'original_filename',
        'mime_type',
        'file_size',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }
}
