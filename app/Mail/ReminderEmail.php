<?php

// app/Mail/ReminderEmail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment as Paymodel;
use App\Models\User;

class ReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $package;

    public function __construct(Paymodel $package)
    {
        $this->package = $package;
    }

    public function build()
    {
        $user = User::where('id',$this->package->user)->first();

        echo "Email Sent Successfully";
        return $this->subject('Reminder: Package Activation')
                    ->view('emails.reminder')
                    ->with(['package'=> $this->package,"user"=>$user]);
    }
}



?>
