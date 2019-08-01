<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $casts = [
        'daily_tasks_lisk' => 'array',
    ];
}
