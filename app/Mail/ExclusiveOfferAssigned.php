<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExclusiveOfferAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;
    public $effectivePrice;

    public function __construct(User $user, Plan $plan, $effectivePrice = null)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->effectivePrice = $effectivePrice ?? $plan->price;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Exclusive Custom Offer Just For You!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.exclusive-offer',
        );
    }
}
