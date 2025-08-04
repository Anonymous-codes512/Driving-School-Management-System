<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'phone',
        'id_card_number',
        'address',
        'salary',
        'employee_status',
        'gender',
        'designation',
        'picture',
        'branch_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function otherExpenses()
    {
        return $this->hasMany(OtherExpense::class);
    }
    public function leaves(){
        return $this->hasMany(Leave::class, 'employee_id');
    }
}
