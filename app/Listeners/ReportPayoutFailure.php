<?php

namespace App\Listeners;

use App\Events\PayoutFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;

class ReportPayoutFailure
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
     * @param  PayoutFailed  $event
     * @return void
     */
    public function handle(PayoutFailed $event)
    {
        $this->mailer->send('emails.payout_failed_admin',
            ['user' => $event->user, 'amount' => $event->amount],
            function($message) use ($event){
                $message
                    ->to('nikhil.agarwal@live.com')
                    ->subject('EngineeringMastered.com - Payout Failed');
            }
        );
    }
}
