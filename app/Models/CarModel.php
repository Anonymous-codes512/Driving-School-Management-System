<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'transmission',
        'description', // Course description
    ];

    /**
     * Get the cars associated with the car model.
     */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }


    /**
     * Get the courses associated with the car model.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
