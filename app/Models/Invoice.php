<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'schedule_id',
        'receipt_number',
        'invoice_date',
        'advance_amount',
        'total_amount',
        'remaining_amount',
        'branch_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
