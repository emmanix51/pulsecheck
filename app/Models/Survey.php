<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Survey extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'user_id', 'image', 'title', 'is_public',
        'is_restricted', 'slug', 'status', 'description', 'respondent_group_id', 'expire_date'
    ];


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function respondentGroups()
    {
        return $this->belongsToMany(RespondentGroup::class, 'survey_respondent_group');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function informationFields()
    {
        return $this->hasMany(InformationField::class);
    }
}
