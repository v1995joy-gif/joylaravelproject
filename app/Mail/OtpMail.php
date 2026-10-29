<?php

namespace App\Mail;

use App\Models\OtpSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $purpose,
        public int $expiresInSeconds,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->purpose === OtpSession::PURPOSE_PASSWORD_RESET
                ? 'Your Password Reset Code'
                : 'Your Email Verification Code',
        );
    }

    public function content(): Content
    {
        $isReset = $this->purpose === OtpSession::PURPOSE_PASSWORD_RESET;

        return new Content(
            view: 'emails.otp',
            with: [
                'expiresMinutes' => (int) ceil($this->expiresInSeconds / 60),
                'heading' => $isReset ? 'Password Reset' : 'Email Verification',
                'action' => $isReset ? 'reset your password' : 'verify your email address',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
