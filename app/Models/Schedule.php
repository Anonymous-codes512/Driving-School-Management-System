<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    protected $fillable = [
        'student_id',
        'instructor_id',
        'vehicle_id',
        'class_date',
        'start_time',
        'end_time',
        'status',
        'classes_attended',
        'class_end_date',
        'class_duration', // Add class_duration to the fillable array

    ];
    protected $casts = [
        'class_date' => 'datetime',
        'class_end_date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Car::class, 'vehicle_id');
    }


    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
