<?php

namespace App\Http\Middleware;

use Closure;
use App\Question;
use App\User;

class UpdateUsageTable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
         * This is an After Middleware
         */
        $response = $next($request);

        if($request->user()){
            /*
             * Get the Logged In User
             */
            $user = $request->user();
        }else{
            /*
             * If no user is logged in, register the usage under the guest user
             */
            $user = User::getGuestUser();
        }

        /*
         * Get the Question associated with the request
         */
        $question = Question::find($request->question);

        /*
         * Update the question_user table.
         */
        $question->usedBy()->attach($user->id);
        $question->save();

        /*
         * Return
         */
        return $response;

    }
}
