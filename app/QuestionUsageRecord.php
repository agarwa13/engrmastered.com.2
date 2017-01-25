<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionUsageRecord extends Model
{
    protected $table = 'question_user';


    protected $fillable = ['question_id','user_id','variables_used','tokens_paid','stripe_charge_id','stripe_charge_amount'];

    public function review(){
        return $this->hasOne('App\Review');
    }

    public function question(){
        return $this->belongsTo('App\Question');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

}
