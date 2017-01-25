<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 8/25/2015
 * Time: 2:58 PM
 */

namespace App\Http\Controllers;
use AlgoliaSearch\Client;
use App\Events\QuestionsSolutionModified;
use App\Events\QuestionsSolutionWasApproved;
use App\Review;
use App\Services\AlchemyAPI;
use App\Services\ElasticSearchClient;
use App\Services\Notification;
use App\Services\StripeClient;
use Elasticsearch\ClientBuilder;
use ViewHelper;
use App\HTMLGenerator;
use App\Solution;
use Illuminate\Http\Request;
use App\Question;
use App\Course;
use Storage;
use Search;
use Event;
use File;
use Auth;
use App\Services\SiteMap;
use App\User;
use DateTime;

class AdminController extends Controller
{

    public function __construct(){

        /*
         * Do not allow access to this controller unless user is Authorized and is Admin
         */
        $this->middleware('auth');
        $this->middleware('admin');

        $this->middleware('question_inputs',['only' => ['postQuestionWithUnapprovedSolutions']]);

    }




    function postAction(Request $request){

        if($request->input('action') == 'approve_question'){
            if($this->approveQuestion($request->user()->id, $request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Question Approved'
                ]);
            }
        }

        if($request->input('action') == 'approve_solution'){
            if($this->approveSolution($request->user()->id, $request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Solution Approved'
                ]);
            }
        }

        if($request->input('action') == 'revert_question_approval'){
            if($this->revertQuestionApproval($request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Question Approval Reverted'
                ]);
            }
        }

        if($request->input('action') == 'revert_solution_approval'){
            if($this->revertSolutionApproval($request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Solution Approval Reverted'
                ]);
            }
        }

        if($request->input('action') == 'revert_approved_solution'){
            if($this->revertApprovedSolution($request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Solution Approval Reverted'
                ]);
            }
        }

        if($request->input('action') == 'review_later'){
            if($this->markForReviewLater($request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Marked for Later Review'
                ]);
            }
        }

        if($request->input('action') == 'review_completed'){
            if($this->markReviewCompleted($request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Review Completed'
                ]);
            }
        }


        if($request->input('action') == 'refund_tokens_based_on_review'){
            if($this->refund_tokens_based_on_review($request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Review Completed'
                ]);
            }
        }


        if($request->input('action') == 'refund_charge_based_on_review'){
            if($this->refund_charge_based_on_review($request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Review Completed'
                ]);
            }
        }

        if($request->input('action') == 'refund_tokens_and_charge_based_on_review'){
            if($this->refund_tokens_and_charge_based_on_review($request->input('id'))){
                return response()->json([
                    'success' => true,
                    'message' => 'Review Completed'
                ]);
            }
        }



    }

    function refund_tokens_and_charge_based_on_review($review_id){
        $this->refund_charge_based_on_review($review_id);
        $this->refund_tokens_based_on_review($review_id);
        return true;
    }

    function refund_charge_based_on_review($review_id){
        $review = Review::with('question_usage_record','user')->find($review_id);
        $notifications = new Notification();

        if($review->question_usage_record->charge_refunded == false ){

            $review->question_usage_record->charge_refunded = true;
            $review->question_usage_record->save();

            if($review->question_usage_record->stripe_charge_id){
                $stripe_client = new StripeClient();
                $stripe_client->refund_charge($review->question_usage_record->stripe_charge_id);
                $review->user->save();
                $notifications->add_notification('Charge refunded');
            }else{
                $notifications->add_notification('User was not charged for this question','warning');
            }
        }else{
            $notifications->add_notification('Charge has already been refunded','warning');
        }

        $this->mark_refund_review_complete($review);

        return true;
    }

    function refund_tokens_based_on_review($review_id){
        $review = Review::with('question_usage_record','user')->find($review_id);
        $notifications = new Notification();

        if($review->question_usage_record->tokens_refunded == false ){

            $review->question_usage_record->tokens_refunded = true;
            $review->question_usage_record->save();

            $review->user->tokens_remaining += $review->question_usage_record->tokens_paid;
            $review->user->save();
            $notifications->add_notification('Tokens refunded');
        }else{
            $notifications->add_notification('Tokens have already been refunded','warning');
        }

        $this->mark_refund_review_complete($review);

        return true;
    }

    function mark_refund_review_complete($review){
        $review->refund_request_reviewed = true;
        $review->save();
    }

    function markForReviewLater($questionId){
        $question = Question::find($questionId);
        $question->review_later = true;
        return $question->save();
    }

    function markReviewCompleted($questionId){
        $question = Question::find($questionId);
        $question->review_later = false;
        return $question->save();
    }

    function revertApprovedSolution($questionId){
        $question = Question::find($questionId);
        $solution = $question->getApprovedSolution();
        $solution->reviewer_id = null;
        event(new QuestionsSolutionModified($question));
        return $solution->save();
    }

    function revertQuestionApproval($questionId){
        $question = Question::findOrFail($questionId);
        $question->reviewer_id = null;
        return $question->save();
    }

    function revertSolutionApproval($solutionId){
        $solution = Solution::findOrFail($solutionId);
        $solution->reviewer_id = null;
        $success = $solution->save();
        event(new QuestionsSolutionModified($solution->question));
        return $success;
    }

    function approveSolution($reviewerId, $solutionId){

        /*
         * Approve the Solution
         */
        $solution = Solution::find($solutionId);
        $solution->reviewer_id = $reviewerId;
        $success =  $solution->save();

        /*
         * Fire the QuestionsSolutionWasApproved and Solution Approved Event if successful
         */
        if($success){
            event(new QuestionsSolutionWasApproved($solution->question));
            event(new QuestionsSolutionModified($solution->question));
        }

        /*
         * Return success or failure
         */
        return $success;

    }

    function approveQuestion($reviewerId, $questionId){
        $question = Question::findorfail($questionId);
        $question->reviewer_id = $reviewerId;
        return $question->save();
    }

    function getDashboard(Request $request){
        return view('admin/dashboard',[
            'questions' => Question::all()
        ]);
    }

    function getQuestionsForLaterReview(Request $request){
        $questions = Question::getQuestionsForLaterReview();
        $questions = $questions->paginate(10);
        return ViewHelper::getResponseToShowQuestions($request, $questions, 'Questions For Later Review');
    }

    function getAllQuestions(Request $request){
        $query_string = "";
        $page_number = 0;
        $course_number = null;
        $creator_id = null;
        $solver_id = null;
        $is_approved = "";
        $has_solutions = "";
        $has_approved_solution = "";

        return view('question.index')
            ->with('query',$query_string)
            ->with('page_number',$page_number)
            ->with('course_number',$course_number)
            ->with('title','Questions')
            ->with('creator_id',$creator_id)
            ->with('solver_id',$solver_id)
            ->with('is_approved',$is_approved)
            ->with('has_solutions',$has_solutions)
            ->with('has_approved_solution', $has_approved_solution);
    }

    function getQuestionsPendingApproval(Request $request){

        $query_string = "";
        $page_number = 0;
        $course_number = null;
        $creator_id = null;
        $solver_id = null;
        $is_approved = "false";
        $has_solutions = "";
        $has_approved_solution = "";

        return view('question.index')
            ->with('query',$query_string)
            ->with('page_number',$page_number)
            ->with('course_number',$course_number)
            ->with('title','Questions')
            ->with('creator_id',$creator_id)
            ->with('solver_id',$solver_id)
            ->with('is_approved',$is_approved)
            ->with('has_solutions',$has_solutions)
            ->with('has_approved_solution', $has_approved_solution);


//        /*
//         * Get the Questions Pending Approval
//         */
//        $questions = Question::getUnapprovedQuestions()->orderBy('id','DESC');
//        $questions = $questions->paginate(10);
//
//        /*
//         * Return the View or JSON
//         */
//        return ViewHelper::getResponseToShowQuestions($request, $questions, 'Questions Pending Approval');
    }

    function getQuestionsWithoutSolutions(Request $request){

        $query_string = "";
        $page_number = 0;
        $course_number = null;
        $creator_id = null;
        $solver_id = null;
        $is_approved = "true";
        $has_solutions = "false";
        $has_approved_solution = "";

        return view('question.index')
            ->with('query',$query_string)
            ->with('page_number',$page_number)
            ->with('course_number',$course_number)
            ->with('title','Questions')
            ->with('creator_id',$creator_id)
            ->with('solver_id',$solver_id)
            ->with('is_approved',$is_approved)
            ->with('has_solutions',$has_solutions)
            ->with('has_approved_solution', $has_approved_solution);

//
//        /*
//         * Get the Questions that do not have any solutions
//         */
//        $questions = Question::getApprovedQuestionsWithoutAnySolutions();
//        /*
//         * Execute the Query and get the Questions
//         */
//        $questions = $questions->paginate(10);
//
//        /*
//         * Return the View or JSON
//         */
//        return ViewHelper::getResponseToShowQuestions($request, $questions, 'Questions Without Any Solutions');
    }

    function getQuestionsWithUnapprovedSolutions(Request $request){

        $query_string = "";
        $page_number = 0;
        $course_number = null;
        $creator_id = null;
        $solver_id = null;
        $is_approved = "true";
        $has_solutions = "true";
        $has_approved_solution = "false";

        return view('question.index')
            ->with('query',$query_string)
            ->with('page_number',$page_number)
            ->with('course_number',$course_number)
            ->with('title','Questions')
            ->with('creator_id',$creator_id)
            ->with('solver_id',$solver_id)
            ->with('is_approved',$is_approved)
            ->with('has_solutions',$has_solutions)
            ->with('has_approved_solution', $has_approved_solution);

//        /*
//         * Get the Questions with Unapproved Solutions
//         */
//        $questions = Question::getApprovedQuestionsWithUnapprovedSolutions();
//
//
//        /*
//         * Execute the Query and get the Questions
//         */
//        $questions = $questions->paginate(10);
//
//        /*
//         * Return the View or JSON
//         */
//        return ViewHelper::getResponseToShowQuestions($request, $questions, 'Questions With Unapproved Solutions');
    }

    function getQuestionWithUnapprovedSolutions(Request $request, $question_id){

        /*
         * Find the Question or fail
         */
        $question = Question::with('solutions')->findorfail($question_id);

        /*
         * Display the Question along with all the Ready for Review Solutions
         */
        return view('admin/question_with_unapproved_solutions',[
            'question' => $question,
            'request' => $request,
            'html_generator' => new HTMLGenerator()
        ]);
    }

    function postQuestionWithUnapprovedSolutions(Request $request, $question_id){
        /*
         * Flash the Request
         */
        $request->flash();

        /*
         * Find the Question of fail
         */
        $question = Question::with('solutions')->findorfail($question_id);

        /*
         * Return the View
         */
        return view('admin/question_with_unapproved_solutions',[
            'question' => $question,
            'request' => $request,
            'html_generator' => new HTMLGenerator()
        ]);
    }

    function getExecute(Request $request){


        $questions = Question::where('id','<',470)->where('id','>',448)->get();
        return view('admin/execute')->with('questions',$questions);


    }

}

