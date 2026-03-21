<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
