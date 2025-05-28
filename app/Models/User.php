<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'profile_picture',
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function schoolOwner()
    {
        return $this->hasOne(\App\Models\SchoolOwner::class, 'user_id', 'id');
    }

    // public function school()
    // {
    //     return $this->hasOneThrough(
    //         \App\Models\School::class,
    //         \App\Models\SchoolOwner::class,
    //         'user_id',    // Foreign key on SchoolOwner table
    //         'id',         // Foreign key on School table (local key on SchoolOwner)
    //         'id',         // Local key on User table
    //         'school_id'   // Local key on SchoolOwner table
    //     );
    // }
}
