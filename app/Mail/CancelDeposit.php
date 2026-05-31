<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class CancelDeposit extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $deposit ;
    public $user;
    public $reason;
    public function __construct( $deposit,$reason)
    {
        $this->deposit = $deposit['amount_deposited'];
        $this->user = User::where('id',$deposit['user_id'])->first();
        $this->reason = $reason;

        // dd($this->user['name']);


    }

    public function build(){

        return $this->view('emails.cancel-deposit')
                    ->subject('Deposit Cancelled!')
                    ->with([
                        'user' => $this->user['name'],
                        'deposit' => $this->deposit,
                        'reason' => $reason
                    ]);
    }

}
