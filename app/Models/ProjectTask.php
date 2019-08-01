<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    const TASK_TYPE = 'Simple Task';
    const STORY_TYPE = 'Story';

    protected $fillable = [
        'absolute_day',
        'name'
    ];
    public function story()
    {
        return $this->belongsTo('App\Models\Story');
    }
}
