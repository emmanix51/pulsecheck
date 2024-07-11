<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'image', 'title', 'is_public',
        'is_restricted', 'slug', 'status', 'description', 'respondent_group_id', 'expire_date'
    ];
}
