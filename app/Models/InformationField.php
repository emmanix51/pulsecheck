<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformationField extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id', 'label', 'type', 'options',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
