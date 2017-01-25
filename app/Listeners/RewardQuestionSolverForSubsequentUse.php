<?php

namespace App\Listeners;

use App\Events\QuestionWasUsed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RewardQuestionSolverForSubsequentUse implements ShouldQueue
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
     * @param  QuestionWasUsed  $event
     * @return void
     */
    public function handle(QuestionWasUsed $event)
    {
        $event->question->getApprovedSolution()->creator->increment_income(0.2);
    }
}
