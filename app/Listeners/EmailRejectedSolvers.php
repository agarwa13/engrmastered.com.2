<?php

namespace App\Listeners;

use App\Events\QuestionsSolutionWasApproved;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Solution;
use Log;

class EmailRejectedSolvers implements ShouldQueue
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
        $solutions = Solution::where('question_id',$event->question->id)->where('reviewer_id','<','1')->get();

        foreach($solutions as $solution){
            $user = $solution->creator;
            $this->mailer->send('emails.solution_rejected',
                [
                    'question' => $event->question,
                    'user'=>$user
                ],
                function($m) use ($user){
                    $m->to($user->email)
                        ->subject('Engineering Mastered - Solution Rejected');
                }
            );
        }
    }
}
