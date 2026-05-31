<?php

// app/Mail/ReminderEmail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment as Paymodel;
use App\Models\User;

class ExpirationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $package;

    public function __construct(Paymodel $package)
    {
        $this->package = $package;
    }

    public function build()
    {
        $user = $user::find('id',$this->package->user);

        return $this->subject('Package Expired')
                    ->view('emails.expiration')
                    ->with(['package'=> $this->package,"user"=>$user]);
    }
}


?>
