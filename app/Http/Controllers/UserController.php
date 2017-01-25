<?php

namespace App\Http\Controllers;

use App\Helpers\ViewHelper;
use App\HTMLGenerator;
use App\Jobs\ChangeNumberOfFollowers;
use App\QuestionUsageRecord;
use App\Solution;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Question;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function showQuestions(Request $request, $user_id){
        $query_string = "";
        $page_number = 0;
        $course_number = null;
        $creator_id = $user_id;
        $solver_id = null;
        $has_solutions = "";
        $has_approved_solution = "";

        return view('admin.execute')
            ->with('query',$query_string)
            ->with('page_number',$page_number)
            ->with('course_number',$course_number)
            ->with('title','Questions')
            ->with('creator_id',$creator_id)
            ->with('solver_id',$solver_id)
            ->with('has_solutions',$has_solutions)
            ->with('has_approved_solution', $has_approved_solution);
    }


    public function showSolutions(Request $request, $user_id){

        /*
         * Find the Questions that the User Has Submitted a Solution for
         */
        $questions = Question::whereHas('solutions',function($query) use ($user_id){
            $query->where('creator_id',$user_id);
        });


        /*
         * Execute the Query and get the Questions
         */
        $questions = $questions->paginate(10);

        /*
         * Return View
         */
        return ViewHelper::getResponseToShowQuestions($request, $questions,'My Solutions');
    }

    /*
     * Show List of All Users
     */
    public function index(Request $request){
        return view('user/index')
            ->with('heading','All Users')
            ->with('users',User::all());
    }


    /*
     * Show List of All Admins
     */
    public function getAdmins(Request $request){
        return view('user/index')
            ->with('heading','Admins')
            ->with('users',User::getAdmins()->get());
    }


    /*
     * Show List of All Managers
     */
    public function getManagers(Request $request){
        return view('user/index')
            ->with('heading','Managers')
            ->with('users',User::getManagers()->get());
    }

    /*
     * Show List of All Editors
     */
    public function getEditors(Request $request){
        return view('user/index')
            ->with('heading','Editors')
            ->with('users',User::getEditors()->get());
    }

    /*
     * Show List of All Students
     */
    public function getStudents(Request $request){
        return view('user/index')
            ->with('heading','Students')
            ->with('users',User::getStudents()->get());
    }

    /*
     * Show the List of Questions that the User got the solution to on the website
     */
    public function getUsedQuestions(Request $request, $user_id){
        $usage_records = QuestionUsageRecord::where('user_id',$user_id)->with(['user','question'])->get();
        return view('user.history')->with('usage_records',$usage_records);
    }


    /*
     * Show the User's Summary
     */
    public function show(Request $request, $id){

        /*
         * Retrive the User
         */
        $user = User::find($id);

        /*
         * Display the User
         */
        return view('user/show')->with('user',$user);

    }


    /*
     * Update User
     */
    public function update(Request $request, $id){
        /*
         * Find the User or Fail
         */
        $user = User::findOrFail($id);

        /*
         * Update if Admin or Request is from user to be edited
         */
        if($request->user()->id == $user->id || $request->user()->isAdmin()){

            /*
             * Directly Update all but Password needs to be encrypted
             */
            if($request->input('name') == 'password'){
                $user->setAttribute($request->input('name'),bcrypt($request->input('value')));
            }else{
                $user->setAttribute($request->input('name'),$request->input('value'));
            }
            $user->save();

        }

        /*
         * Return Response
         */
        return response()->json(['success' => true],200);

    }

    /*
     * followQuestion
     */
    public function followQuestion(Request $request, $question_id){
        /*
         * Create Entry in Database (if it doesn't already exist)
         */
        if(!$request->user()->following->contains($question_id)){
            $request->user()->following()->attach($question_id);
        }

        /*
         * Update the Value in the Question Database
         * TODO: Make this happen async
         */
        Question::where('id',$question_id)->increment('num_followers');

        /*
         * Return Response
         */
        return response()->json([
            'success' => true,
            'message' => 'Started Following Question'
        ],200);
    }

    /*
     * Unfollow Question
     */
    public function stopFollowingQuestion(Request $request, $question_id){

        /*
         * Remove Entry from Database if it exists
         */
        if($request->user()->following->contains($question_id)) {
            $request->user()->following()->detach($question_id);
        }

        Question::where('id',$question_id)->decrement('num_followers');

        /*
         * Return Response
         */
        return response()->json([
            'success' => true,
            'message' => 'Stopped following Question'
        ],200);
    }

}
