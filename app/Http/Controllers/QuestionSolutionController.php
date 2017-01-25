<?php

namespace App\Http\Controllers;

use App\Events\FirstTimeQuestionWasUsed;
use App\Events\QuestionWasUsed;
use App\HTMLGenerator;
use App\QuestionUsageRecord;
use Illuminate\Http\Request;
use App\Events\QuestionsSolutionModified;
use App\Http\Requests;
use App\Question;
use App\Solution;
use Storage;
use App\Http\Controllers\QuestionController;
use Auth;
use App\User;
use Session;

class QuestionSolutionController extends Controller
{

    /*
     * Middleware Controller
     */
    public function __construct(){

        /*
         * To access any solutions, user must be authenticated
         * But they do not need to be logged in to see a solution
         */
        $this->middleware('auth',['except' => ['index']]);

        /*
         * To see the create solution page, update solution page, store a solution or update a solution
         * the user must be manually promoted to an editor role
         */
        $this->middleware('editor', ['only' => ['create','store','edit','update']]);


        /*
         * When displaying the solution to an approved question, we should reduce tokens and update the usage table
         */
        $this->middleware('register_login_subscribe',['only' => ['index']]);
        $this->middleware('charge', ['only' => ['index']]);
        $this->middleware('question_inputs', ['only' => ['index','store','update']]);

    }

    /**
     * @param Request $request
     * @param $questionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $questionId)
    {
        /*
         * This function will show the approved solution
         */
        $question = Question::find($questionId);
        $solution = $question->getApprovedSolution();
        $answer = $solution->getAnswer($request);

        /*
         * Fire the Question Was Used Event
         * This ensures that the Usage is Logged,
         * and that the Solver and Creator are Paid
         * and finally that the response is not slowed down
         * by this.
         */
        if($question->purchased_at_least_once == true){
            event(new QuestionWasUsed($question,($request->user() ? $request->user() : User::getGuestUser()), $request->all() ));
        }else{
            event(new FirstTimeQuestionWasUsed($question,($request->user() ? $request->user() : User::getGuestUser()), $request->all()));
        }

        // Log Question Usage
        $variables = $request->all();
        foreach(array_keys($variables) as $key){
            if (strpos($key,'var') === false){
                unset($variables[$key]);
            }
        }

        $user = ($request->user() ? $request->user() : User::getGuestUser());

