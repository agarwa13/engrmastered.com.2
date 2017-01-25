<?php

namespace App\Http\Controllers;

use App\Events\QuestionWasCreated;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Question;
use App\Course;

use App\Http\Requests;
use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Http\Requests\Questions\UpdateQuestionRequest;
use App\Http\Requests\Questions\DeleteQuestionRequest;

use App\Http\Controllers\Controller;


use App\HTMLGenerator;


use Event;
use Input;
use Paginator;
use Storage;
use Auth;
use Mail;
use DB;

class QuestionController extends Controller
{

    /*
     * Specify Middleware
     */
    public function __construct(){
        $this->middleware('auth',['except' => ['getUnsolved','index','show','getSolved']]);
    }

    /*
     * Display Unsolved (Unanswered) Questions
     */
    public function getUnsolved(Request $request){

        $questions = Question::getUnsolvedQuestions()->orderBy('num_followers','desc');

        /*
         * Execute the Query and get the Questions
         */
        $questions = $questions->paginate(10);

        return ViewHelper::getResponseToShowQuestions($request, $questions, 'Unsolved Questions');
    }

    public function getSolved(Request $request){
        $questions = Question::getApprovedSolvedQuestions();
        $questions = $questions->with('courses.university')->paginate(10);

        /*
         * Return the View or JSON
         */
        return ViewHelper::getResponseToShowQuestions($request, $questions, 'Solved Questions');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
//        $questions = Question::getApprovedSolvedQuestions();
//        $questions = $questions->with('courses.university')->paginate(10);

        /*
         * Return the View or JSON
         */
//        return ViewHelper::getResponseToShowQuestions($request, $questions, 'Solved Questions');


        $query_string = "";
        $page_number = 0;
        $course_number = null;
        $creator_id = null;
        $solver_id = null;
        $is_approved = "";
        $has_solutions = "";
        $has_approved_solution = "true";

        return view('question.index')
            ->with('query',$query_string)
            ->with('page_number',$page_number)
            ->with('course_number',$course_number)
            ->with('title','Search Questions and Answers from WebAssign, SmartPhysics and other college level courses |')
            ->with('description','Plug in the values from your homework problem into our calculator to get the answers to your particular homework problem. You can search and filter by course etc.')
            ->with('creator_id',$creator_id)
            ->with('solver_id',$solver_id)
            ->with('is_approved',$is_approved)
            ->with('has_solutions',$has_solutions)
            ->with('has_approved_solution', $has_approved_solution);

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $courses = Course::all();
        return view('question/create')
            ->with('editMode',false)
            ->with('courses',$courses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateQuestionRequest $request)
    {
        // Add the Creator to the Request
        $request->merge(array('creator_id' => $request->user()->id));

        // Try to Create the New Question in the Database
        $newQ = Question::create($request->all());

        // Make Sure to add the related Course
        // TODO: Convert to allow insertion of multiple courses
        if($request->has('courses') && $request->input('courses') != 0){
            $newQ->courses()->attach($request->input('courses'));
            $newQ = Question::find($newQ->id);
            $newQ->touch();
        }

        // If successful, return
        if($newQ){

            // Fire the Question Was Created Event
            event(new QuestionWasCreated($newQ));


            if($request->ajax()){
                return "success";
            }else{
                /*
                 * Send them to My Questions Page
                 */
                return redirect('user/'.$request->user()->id.'/question');
            }
        }
        return back()->withInput()->withErrors($newQ->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $request->flash();
        $question = Question::with('creator','reviewer')->findOrFail($id);
        $html_generator = new HTMLGenerator();
        $actions = $html_generator->getActionsForQuestions($question);

        if($question->images){
            $images = json_decode($question->images);
        }else{
            $images = "";
        }

        if($request->ajax()){
            return response()->json([
                'question' => $question,
                'images' => $images,
                'actions' => $actions]
            );
        }

        return view('question/show',[
            'question' => $question,
            'actions' => $actions,
            'images' => $images,
            'user' => $request->user(),
            'html_generator' => $html_generator
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $question = Question::find($id);

        if($question->images){
            $images = json_decode($question->images);
        }else{
            $images = "";
        }

        $html_generator = new HTMLGenerator();

        return $this->create()
            ->with('editMode',true)
            ->with('question',$question)
            ->with('images',$images)
            ->with('html_generator', $html_generator)
            ->with('actions', $html_generator->getActionsForQuestions($question));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateQuestionRequest $request, $id)
    {

        $success = false;

        $question = Question::findOrFail($id);

        /*
         * Admins can update anything they want
         */
        if($request->user()->isAdmin()){
            $success = $question->update($request->except('_token','_method','course'));
        }

        /*
         * Other's can only update title, body, images and courses
         */
        if($request->has('title')){
            $question->title = $request->input('title');
        }

        if($request->has('body')) {
            $question->body = $request->input('body');
        }

        if($request->has('images')) {
            $question->images = $request->input('images');
        }

        if($request->has('course')){
            $question->courses()->detach();
            $question->courses()->attach($request->input('course'));
        }

        /*
         * Commit to Database
         */
        $success = $question->save();

        /*
         * If AJAX Request, Send Back JSON
         */
        if($request->ajax()){
            return response()->json([
                'success' => $success
            ]);
        }

        /*
         * If POST Request, Send to Question Display Page Once Updated.
         */
        if($success){
            return redirect('question/'.$id);
        }else{
            return back()->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(DeleteQuestionRequest $request, $id)
    {

        $success = false;
        $question = Question::findOrFail($id);
        $success = $question->delete();

        /*
         * If AJAX Request, Send Back JSON
         */
        if($request->ajax()){
            return response()->json([
                'success' => $success
            ]);
        }

        /*
         * If not AJAX request, Send to Home Page Once Deleted.
         */
        if($success){
            return redirect('/');
        }else{
            return back()->with('status','Unable to Delete Question');
        }

    }


}
