<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
     use HasFactory;

    protected $fillable = [
        'employee_id',
        'license_city',
        'license_start_date',
        'license_end_date',
        'license_number',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // public function attendances()
    // {
    //     return $this->hasMany(Attendance::class, 'instructor_id');
    // }

    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class);
    // }
    
}
