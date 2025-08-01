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
        'status',
    ];

    /**
     * Get the car model associated with the car.
     */
    // In Car.php model
    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }


    /**
     * Get the schedules associated with the car.
     */
    // public function schedules()
    // {
    //     return $this->hasMany(Schedule::class, 'vehicle_id');
    // }
}
