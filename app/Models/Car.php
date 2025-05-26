<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_model_id',
        'registration_number',
    ];

     /**
     * Get the car model associated with the car.
     */
    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    /**
     * Get the schedules associated with the car.
     */
    // public function schedules()
    // {
    //     return $this->hasMany(Schedule::class, 'vehicle_id');
    // }
}
