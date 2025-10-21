<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    protected $fillable = [
        'section',
        'key',
        'value',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];
}
