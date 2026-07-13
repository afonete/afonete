<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationPinEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $pin;
    public $name;

    public function __construct($name, $pin)
    {
        $this->name = $name;
        $this->pin = $pin;
    }

    public function build()
    {
        return $this->subject('Verify your Email - Activation PIN')
                    ->view('emails.verification_pin');
    }
}
