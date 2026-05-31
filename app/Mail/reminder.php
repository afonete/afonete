<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class reminder extends Mailable
{
    use Queueable, SerializesModels;
public $email;public $username;
    /**
     * Create a new message instance.
     * @param string $email
     * @param string $username
     * @return void
     */
    public function __construct($email,$username)
    {
        $this->email=$email;
        $this->username=$username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('FONEPO - QUICK ACCOUNT REMINDER')->view('reminder');
    }
}
