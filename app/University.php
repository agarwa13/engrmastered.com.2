<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    /**
     * The database table used by the model
     * @var string
     */
    protected $table = "universities";

    /**
     * The fields that can be mass assigned
     * name = Name of the University (Example: University of Illinois at Urbana Champaign)
     * acronym = Acronym of the University (Example: UIUC)
     * creator_id = Id of User that created the University
     * reviewer_id = Id of User that reviewed and approved the University
     *
     * Universities will not be displayed to users without admin role unless they have been reviewed.
     *
     * @var array
     */
    protected $fillable = ['name','acronym','creator_id','reviewer_id'];

    public function creator(){
        return $this->belongsTo('App\User','creator_id');
    }


    public function reviewer(){
        return $this->belongsTo('App\User','reviewer_id');
    }

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
