<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attempt data.
     *
     * `answers` is stored as JSON and holds per-question user answers.
     */
    protected $fillable = [
        'user_id',
        'exam_id',
        'score',
        'answers',
        'completed_at',
    ];

    /**
     * Casts for consistent typing.
     */
    protected $casts = [
        'answers' => 'array',
        'completed_at' => 'datetime',
        'score' => 'integer',
    ];

    /**
     * User who took the exam.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Exam that was attempted.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
