<?php

namespace App\Notifications;


use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class SurveyNotification extends Notification
{
    use Queueable;

    protected $survey;

    public function __construct($survey)
    {
        $this->survey = $survey;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('A new survey is available for you to participate in.')
            ->action('Take Survey', url("/survey/{$this->survey->slug}"))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'survey_id' => $this->survey->id,
            'survey_title' => $this->survey->title,
        ];
    }
    public function toArray($notifiable)
    {
        return [
            'survey_id' => $this->survey->id,
            'title' => $this->survey->title,
            'slug' => $this->survey->slug,
        ];
    }
}
