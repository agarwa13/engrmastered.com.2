<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use App\HTMLGenerator;
use Illuminate\Support\Collection;

/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 9/24/2015
 * Time: 9:17 PM
 */
class ViewHelper
{

    /**
     * @param Request $request
     * @param $questions
     * @return mixed
     */
    public static function getResponseToShowQuestions(Request $request, $questions, $heading = '', $title = '')
    {
        /*
         * Return the Response as json or view
         */
        if ($request->ajax()) {

            // Get the Pagination HTML
            $pagination = $questions->appends(['q' => $request->input('q')])->render();

            // Convert the Pagination Instance into a collection (so we can use transform)
            $questions = new Collection($questions->items());

            // Transform the List of Question (Models) into HTML to be displayed
            $questions->transform(function ($question, $key) {
                $html_generator = new HTMLGenerator();
                return view('html_generator/question_summary_with_actions', [
                    'question' => $question,
                    'html_generator' => $html_generator,
                    'actions' => $html_generator->getActionsForQuestions($question)
                ])->render();
            });

            // Return the Response as JSON
            return response()->json([
                'success' => true,
                'questions' => $questions,
                'pagination' => $pagination
            ], 200);

        } else {
            /*
            * Build the View
            */
            $view = view('question/index_old')
                ->with('questions', $questions)
                ->with('html_generator', new HTMLGenerator())
                ->with('heading', $heading)
                ->with('title',$title);

            /*
             * Send the Search Query Parameter to the View If Passed
             */
            if ($request->has('q')) {
                $view = $view->with('q', $request->input('q'));
            }

            /*
             * Build the View
             */
            return $view;
        }
    }

}