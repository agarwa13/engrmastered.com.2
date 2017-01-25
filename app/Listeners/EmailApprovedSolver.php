<?php

namespace App\Listeners;

use App\Events\QuestionsSolutionWasApproved;
use App\Solution;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class EmailApprovedSolver implements ShouldQueue
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
        $solution = Solution::findOrFail($event->question->approved_solution_id);
        $user = $solution->creator;

        Log::info($user->email);

        $this->mailer->send('emails.solution_approved',
            [
                'question' => $event->question,
                'user'=>$user
            ],
            function($m) use ($user){
                $m->to($user->email)
                    ->subject('Engineering Mastered - Solution Approved');
            }
        );

    }
}
