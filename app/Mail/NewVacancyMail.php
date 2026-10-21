<?php

namespace App\Mail;

use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewVacancyMail extends Mailable
{
    use Queueable, SerializesModels;

    public Vacancy $vacancy;

    public function __construct(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новая вакансия: ' . $this->vacancy->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-vacancy',
        );
    }
}