<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;


class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function verificationEmail($user)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verify',
            now()->addMinutes(60),
            ['id' => $user['id'], 'hash' => sha1($user['email'])]
        );

        $mailContent = 'Click the button below to verify your email address: ' . $verificationUrl;

        return $this->subject('Verify Email Address')
                    ->markdown('verification', ['mailContent' => $mailContent]);
    }
}
