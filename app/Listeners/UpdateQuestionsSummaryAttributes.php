<?php

namespace App\Listeners;

use App\Events\QuestionsSolutionModified;
use App\Question;
use App\Solution;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateQuestionsSummaryAttributes implements ShouldQueue
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
     * @param  QuestionsSolutionModified  $event
     * @return void
     */
    public function handle(QuestionsSolutionModified $event)
    {

        $question = Question::findOrFail($event->question->id);

        /*
         * Check to see if there is an approved solution, if there is, we can easily update the attributes
         */
        $approved_solution = Solution::where('question_id',$question->id)->where('reviewer_id','>',0)->first();

        if($approved_solution != null){
            $question->has_solutions = true;
            $question->has_approved_solution = true;
            $question->approved_solution_id = $approved_solution->id;

        }else{
            $question->has_approved_solution = false;
            $question->approved_solution_id = null;
            $question->has_solutions = (count($question->solutions) > 0);
        }

        $question->save();

    }
}