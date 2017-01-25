<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Solution;
use App\Services\Billable;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Billable;

    /*
     * Dates
     */
    protected $dates = ['trial_ends_at', 'subscription_ends_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'tokens_remaining', 'tokens_used', 'role_id', 'university_id', 'reputation', 'income', 'income_redeemed'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];



    /**
     * There is a one to many relationship between roles and users.
     * Each user has an associated role
     * This function retrieves the User's role
     *
     */
    public function role(){
        return $this->belongsTo('App\Role');
    }


    public function createdQuestions(){
        return $this->hasMany('App\Question','creator_id');
    }


    public function is_paying_user_for(Question $question){

        // Creator's of Approved Solutions get the Solution for Free
        if($question->hasApprovedSolution() && $question->getApprovedSolution()->creator_id == $this->id){
            return false;
        }

        // Admin's get the Solution for Free
        if($this->isAdmin()){
            return false;
        }

        // Everyone else pays
        return true;
    }


    /*
     * There is a many to many relationship between users and questions.
     * These are the questions that the user has been provided a solution for
     */
    public function usedQuestions(){
        return $this->belongsToMany('App\Question')->withTimestamps();;
    }

    public function usageRecords(){
        return $this->hasMany('App\QuestionUsageRecord');
    }

    public function hasSolutionForQuestion($question){
        return (Solution::where('creator_id',$this->id)->where('question_id',$question->id)->count() > 0);
    }

    /**
     * Increasing the Number of Credits
     * These are credits that the user can use to solve questions on the website
     * @param $number_of_tokens
     * @return $this
     */
    public function increment_tokens($number_of_tokens){
        $this->tokens_remaining += $number_of_tokens;
        $this->save();
        return $this;
    }

    /**
     * Increases the Income that the User has earned
     * This is real dollars that can be paid out to the user
     * @param $amount
     * @return $this
     */
    public function increment_income($amount){
        $this->income += $amount;
        $this->save();
        return $this;
    }


    /*
     * Questions that the User is Following
     */
    public function following(){
        return $this->belongsToMany('App\Question','following')->withTimestamps();
    }

    /**
     * Various Functions to Understand Permissions of User
     */
    public function isAdmin(){
        return ($this->role->id >= 4);
    }

    public function isManager(){
        return ($this->role->id >= 3);
    }

    public function isEditor(){
        return ($this->role->id >= 2);
    }

    public function isStudent(){
        return ($this->role->id >= 1);
    }

    public static function getStudents(){
        return User::where('role_id',1);
    }

    public static function getEditors(){
        return User::where('role_id',2);
    }

    public static function getManagers(){
        return User::where('role_id',3);
    }

    public static function getAdmins(){
        return User::where('role_id',4);
    }

    /*
     * Checks if the user has submitted a solution for the given question
     */
    public function getUsersSolution($questionId){
        $solution = Solution::where('question_id',$questionId)->where('creator_id',$this->id)->first();
        return $solution;
    }

    public function getMyApprovedQuestions(){
        return Question::getApprovedQuestions()->where('creator_id',$this->id);
    }

    public function getMyApprovedSolutions(){
        return Solution::getApprovedSolutions()->where('creator_id',$this->id);
    }

    /*
     * Get User Profile
     */
    public function getProfile(){
        $profile = array();
        $profile['questionsApproved'] = count($this->getMyApprovedQuestions()->get());
        $profile['solutionsApproved'] = count($this->getMyApprovedSolutions()->get());
        return $profile;
    }


    /*
     * Get Guest User
     */
    public static function getGuestUser(){
        return User::find(50);
    }


}
