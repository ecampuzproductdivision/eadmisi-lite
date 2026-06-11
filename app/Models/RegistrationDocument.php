<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationDocument extends Model
{
    protected $fillable = [
        'registration_id',
        'type',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    protected $table = 'registration_documents';

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the full URL to the file
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get human-readable file size
     */
    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}