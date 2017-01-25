<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The database table used by the Model
     * @var string
     */

    protected $table = "roles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role'];

    /**
     * There is a one to many relationship between roles and users.
     * Each user has an associated role
     * This function retrieves the users that have this role
     */
    public function users(){
        return $this->hasMany('App\User');
    }

}
