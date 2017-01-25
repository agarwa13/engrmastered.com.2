<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Question;

class Course extends Model
{
    /**
     * The database table used by the model
     * @var string
     */
    protected $table  = "courses";

    /**
     * The fields that can be mass assigned
     * @var array
     */
    protected $fillable = ['name', 'instructor', 'acronym', 'university_id', 'creator_id', 'reviewer_id'];

    /*
     * List of Relationships to be touched
     */
    protected $touches = ['questions','university'];

    /*
     * TODO: Convert to Scope
     */
    public function getUnsolvedQuestions(){
        return Question::getApprovedUnsolvedQuestions()->whereHas('courses',function($query){
            $query->where('courses.id',$this->id);
        });
    }

    /*
     * TODO: Convert to Scope
     */
    public function getSolvedQuestions(){
        return Question::getApprovedSolvedQuestions()->whereHas('courses',function($query){
            $query->where('courses.id',$this->id);
        });
    }

    /*
     * TODO: Convert to Scope
     */
    public function getApprovedQuestions(){
        return Question::getApprovedQuestions()->whereHas('courses',function($query){
            $query->where('courses.id',$this->id);
        });
    }

    /*
     * RELATIONSHIPS SECTION ----------------------------------------------------------
     */

    /*
     * There exists a many to many relationship between courses and questions
     */
    public function questions(){
        return $this->belongsToMany('App\Question')->withTimestamps();
    }

    /**
     * There exists a many to one relationship between the Course and User
     * This user is the creator of the course
     *
     * @method void
     *
     */
    public function creator(){
        return $this->hasOne('App\User','creator_id');
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
        return $this->belongsTo('App\User','reviewer_id');
    }

    /**
     * There exists a one to many relationship between the University and the Course
     * This university is where the course is held
     * Courses may float i.e. not be associated to any university
     *
     * @method void
     */
    public function university(){
        return $this->belongsTo('App\University');
    }


    /*
     * RELATIONSHIPS SECTION END----------------------------------------------------------
     */

    /**
     * This method is an accessor. It automatically changes the acronym to be all capitals
     * regardless of how it is stored in the database.
     * See: http://laravel.com/docs/5.1/eloquent-mutators#accessors-and-mutators
     * @param $value (String from Database)
     * @return string (Capitalized String)
     */
    public function getAcronymAttribute($value){
        return strtoupper($value);
    }
}
