<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProblemEmail extends Model
{
    protected $table = 'problem_emails';

    protected $fillable = ['email_address','bounced','complained'];

    /**
     * @param int $num_bounces
     * @return $this
     */
    public function incrementBounceCount($num_bounces = 1){
        $this->bounced += $num_bounces;
        $this->save();

        $user = User::where('email',$this->email_address)->first();
        if($user != null){
            $user->email_bounced = true;
            $user->save();
        }

        return $this;
    }

    /**
     * @param int $num_complaints
     * @return $this
     */
    public function incrementComplainedCount($num_complaints = 1){
        $this->complained += $num_complaints;
        $this->save();

        $user = User::where('email',$this->email_address)->first();
        if($user != null){
            $user->email_complained = true;
            $user->save();
        }

        return $this;
    }

}
