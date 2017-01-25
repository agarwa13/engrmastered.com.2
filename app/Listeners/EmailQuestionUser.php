<?php

namespace App\Listeners;

use App\Events\FirstTimeQuestionWasUsed;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailQuestionUser implements ShouldQueue
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
     * @param  FirstTimeQuestionWasUsed  $event
     * @return void
     */
    public function handle($event)
    {

        $this->mailer->send('emails.question_was_used',
            [
                'question' => $event->question,
                'user'=>$event->user
            ],
            function($m) use ($event){
                $m->to($event->user->email)
                    ->subject('Engineering Mastered - Ensuring You Are Satisfied');
            }
        );

    }
}
