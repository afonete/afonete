<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels; 

     public $activation;public $package;
   /**
    * Create a new message instance.
    * @param  string $activation
    * @param  string $package
    * @return void
    */
    public function __construct($activation,$package)
    {
       $this->activation=$activation;
      $this->package = $package;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->subject('  package Activation')
                    ->view('emails.activate');
                       }
}