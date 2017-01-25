<?php
/**
 * Created by PhpStorm.
 * User: nikhilagarwal
 * Date: 9/20/15
 * Time: 4:34 PM
 */

namespace App\Http\Controllers;

use App\HTMLGenerator;
use Illuminate\Http\Request;
use App\Question;
use App\Solution;

class HTMLController extends Controller {

    public function getButton($button, $action, $question_id, $solution_id = null){

        $html_generator = new HTMLGenerator();

        /*
         * Should have a Question
         */
        $question = Question::find($question_id);

        /*
         * May or May not have a Solution
         */
        if($solution_id){
            $solution = Solution::find($solution_id);
        }else{
            $solution = null;
        }

        /*
         * If Button is True, Return a Button otherwise return a link
         */
        if($button == "true"){
            $element = $html_generator->displayActionAsButton($action, $question, $solution);
        }else{
            $element = $html_generator->displayAction($action, $question, $solution);
        }

        /*
         * Return the response as Json
         */
        return response()->json([
            'button' => $element,
            'success' => true
        ],200);

    }

} 