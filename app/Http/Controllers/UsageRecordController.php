<?php

namespace App\Http\Controllers;

use App\QuestionUsageRecord;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsageRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usage_records = QuestionUsageRecord::all();

        if($request->ajax()){
            return response()->json(['usage_records' => $usage_records],200);
        }else{
            return view('usage_record.index')->with('usage_records',$usage_records);
        }

    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {

        /*
         * Only Record Owners and Admin's are authorized to view the record
         */
        $usage_record = QuestionUsageRecord::with('question')->findOrFail($id);

        $question = $usage_record->question;

        if($question->images){
            $images = json_decode($question->images);
        }else{
            $images = "";
        }

        $solution = $question->getApprovedSolution();

        $variables = json_decode($usage_record->variables_used, true);

        $request->merge($variables);

        $answer = $solution->getAnswer($request);

        if($request->user()->isAdmin() || $request->user()->id == $usage_record->user_id){
            return view('question.solution.show',[
                'question' => $question,
                'images' => $images,
                'answer' => $answer,
                'usage_record' => $usage_record
            ]);
        }else{
            abort(400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
