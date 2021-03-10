<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillGroup extends Model
{
    use HasFactory;

    public function skill()
    {
        return $this->belongsToMany('App\Models\Skill');
    }
}
