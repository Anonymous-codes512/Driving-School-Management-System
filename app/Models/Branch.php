<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'branch_phone_number',
        'branch_email_address',
        'website',
        'opening_hours',
        'closing_hours',
        'slots_length',
        'branch_code',
        'location',
        'status',
        'owner_id',
        'school_id',
    ];

    /**
     * Branch belongs to a SchoolOwner (owner).
     */
    public function owner()
    {
        return $this->belongsTo(SchoolOwner::class, 'owner_id');
    }

    /**
     * Branch belongs to a School.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Branch have many banners.
     */
    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
