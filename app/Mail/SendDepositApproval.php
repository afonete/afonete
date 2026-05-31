<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendDepositApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $deposit ;
    public $user;

    public function __construct( $deposit)
    {
        $this->deposit = $deposit['amount_deposited'];
        $this->user = User::where('id',$deposit['user_id'])->first();

        // dd($this->user['name']);


    }

    public function build()
    {
        return $this->view('emails.deposit-approval')
                    ->subject('Deposit Approved - Start Investing Today!')
                    ->with([
                        'user' => $this->user['name'],
                        'deposit' => $this->deposit,
                    ]);
    }

}
