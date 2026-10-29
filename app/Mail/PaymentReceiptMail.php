<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Payment $payment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Payment Receipt');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-receipt',
            with: [
                'totalDue' => $this->totalDue(),
                'totalPaid' => $this->totalPaid(),
                'balance' => $this->balance(),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function totalDue(): float
    {
        $reservation = $this->payment->reservation;

        return $reservation->totalDueAmount();
    }

    private function totalPaid(): float
    {
        return (float) $this->payment->reservation->payments
            ->where('status', 'Confirmed')
            ->sum('amount');
    }

    private function balance(): float
    {
        return max(0, $this->totalDue() - $this->totalPaid());
    }
}
