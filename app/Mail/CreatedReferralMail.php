<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreatedReferralMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $referral;

    public function __construct($student, $referral)
    {
        $this->student = $student;
        $this->referral = $referral;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo estudiante remitido',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.createdReferral',
            with: [
                'student'  => $this->student,
                'referral' => $this->referral,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
