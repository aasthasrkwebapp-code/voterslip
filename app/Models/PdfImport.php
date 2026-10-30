<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdfImport extends Model
{
    protected $fillable = [
        'election_id',
        'ward_id',
        'original_filename',
        'file_path',
        'file_hash',
        'total_pages',
        'total_records',
        'imported_records',
        'failed_records',
        'status',
        'error_message',
        'imported_at',
    ];

    protected function casts(): array
    {
        return [
            'total_pages' => 'integer',
            'total_records' => 'integer',
            'imported_records' => 'integer',
            'failed_records' => 'integer',
            'imported_at' => 'datetime',
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
}
