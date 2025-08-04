<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'employee_id', 'start_date', 'end_date', 'leave_reason', 'status'];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Define relationship with Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
