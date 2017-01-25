<?php

namespace App\Events;

use App\Events\Event;
use App\Question;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QuestionWasUsed extends Event
{
    use SerializesModels;

    public $question;
    public $user;
    public $variables;

    /**
     * @param $question
     * @param $user
     */
    public function __construct(Question $question,User $user, array $variables)
    {
        /*
         * Set the Class Variables
         */
        $this->user = $user;
        $this->question = $question;
        $this->variables = $variables;
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
