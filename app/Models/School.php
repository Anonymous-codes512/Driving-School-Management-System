<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
   use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'phone',
        'status',
        'info',
        'logo_path',
    ];

    public function schoolOwner()
    {
        return $this->hasOne(SchoolOwner::class);
    }
}
