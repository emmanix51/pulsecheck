<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionGroup extends Model
{
    use HasFactory;
     protected $table = 'question_groups';
    public $timestamps = true; 
    protected $fillable = ['survey_id','question_section_id','format', 'number', 'label','group_question', 'category_label','question_categories','question_instruction'];


    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
     public function question_section()
    {
        return $this->belongsTo(QuestionSection::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'question_group_id');
    }
}
