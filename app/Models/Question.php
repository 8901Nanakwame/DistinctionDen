<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes used by admin tools.
     */
    protected $fillable = [
        'exam_id',
        'question_text',
        'options',
        'correct_answer',
        'type',
    ];

    /**
     * Persist options as JSON in the DB but work with them as arrays in PHP.
     */
    protected $casts = [
        'options' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            CacheService::clearHomepageCache();
        });

        static::deleted(function () {
            CacheService::clearHomepageCache();
        });
    }

    /**
     * The exam this question belongs to.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
