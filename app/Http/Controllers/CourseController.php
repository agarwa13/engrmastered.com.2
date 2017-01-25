<?php

namespace App\Http\Controllers;

use App\HTMLGenerator;
use App\Services\Notification;
use Illuminate\Http\Request;
use App\Course;
use App\University;
use App\Http\Requests;
use App\Question;
use Auth;
use App\Http\Controllers\Controller;
use App\Helpers\ViewHelper;

class CourseController extends Controller
{

    /*
     * Assign Middleware to Methods
     */
    public function __construct(){
        $this->middleware('auth',['except' =>[
            'index',
            'show',
            'showUnsolvedQuestions',
            'showSolvedQuestions',
            'showApprovedQuestions'
        ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /*
         * Display all the courses
         */

        $courses = Course::all();
        $universities = University::all();

        return view('course/index',[
            'courses' => $courses,
            'universities' => $universities
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //TODO: Implement Function
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $course = Course::create([
            'name' => $request->input('name'),
            'acronym' => $request->input('acronym'),
            'instructor' => $request->input('instructor'),
            'university_id' => $request->input('university_id'),
            'creator_id' => $request->user()->id
        ]);

        if($request->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Course '.$course->name.' successfully created.'
            ]);
        }else{
            return $this->index($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {

        $query_string = "";
        $page_number = 0;
        $course_number = $id;
        $creator_id = null;
        $solver_id = null;
        $is_approved = "";
        $has_solutions = "";
        $has_approved_solution = "true";

        $course = Course::findorfail($id);

        return view('question.index')
            ->with('query',$query_string)
            ->with('page_number',$page_number)
            ->with('course_number',$course_number)
            ->with('title','Questions and Answers from '. $course->acronym . " at " . $course->university->acronym . " |")
            ->with('description','Solve all your homework problems from ' . $course->acronym . " within 1 hour!. Plug in the questions from WebAssign or SmartPhysicss and copy the answers back. It is as easy as that.")
            ->with('creator_id',$creator_id)
            ->with('solver_id',$solver_id)
            ->with('is_approved',$is_approved)
            ->with('has_solutions',$has_solutions)
            ->with('has_approved_solution', $has_approved_solution);

//        /*
//         * Find Course or throw Error
//         */
//        $course = Course::with('university','questions')->findorfail($id);
//
//        /*
//         * Figure out the Heading for the Page
//         */
//        $heading = 'Questions from '.$course->name;
//        if($course->university_id > 0){
//            $heading .= ' at '.$course->university->name;
//        }
//
//        /*
//         * Figure out the Title
//         */
//        $title = "Get Homework Solutions for " . $course->name . " (". $course->acronym .") at " . $course->university->name . " (" . $course->university->acronym . ")" ;
//
//        $questions = Question::whereHas('courses',function($query) use ($course){
//            $query->where('courses.id',$course->id);
//        });
//
//
//        /*
//         * Execute the Query and get the Questions
//         */
//        $questions = $questions->paginate(10);
//
//        /*
//         * Return View
//         */
//        return ViewHelper::getResponseToShowQuestions($request, $questions, $heading, $title);

    }


    /**
     * Display the Unsolved Questions
     *
     * @param int $id
     * @return Response
     */
    public function showUnsolvedQuestions(Request $request, $id){

        /*
         * Find Course or throw Error
         */
        $course = Course::with('university','questions')->findorfail($id);

        /*
         * Figure out the Heading for the Page
         */
        $heading = 'Unsolved Questions from '.$course->name;
        if($course->university_id > 0){
            $heading .= ' at '.$course->university->name;
        }

        /*
         * Figure out the Title
         */
        $title = "Get Homework Solutions for " . $course->name . " (". $course->acronym .") at " . $course->university->name . " (" . $course->university->acronym . ")" ;

        /*
         * Get the Unsolved Questions
         */
        $questions = $course->getUnsolvedQuestions();

        /*
         * Execute the Query and get the Questions
         */
        $questions = $questions->paginate(10);

        /*
         * Return View
         */
        return ViewHelper::getResponseToShowQuestions($request, $questions, $heading, $title);
    }

    /*
     * Display the Solved Questions
     *
     * @param int $id
     * @return Response
     */
    public function showSolvedQuestions(Request $request, $id){
        /*
         * Find the Course or throw Error
         */
        $course = Course::findorfail($id);

        /*
         * Figure out the Heading for the page
         */
        $heading = 'Solved Questions from '.$course->name;
        if($course->university_id > 0){
            $heading .= ' at '.$course->university->name;
        }

        /*
         * Figure out the Title
         */
        $title = "Get Homework Solutions for " . $course->name . " (". $course->acronym .") at " . $course->university->name . " (" . $course->university->acronym . ")" ;

        /*
         * Get the Solved Questions
         */
        $questions = $course->getSolvedQuestions();


        /*
         * Execute the Query and get the Questions
         */
        $questions = $questions->paginate(10);

        /*
         * Return View
         */
        return ViewHelper::getResponseToShowQuestions($request, $questions, $heading, $title);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $notification_client = new Notification();

        if($request->has('name')){
            $course->name = $request->input('name');
            $notification_client->add_notification('Name Updated');
        }

        if($request->has('acronym')){
            $course->acronym = $request->input('acronym');
            $notification_client->add_notification('Acronym Updated');
        }

        if($request->has('instructor')){
            $course->instructor = $request->input('instructor');
            $notification_client->add_notification('Instructor Updated');
        }

        if($request->has('reviewer_id')){
            $course->reviewer_id = $request->input('reviewer_id');
            $notification_client->add_notification('Course Approved');
        }

        $course->save();

        if($request->ajax()){
            return response()->json();
        }else{
            return $this->index($request);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $course = Course::findorfail($id);
        $course->delete();

        if($request->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Deleted Course '.$id.' Successfully'
            ]);
        }else{
            return redirect('course');
        }


        /*
         * Do we want to delete all the questions associated with this course?
         */

    }
}
