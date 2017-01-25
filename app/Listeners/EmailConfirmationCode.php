<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;

class EmailConfirmationCode implements ShouldQueue
{
    private $mailer;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->confirmation_code = str_random(16);
        $event->user->save();

        $this->mailer->send('emails.confirmation_code_first_time',
            ['user' => $event->user],
            function($m) use ($event){
                $m->to($event->user->email)->subject('Engr Mastered Address Verification');
            }
        );
    }
}
