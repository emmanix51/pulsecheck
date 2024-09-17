<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSection extends Model
{
    use HasFactory;

    protected $fillable = ['survey_id', 'section_number', 'section_label','section_instruction'];


    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function question_groups()
    {
        return $this->hasMany(QuestionGroup::class, 'question_section_id');
    }
}
