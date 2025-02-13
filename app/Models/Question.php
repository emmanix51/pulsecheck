<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['survey_id','question_group_id', 'question_type','group','category', 'question', 'description', 'data'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
    public function question_group()
    {
        return $this->belongsTo(QuestionGroup::class);
    }
}
