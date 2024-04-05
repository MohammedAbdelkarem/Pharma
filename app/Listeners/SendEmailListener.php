<?php

namespace App\Listeners;

use App\Event\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendEmail $event): void
    {
        Mail::to($event->email)->send(new \App\Mail\SendEmail($event->code));
    }
}
