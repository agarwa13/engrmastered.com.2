<?php

namespace App\Events;

use App\Events\Event;
use App\Question;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;

class FirstTimeQuestionWasUsed extends Event
{
    use SerializesModels;

    public $question;
    public $user;
    public $variables;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Question $question, User $user, array $variables)
    {

        /*
         * Update the Question to Show that it has not been purchased
         */
        $question->purchased_at_least_once = true;
        $question->save();

        /*
         * Set the Class Variables
         */
        $this->question = $question;
        $this->user = $user;
        $this->variables;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