        $usage_record = QuestionUsageRecord::create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'variables_used' => json_encode($variables),
            'tokens_paid' => $request->session()->pull('tokens_paid',0),
            'stripe_charge_id' => $request->session()->pull('stripe_charge_id',null),
            'stripe_charge_amount' => $request->session()->pull('stripe_charge_amount',null),
        ]);


        /*
         * Return the View with the Solution or the solution text via Ajax
         */
        if ($request->ajax()){
            return $answer;
        }else{
            $request->flash();
            $question = Question::find($questionId);
            if($question->images){
                $images = json_decode($question->images);
            }else{
                $images = "";
            }
            return view('question/solution/show',[
                'question' => $question,
                'images' => $images,
                'answer' => $answer,
                'usage_record' => $usage_record
            ]);
        }
    }

    /**
     * Show the form for creating a new solution of the question.
     *
     * @return Response
     */
    public function create(Request $request, $questionId)
    {
        $question = Question::find($questionId);

        /*
         * Send the User Back if the Question already has an approved solution
         * unless he is an admin of course
         */
        if($question->hasApprovedSolution() && !$request->user()->isAdmin()){
            return back()->with('errors',["Question already has an approved Solution"]);
        }

        /*
         * Get he Images of the Question
         */
        if($question->images){
            $images = json_decode($question->images);
        }else{
            $images = "";
        }

        /*
         * Check if the user has already submitted a solution for this question
         * If he has allow him to edit that solution instead of creating a new one.
         */
        $solution = Solution::where('question_id',$questionId)->where('creator_id',$request->user()->id)->first();

        $html_generator = new HTMLGenerator();
        $actions = $html_generator->getActionsForQuestions($question);

        if($solution == null){
            return view('question/solution/create',[
                'question' => $question,
                'images' => $images,
                'editMode' => false,
                'actions' => $actions,
                'html_generator' => $html_generator
            ]);
        }else{
            return view('question/solution/create',[
                'question' => $question,
                'images' => $images,
                'editMode' => true,
                'solution' => $solution,
                'actions' => $actions,
                'html_generator' => $html_generator
            ]);
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param Integer $questionId
     * @return Response
     */
    public function store(Request $request, $questionId)
    {
        /*
         * Retrieve this users solution to this question
         * or create a new one
         */
        $solution = Solution::firstOrCreate([
            'question_id' => $questionId,
            'creator_id' => $request->user()->id
        ]);

        /*
         * Update the Question Has Solutions Field
         */
        $question = Question::findOrFail($questionId);
        $question->has_solutions = true;
        $question->save();

        if($solution->file == "") {
            $filename = 'solutions/'.uniqid($questionId."_").".php";
            $solution->file = $filename;
        }

        event(new QuestionsSolutionModified($solution->question));

        return $this->storeOrUpdate($request, $solution);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $questionId, $id)
    {
        return "This Controller Method Has Not Been Implemented Yet";
    }

    /**
     * Show the form for editing the specified solution.
     * Also makes sure that the solution id belongs to the specified questionId
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $questionId, $id)
    {
        $solution = Solution::find($id);

        if($questionId != $solution->question_id){
            return back()->with('status','Solution Id does not match Question Id');
        }

        $question = Question::find($questionId);

        /*
         * Send the User Back if the Question already has an approved solution
         * unless he is an admin
         */
        if($question->hasApprovedSolution() && !$request->user()->isAdmin()){
            return back()->with('errors',["Question already has an approved Solution"]);
        }

        /*
         * Get he Images of the Question
         */
        if($question->images){
            $images = json_decode($question->images);
        }else{
            $images = "";
        }
        /*
         * We will re-use the same view as the create method but we will add the existing data
         */

        $html_generator = new HTMLGenerator();
        $actions = $html_generator->getActionsForQuestions($question);

        return view('question/solution/create',[
            'question' => $question,
            'images' => $images,
            'editMode' => true,
            'solution' => $solution,
            'actions' => $actions,
            'html_generator' => $html_generator
        ]);


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $questionId, $id)
    {
        /*
         * Only Update If User is Admin
         * or
         * Solution is Not Approved and the User is the Creator
         */
        $solution = Solution::find($id);
        if($request->user()->isAdmin() || (!$solution->isApproved() && $solution->creator_id == $request->user()->id)){
            return $this->storeOrUpdate($request, $solution);
        }else{
            App::abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $questionId, $id)
    {
        $success = false;

        /*
         * Students can delete a solution if
         * They Created it
         * Admin has not yet approved it
         */
        $solution = Solution::find($id);

        if(!$solution->isApproved() && $solution->creator_id == $request->user()->id){
            $success = $solution->delete();
        }

        /*
         * Admin can delete a question at any time.
         */
        elseif($request->user()->isAdmin()){
            $success = $solution->delete();
        }

        /*
         * Fire the Event
         */
        event(new QuestionsSolutionModified($solution->question));

        /*
         * If AJAX Request, Send Back JSON
         */
        if($request->ajax()){
            return response()->json([
                'success' => $success
            ]);
        }

        /*
         * If POST Request, Send to Home Page Once Deleted.
         */
        if($success){
            return redirect('/');
        }else{
            return back()->with('status','Unable to Delete Question');
        }
    }

    /**
     * @param Requests\submitQuestionRequest $request
     * @param $solution
     * @param $filename
     * @return mixed
     */
    private function storeOrUpdate(Request $request, $solution)
    {
        $return_value = array();
        $return_value['request_type'] = $request->input('request_type');

        /*
         * Expect 3 types of requests
         * 1. Autosave
         * 2. Check Solution
         * 3. Change Ready for Review Status
         */

        // We always update the Code
        Storage::put($solution->file, $request->input('solution'), true);



        // We save here in case it is a new solution even though we haven't really updated any of its properties
//        $solution->code = $solution->getCodeAttribute();
        $solution->save();



        switch($request->input('request_type')){
            case 'auto_save':
                return response()->json($return_value);

            case 'check_solution':
                $return_value['answer'] = $solution->getAnswer($request);
                return response()->json($return_value);

            case 'change_ready_for_review_status':
                $solution->ready_for_review = $request->input('ready_for_review');
                $solution->save();
                $return_value['ready_for_review'] = $solution->ready_for_review;
                return response()->json($return_value);

            default:
                abort(400);
        }

    }

}
