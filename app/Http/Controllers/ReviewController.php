<?php

namespace App\Http\Controllers;

use App\Question;
use App\Review;
use App\Services\Notification;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     * Grouped by Questions
     * Sorted by the Questions that have the most number of negative reviews
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $reviews = Review::with(['question','user','refund_authorizer','question_usage_record'])->get();
//        $reviews = [];
        return view('review.index')->with('reviews',$reviews);
    }

    public function getQuestionsWithReviews()
    {
        $sql = "SELECT question_id, sum(refund_requested) as `refunds_requested` FROM `reviews` group by `question_id` order by `refunds_requested` desc";
        $ids = array();
        $question_ids_with_count = DB::select($sql);
        foreach($question_ids_with_count as $question){
            array_push($ids, $question->question_id);
        }
        $questions = Question::whereIn('id',$ids)->get();

        return view('review.summary_by_questions')->with('questions',$questions);
    }

    public function getQuestionsWithRefundRequestsPendingReview(){

        $sql = "SELECT question_id, sum(refund_requested) as `refunds_requested` FROM `reviews` WHERE `refund_request_reviewed` = 0 group by `question_id` order by `refunds_requested` desc";
        $ids = array();
        $question_ids_with_count = DB::select($sql);
        foreach($question_ids_with_count as $question){
            array_push($ids, $question->question_id);
        }
        $questions = Question::whereIn('id',$ids)->get();

        return view('review.summary_by_questions')->with('questions',$questions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\StoreReviewRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StoreReviewRequest $request)
    {
        $review = new Review;
        $review->user_id = $request->user()->id;
        $review->question_id = $request->input('question_id');
        $review->question_usage_record_id = $request->input('question_usage_record_id');
        $review->positive_review = $request->input('positive_review');
        $review->refund_requested = $request->input('refund_requested');
        $review->refund_authorized_by = null;
        $review->comment = $request->input('comment');
        $review->save();

        if($request->ajax()){
            return response()->json(['message','Successfully added review'],200);
        }

        return view('review.completed')->with('positive_review',$request->input('positive_review'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $review = Review::findOrFail($id);
        return view('review.show')->with('review',$review);
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
        $review = Review::findOrFail($id);

        // How to Process the Refund
        if($review->refund_requested && $request->has('refund_authorized_by')){
            $review->refund_authorized_by = $request->input('refund_authorized_by');
        }

        $review->save();

        if($request->ajax()){
            return response()->json(['message' => 'Review Updated'],200);
        }else{
            return "Not Yet Implemented";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification, $id)
    {
        $review = Review::find($id);
        $review->delete();
        $notification->add_notification('Review Deleted');
        return response()->json([]);
    }

}
