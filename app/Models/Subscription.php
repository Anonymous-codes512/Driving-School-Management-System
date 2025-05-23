<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration',
        'status',
        'features',
    ];

    // Cast features JSON to array automatically
    protected $casts = [
        'features' => 'array',
    ];
}
