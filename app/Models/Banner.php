<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'banner_image',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
