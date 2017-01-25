<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PayoutFailed extends Event
{
    use SerializesModels;

    public $user;
    public $amount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $amount)
    {
        $this->amount = $amount;
        $this->user = $user;
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
