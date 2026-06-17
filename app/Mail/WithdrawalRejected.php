<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $transactionNo;
    public $reason;

    public function __construct($user, $amount, $transactionNo, $reason)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->transactionNo = $transactionNo;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('❌ Withdrawal Rejected — Reference ' . $this->transactionNo)
                    ->view('emails.withdrawal-rejected');
    }
}
