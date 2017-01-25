<?php

namespace App\Listeners;

use App\Events\QuestionWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;

class SendCreatedQuestionEmail implements ShouldQueue
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
     * @param  QuestionWasCreated  $event
     * @return void
     */
    public function handle(QuestionWasCreated $event)
    {
        /*
         * Get the Email Address
         */
        $email_address = $event->question->creator->email;

        /*
         * Send the Mail
         */
        $this->mailer->send('emails.question_was_created',
            ['question' => $event->question],
            function ($message) use ($email_address) {
                $message
                    ->to($email_address)
                    ->subject('Question Created');
            });
    }
}
