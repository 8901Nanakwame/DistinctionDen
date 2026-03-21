<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes used by admin/seeders/factories.
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'duration',
        'file_path',
        'category_id',
        'is_active',
    ];

    /**
     * Attribute casting for consistent types in PHP.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'duration' => 'integer',
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
     * Category the exam belongs to (used for browsing/filtering).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Questions that make up this exam.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Attempts users have made for this exam.
     */
    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
