<?php

namespace App\Http\Middleware;

use App\Question;
use App\User;
use Closure;

class Pay
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
        $response = $next($request);

        /*
         * Pay the User who created the solution unless
         * an admin accessed the solution
         * or the creator of the solution accessed the solution
         */
        $solution = Question::find($request->question)->getApprovedSolution();
        $tokens_required = config('constants.charge_per_question');
        $solver = $solution->creator;


        if($request->user() && ($request->user()->isAdmin() || $request->user()->id == $solver->id)){
            // Do not pay the solver
        }else{
            $solver->income = $solver->income + ((float)$tokens_required)/2;
            $solver->save();
        }

        return $response;
    }
}
