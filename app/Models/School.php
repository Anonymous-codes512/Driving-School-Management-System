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
        'owner_id',
        'logo_path',
    ];

public function schoolOwner()
{
    return $this->belongsTo(SchoolOwner::class, 'owner_id');
}


    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
