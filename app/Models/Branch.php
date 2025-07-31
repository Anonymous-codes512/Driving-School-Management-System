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
}
