<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SurveyDistributed extends Mailable
{
    use Queueable, SerializesModels;

    public $survey;
    public $user;
    // private $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct($data)
    // {
    //     $this->data = $data;
    // }
    public function __construct($survey, $user)
    {
        $this->survey = $survey;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function envelope(): Envelope
{
    return new Envelope(
        from: new Address(config('mail.from.address'), config('mail.from.name')),
        subject: 'New Survey',
    );
}

    public function content(): Content
    {
        return new Content(
            view: 'emails.survey-distributed',
            with: [
                "user" => $this->user,
                "survey" => $this->survey
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
    // public function build()
    // {
    //     return $this->subject('New Survey Available')
    //         ->view('emails.survey_distributed');
    // }
}
