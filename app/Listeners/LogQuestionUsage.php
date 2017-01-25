<?php

namespace App\Listeners;

use App\Events\FirstTimeQuestionWasUsed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogQuestionUsage implements ShouldQueue
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
    public function handle($event)
    {
        /*
         * Log that the Question was Used
         */

        foreach(array_keys($event->variables) as $key){
            if (strpos($key,'var') === false){
                unset($event->variables[$key]);
            }
        }

        $event->question->usedBy()->attach($event->user->id, ['variables_used' => json_encode($event->variables)]);
        $event->question->save();

    }
}
