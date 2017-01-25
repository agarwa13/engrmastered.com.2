<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $table = "reviews";

    protected $fillable = ['user_id','question_id','positive_review','comment'];

    public function question(){
        return $this->belongsTo('App\Question');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function refund_authorizer(){
        return $this->belongsTo('App\User','refund_authorized_by');
    }

    public function question_usage_record(){
        return $this->belongsTo('App\QuestionUsageRecord','question_usage_record_id');
    }

}