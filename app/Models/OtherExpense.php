<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherExpense extends Model
{
    protected $fillable = [
        'employee_id',
        'expense_type',
        'amount',
        'expense_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
