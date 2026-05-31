<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreatedUser extends Mailable
{
    use Queueable, SerializesModels;

public $email;public $password; public $username;
    /**
     * Create a new message instance.
     *
     *  
     * @param  string $email
     * @param  string $password
     * @param  string $username
     * @return void
     */
    public function __construct($email,$password,$username)
    {
       
        $this->email=$email;
         $this->password=$password;
         $this->username=$username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(' FONEPO REQUEST ACCEPTED')
        ->view('emails.Created-User');
    }
}
