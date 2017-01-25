<?php

namespace App;

use App\Services\ElasticSearchClient;
use Illuminate\Database\Eloquent\Model;
use Log;
use DB;
use Config;

class Question extends Model
{

    /**
     * The database table that the Model uses
     * @var string
     */
    protected $table = "questions";

    /**
     * The fields that are mass assignable
     * @var array
     */
    protected $fillable = ['title','body','images','quality_score','deactivated','creator_id','reviewer_id'];

    /*
     * We have a calculated field (body alternate) that we want to access
     */
    protected $appends = ['simple_body','raw_body'];

    public function getRawBodyAttribute(){
        return $this->attributes['body'];
    }

    public function getSimpleBodyAttribute(){
        $main_body = $this->attributes['body'];

        // Replace all the fields including repeat fields with a blank
        $main_body =  preg_replace_callback('/{{([^},]+)(?:,([^}]+))?}}/', function($_) {
            return '___';
        }, $main_body);

        return $main_body;
    }

    public function getBodyAttribute($main_body){

        // Replace all the non-repeat fields
        $main_body =  preg_replace_callback('/{{([^}r,]+)(?:,([^}]+))?}}/', function($_) {
            return '<input size="5" class="question-input" type="text" name="var'.$_[1].'"' . (isset($_[2]) ? ' value="'.$_[2]. '"' : '') . '>';
        }, $main_body);

        // Replace all the repeat fields
        $main_body =  preg_replace_callback('/{{([^},]+)(?:,([^}]+))?}}/', function($_) {
            $without_r = substr($_[1], 0, -1);
            return '<input size="5" type="text" class="repeated-question-input repeated-var'.$without_r.'" disabled>';
        }, $main_body);

        return $main_body;
    }

    /**
     * Images is stored as serialized json.
     * So we cast it to a PHP array.
     * See: http://laravel.com/docs/5.1/eloquent-mutators#attribute-casting
     */
    protected $casts = [
        'images' => 'array',
        'keywords' => 'array'
    ];


    public function courses(){
        return $this->belongsToMany('App\Course')->withTimestamps();
    }

    /**
     * There exists a many to one relationship between the Course and User
     * This user is the creator of the course
     *
     * @method void
     *
     */
    public function creator(){
        return $this->hasOne('App\User','id','creator_id');
    }

    /**
     * There exists a many to one relationship between the Course and User
     * This user is the reviewer of the course
     * The reviewer of the Course will always be an admin
     * If an Admin is the creator, then the reviewer is also the same admin
     *
     * @method void
     */

    public function reviewer(){
        return $this->hasOne('App\User','id','reviewer_id');
    }


    /**
     * There exists a many to one relationship between solutions and questions
     * Note there is only one approved solution but many solutions
     */
    public function solutions(){
        return $this->hasMany('App\Solution');
    }

    public function reviews(){
        return $this->hasMany('App\Review');
    }

    public function reviewsCount(){
        return $this->hasOne('App\Review')
            ->selectRaw('question_id, count(*) as aggregate')
            ->groupBy('question_id');
    }

    public function getReviewCountAttribute(){
        if(!$this->relationLoaded('reviewsCount')){
            $this->load('reviewsCount');
        }

        $related = $this->getRelation('reviewsCount');
        return ($related) ? (int) $related->aggregate : 0;
    }

    public function positiveReviewsCount(){
        return $this->hasOne('App\Review')
            ->where('positive_review',true)
            ->selectRaw('question_id, count(*) as aggregate')
            ->groupBy('question_id');
    }

    public function getPositiveReviewsCount(){
        if(!$this->relationLoaded('positiveReviewsCount')){
            $this->load('positiveReviewsCount');
        }

        $related = $this->getRelation('positiveReviewsCount');
        return ($related) ? (int) $related->aggregate : 0;
    }

    public function negativeReviewsCount(){
        return $this->hasOne('App\Review')
            ->where('positive_review',false)
            ->selectRaw('question_id, count(*) as aggregate')
            ->groupBy('question_id');
    }

