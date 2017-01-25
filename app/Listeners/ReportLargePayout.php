<?php

namespace App\Listeners;

use App\Events\LargePayoutRequested;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportLargePayout implements ShouldQueue
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
     * @param  LargePayoutRequested  $event
     * @return void
     */
    public function handle(LargePayoutRequested $event)
    {
        $this->mailer->send(
            'emails.report_large_payout_request',
            [
                'user' => $event->user,
                'amount' => $event->amount
            ],
            function($m) use ($event){
                $m->to('nikhil@engineeringmastered.com')->subject('User Requeseted Large Payout');
            }
        );
    }
}
