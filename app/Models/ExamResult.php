<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'registration_id',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'score',
        'duration_seconds',
        'answers',
        'status',
    ];

    protected $table = 'exam_results';

    protected $casts = [
        'answers' => 'array',
        'score' => 'float',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get formatted duration
     */
    public function getDurationFormattedAttribute(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return $minutes . ' menit ' . $seconds . ' detik';
    }
}