<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Orders placed by the user (used by other tutorial areas of the project).
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Cart items belonging to the user.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * All exam attempts made by the user.
     */
    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /**
     * Exams the user has attempted (via the pivot-like attempts table).
     */
    public function exams()
    {
        return $this->hasManyThrough(Exam::class, ExamAttempt::class);
    }

    /**
     * Check if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            \App\Services\CacheService::clearHomepageCache();
        });
    }
}
