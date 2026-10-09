<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AccountCreatedMail extends Mailable
{
    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to Horizon Lab - your account has been created');
    }

    public function content(): Content
    {
        $isEngineer = $this->user->role === 'engineer';

        return new Content(
            view: 'emails.account-created',
            with: [
                'accountType' => $isEngineer ? 'engineer' : 'health facility',
                'loginUrl' => route($isEngineer ? 'engineer.login' : 'facility.login'),
            ],
        );
    }
}
