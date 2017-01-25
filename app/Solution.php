<?php

namespace App;

use App\Services\Notification;
use Illuminate\Database\Eloquent\Model;
use Request;
use Config;
use Storage;
use Symfony\Component\Debug\Exception\FatalErrorException;

class Solution extends Model
{

    /**
     * The database table that the Model uses
     * @var string
     */
    protected $table = "solutions";

    /**
     * The fields that are mass assignable
     * @var array
     */
    protected $fillable = ['file','question_id','ready_for_review','creator_id','reviewer_id'];

    /*
     * List of Relationships to be touched
     */
    protected $touches = ['question'];


    protected $appends = ['code'];


    /*
     * Appended Attributes
     */
    public function getCodeAttribute(){
        return trim(Storage::get($this->file));
    }


    /*
     *
     * RELATIONSHIPS SECTION -------------------------------------------------------
     */


    /*
     * There exists a many to one relationship between the solution and the question
     * This question is the question associated with this solution
     */
    public function question(){
        return $this->hasOne('App\Question','id','question_id');
    }

    /**
     * There exists a many to one relationship between the Solution and User
     * This user is the creator of the solution
     *
     * @method void
     *
     */
    public function creator(){
        return $this->hasOne('App\User','id','creator_id');
    }

    /**
     * There exists a many to one relationship between the Solution and User
     * This user is the reviewer of the solution
     * The reviewer of the Solution will always be an admin
     * If an Admin is the creator, then the reviewer is also the same admin
     *
     * @method void
     */
    public function reviewer(){
        return $this->hasOne('App\User','id','reviewer_id');
    }


    /*
     * RELATIONSHIPS SECTION END -------------------------------------------------------
     */

    public function isApproved(){
        return ($this->reviewer_id>0);
    }


    public function getFileContents(){
        return trim(Storage::get($this->file));
    }

    public function getAnswer($request){
//        /*
//         * Make sure the code has no syntax errors
//         */
//        $noError = $this->php_syntax_error($this->getFileContents());
//        if($noError != false){
//           return $noError;
//        }

        /*
        * Get variables from request and assign to $v1 (for compatibality with existing code
        */
        for ($y = 1; $y < Config::get('constants.max_input_variables'); $y++) {
            if ($request->has("var" . $y)) {
                $cmd = "\$v" . $y . " = \$request->input('var" . $y . "');";
                eval($cmd);
            }
        }

        /*
         * Only include is working inside ob_start()
         * eval($code) is not working
         * Therefore, I am forced to download the file and save it to disk and then evaluate it.
         * TODO:: Get the Code to run safely directly from S3
         */
        $code = Storage::get($this->file);
        Storage::disk('local')->put($this->file, $code);

        /*
         * Check for Syntax Errors
         */
        $syntax_check_result = shell_exec('php -l '.storage_path('app/'.$this->file));
        if(strpos($syntax_check_result,'No syntax errors detected') === false){
            $notifications_client = new Notification();
            $message = 'Syntax Error Found';
            $notifications_client->add_notification($message,'Warning');
            return $message;
        }

        try{
            /*
             * Compute the Solution
             */
            ob_start();
            include_once(storage_path()."/app/solutions/common_functions.php");
            include(storage_path()."/app/".$this->file);
            $answer = ob_get_contents();
            ob_end_clean();

            return $answer;
        }
        catch(\Exception $e){
            return $e->getMessage();
        }

    }


    //TODO: Convert to Scope
    public static function getApprovedSolutions(){
        return Solution::where('reviewer_id','>',0);
    }


    /**
     * Check the syntax of some PHP code.
     * TODO: Implement Function
     * @param string $code PHP code to check.
     * @return boolean|array If false, then check was successful, otherwise an array(message,line) of errors is returned.
     */
    private function php_syntax_error($code){
        return false;
    }


}
