<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekognitionResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_original_name',
        'resource_path',
        'user_id'
    ];

    protected $dates = ['created_at', 'updated_at'];
}
