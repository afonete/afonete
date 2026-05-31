<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class activate extends Mailable
{
    use Queueable, SerializesModels;
public $status;
public $email;
    /**
     * Create a new message instance.
     * @param  string $status
     * @param  string $email
     * @return void
     */
    public function __construct($status,$email)
    {
        $this->status=$status;
        $this->email=$email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->subject('  News Activation')
                    ->view('emails.news');
    }
}
