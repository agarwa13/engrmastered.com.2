<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\QuestionsSolutionWasApproved' => [
            'App\Listeners\EmailFollowingUsers',
            'App\Listeners\EmailRejectedSolvers',
            'App\Listeners\EmailApprovedSolver',
        ],

        'App\Events\QuestionWasCreated' => [
            'App\Listeners\SendCreatedQuestionEmail'
        ],

        'App\Events\FirstTimeQuestionWasUsed' => [
            'App\Listeners\RewardQuestionCreator',
            'App\Listeners\RewardQuestionSolver',
            'App\Listeners\EmailQuestionUser',
//            'App\Listeners\LogQuestionUsage'
        ],

        'App\Events\QuestionWasUsed' => [
            'App\Listeners\RewardQuestionSolverForSubsequentUse',
            'App\Listeners\EmailQuestionUser',
//            'App\Listeners\LogQuestionUsage'
        ],


        'App\Events\PayoutFailed' => [
            'App\Listeners\ReportPayoutFailure'
        ],

        'App\Events\LargePayoutRequested' => [
            'App\Listeners\ReportLargePayout'
        ],

        'App\Events\UserCreated' => [
            'App\Listeners\EmailConfirmationCode'
        ],

        'App\Events\QuestionsSolutionModified' => [
            'App\Listeners\UpdateQuestionsSummaryAttributes'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
