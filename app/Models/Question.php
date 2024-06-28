<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_type', 'question', 'description', 'data', 'survey_id'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
