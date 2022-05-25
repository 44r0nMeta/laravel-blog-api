<?php

namespace App\Listeners;

use App\Events\NewUserAdded;
use App\Jobs\SendInviteLinkJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInviteLinkListner
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
     * @param  object  $event
     * @return void
     */
    public function handle(NewUserAdded $event)
    {

        SendInviteLinkJob::dispatch($event->user);
    }
}
