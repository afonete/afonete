<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequested extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $address;
    public $transactionNo;

    public function __construct($user, $amount, $address, $transactionNo)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->address = $address;
        $this->transactionNo = $transactionNo;
    }

    public function build()
    {
        return $this->subject('🔔 New Manual Withdrawal Request — ' . $this->user->name)
                    ->view('emails.withdrawal-requested');
    }
}
