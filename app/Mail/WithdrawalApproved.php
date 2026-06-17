<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $transactionNo;
    public $txnHash;

    public function __construct($user, $amount, $transactionNo, $txnHash = null)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->transactionNo = $transactionNo;
        $this->txnHash = $txnHash;
    }

    public function build()
    {
        return $this->subject('✅ Withdrawal Approved — Reference ' . $this->transactionNo)
                    ->view('emails.withdrawal-approved');
    }
}
