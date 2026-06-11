<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = [
        'category',
        'question',
        'options',
        'correct_answer',
        'order',
    ];

    protected $table = 'exam_questions';

    protected $casts = [
        'options' => 'array',
    ];
}