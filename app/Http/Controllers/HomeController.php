<?php
/**
 * Created by PhpStorm.
 * User: nikhilagarwal
 * Date: 8/23/15
 * Time: 8:24 AM
 */

namespace App\Http\Controllers;
use App\Helpers\ViewHelper;
use App\Services\Braintree;
use Illuminate\Http\Request;
use App\Question;
use App\Services\SiteMap;
use Auth;

class HomeController extends Controller{

    public function getWriteSolutionsGuide(){
        return view('info.write_solution_instruction');
    }

    public function getFindHomeworkSolutions(){
        return view('info.find_homework_solutions');
    }

    public function getSearchResults(Request $request){
//        return view('info.search-results');

        $query_string = $request->q;
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
            ->with('title','Questions and Answers related to ' . $request->q . " |")
            ->with('description','Solve all your homework problems effortlessly. Get answers instantly that you can copy into WebAssign, SmartPhysics and other college homework platforms')
            ->with('creator_id',$creator_id)
            ->with('solver_id',$solver_id)
            ->with('is_approved', $is_approved)
            ->with('has_solutions',$has_solutions)
            ->with('has_approved_solution', $has_approved_solution);

    }

    public function getAskQuestions(){
        return view('info.ask_questions');
    }

    public function getAnswerQuestions(){
        return view('info.answer_questions');
    }

    public function getCopyright(){
        return view('legal.copyright');
    }

    public function getHome(Request $request){
        return view('welcome');
    }

    public function siteMap(SiteMap $siteMap){
        $map = $siteMap->getSiteMap();
        return response($map)->header('Content-type','text/xml');
    }

    public function getTermsAndConditions(){
        return view('legal/termsandconditions');
    }

    public function getPrivacyPolicy(){
        return view('legal/privacypolicy');
    }

    public function getHonorCode(){
        return view('legal/honorcode');
    }

    public function getFAQ(){
        return view('legal/faq');
    }



} 