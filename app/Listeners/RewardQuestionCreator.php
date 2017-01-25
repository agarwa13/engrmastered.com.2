<?php

namespace App\Listeners;

use App\Events\FirstTimeQuestionWasUsed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RewardQuestionCreator implements ShouldQueue
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
     * @param  FirstTimeQuestionWasUsed  $event
     * @return void
     */
    public function handle(FirstTimeQuestionWasUsed $event)
    {
        /*
         * Reward the Student who asked the question
         */
        $event->question->creator->increment_tokens(2);
    }
}
