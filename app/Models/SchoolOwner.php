<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'name',
        'email',
        'phone',
        'picture_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // SchoolOwner belongs to one School
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
