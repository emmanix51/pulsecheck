<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\SurveyNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // public function distribute()
    // {
    //     if (!$this->is_public) {
    //         $respondentGroup = $this->respondentGroups()->first();
    //         $respondents = User::where('respondent_type', $respondentGroup->type)
    //             // ->where('category', $respondentGroup->category)
    //             ->get();

    //         Notification::send($respondents, new SurveyNotification($this));
    //     }
    // }

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
        return $this->hasMany(Question::class, 'survey_id');
    }
    public function informationFields()
    {
        return $this->hasMany(InformationField::class);
    }
}
