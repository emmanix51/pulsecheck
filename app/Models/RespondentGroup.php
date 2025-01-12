<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondentGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'category','college_ids','program_ids'
    ];

    public function surveys()
    {
        return $this->belongsToMany(Survey::class, 'survey_respondent_group');
    }
}
