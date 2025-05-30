<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'father_or_husband_name',
        'cnic',
        'address',
        'phone',
        'optional_phone',
        'admission_date',
        'course_end_date',
        'email',
        'coupon_code',
        'course_id',
        'instructor_id',
        'course_duration',
        'pickup_sector',
        'timing_preference',
        'status',
    ];

    // Relationships

    // Student belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Student belongs to Instructor
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    // Student belongs to Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Student belongs to Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Student has many Attendances (foreign key student_id)
    // public function attendances()
    // {
    //     return $this->hasMany(Attendance::class, 'student_id');
    // }

    // Student has many Schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'student_id'); // Added foreign key explicitly for clarity
    }

    // Student has one Invoice through Schedule
    public function invoice()
    {
        return $this->hasOneThrough(
            Invoice::class,
            Schedule::class,
            'student_id', // Foreign key on schedules table
            'schedule_id', // Foreign key on invoices table
            'id', // Local key on students table
            'id'  // Local key on schedules table
        );
    }
}

