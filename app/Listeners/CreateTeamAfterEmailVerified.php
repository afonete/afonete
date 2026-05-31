<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Teams;

class CreateTeamAfterEmailVerified
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        //

        $user = $event->user;

        // Create a team for the user if they don't already have one
        if (!$user->ownedTeams()->exists()) {
            Teams::create([
                'user_id' => $user->id,
                'side' => 'LEFT', // or any default value or logic to determine the side
            ]);
        }

    }
}