    public function getNegativeReviewsCount(){
        if(!$this->relationLoaded('negativeReviewsCount')){
            $this->load('negativeReviewsCount');
        }

        $related = $this->getRelation('negativeReviewsCount');
        return ($related) ? (int) $related->aggregate : 0;
    }

    public function refundRequestsCount(){
        return $this->hasOne('App\Review')
            ->where('refund_requested',true)
            ->selectRaw('question_id, count(*) as aggregate')
            ->groupBy('question_id');
    }

    public function getRefundRequestsCount(){
        if(!$this->relationLoaded('refundRequestsCount')){
            $this->load('refundRequestsCount');
        }

        $related = $this->getRelation('refundRequestsCount');
        return ($related) ? (int) $related->aggregate : 0;
    }

    /*
     * Returns a List of Users following this question
     */
    public function followedBy(){
        return $this->belongsToMany('App\User','following')->withTimestamps();
    }

    /**
     * Added a column so we do not need to count from the database each time
     * @return mixed
     */
    public function followedByCount(){
        return $this->num_followers;
    }

    /*
     * There exists a many to many relationship between users and questions
     * These are the users that have solved this question
     */
    public function usedBy(){
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function usageRecords(){
        return $this->hasMany('App\QuestionUsageRecord');
    }



    public function approved_solution(){
        return $this->belongsTo('App\Solution','approved_solution_id');
    }

    /**
     * Many Solutions Exist for each question.
     * This returns the approved solution if any
     */
    public function getApprovedSolution(){
//        return Solution::where('question_id',$this->id)->where('reviewer_id','>',0)->first();
        return $this->approved_solution;
    }

    /*
     * Returns if the Question has an Approved Solution or Not
     */
    public function hasApprovedSolution(){
        return $this->has_approved_solution;
    }

    /*
     * Returns if the Question is Approved or Not
     */
    public function isApproved(){
        return ($this->reviewer_id > 0);
    }



    /*
     * Returns if a Question has Unapproved Solutions available to review
     */
    public function hasUnapprovedSolutions(){
        return $this->has_solutions;
    }


    public function isMarkedForReview(){
        return $this->review_later;
    }

    /*
     * Returns a List of Questions that have not been approved
     */
    public static function getUnapprovedQuestions(){
        return Question::with('courses.university','usageRecords')->where('reviewer_id','<',1)->orwhere('reviewer_id',null);
    }

    /*
     * Returns a List of Questions that have been approved
     */
    public static function getApprovedQuestions(){
        return Question::with('courses.university','usageRecords')->where('reviewer_id','>',0);
    }

    /*
     * Returns a List of Questions that do not have an approved solution
     * but are approved themselves.
     */
    public static function getApprovedUnsolvedQuestions(){
        return Question::getApprovedQuestions()->whereDoesntHave('solutions',function($query){
            $query->where('reviewer_id','>',0);
        });
    }

    /*
     * Returns a List of Questions that do not have an approved solution
     * but are approved themselves.
     */
    public static function getUnsolvedQuestions(){
        return Question::with('courses.university','usageRecords')->whereDoesntHave('solutions',function($query){
            $query->where('reviewer_id','>',0);
        });
    }

    /*
     * Returns a List of Questions that have an approved solution
     * and are approved themselves.
     */
    public static function getApprovedSolvedQuestions(){
        return Question::getApprovedQuestions()->whereHas('solutions',function($query){
            $query->where('reviewer_id','>',0);
        });
    }

    /*
     * Returns a List of Approved Questions that do not have an approved solution but have some solutions submitted.
     */
    public static function getApprovedQuestionsWithUnapprovedSolutions(){
        return Question::getApprovedQuestions()->whereHas('solutions',function($query){
            $query->where('ready_for_review',1);
        })->whereDoesntHave('solutions',function($query){
            $query->where('reviewer_id','>',0);
        });
    }

    /*
     * Returns a List of Approved Questions that do not have any solutions submitted
     */
    public static function getApprovedQuestionsWithoutAnySolutions(){
        return Question::getApprovedQuestions()->whereDoesntHave('solutions');
    }


    public static function getQuestionsForLaterReview(){
        return Question::with('courses.university','usageRecords')->where('review_later',true);
    }



}
