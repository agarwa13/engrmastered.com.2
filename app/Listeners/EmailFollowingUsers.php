<?php

namespace App\Listeners;

use App\Events\QuestionsSolutionWasApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;

class EmailFollowingUsers implements ShouldQueue
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
     * @param  QuestionsSolutionWasApproved  $event
     * @return void
     */
    public function handle(QuestionsSolutionWasApproved $event)
    {
        // Send Email to Each user that is following the question that was solved
        foreach($event->question->followedBy as $user){
            $this->mailer->send('emails.question_was_solved',
                ['question' => $event->question],
                function($message) use ($user){
                $message
                    ->to($user->email)
                    ->subject('EngineeringMastered.com - Question Solved');
            });
        }

    }
}
