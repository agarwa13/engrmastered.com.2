<?php

namespace App\Http\Requests\Questions;
use Auth;
use App\Http\Requests\Request;
use App\Question;

class DeleteQuestionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /*
         * Allow Administrator's to delete resources
         */
        if(Auth::user()->isAdmin()){
            return true;
        }

        /*
         * Allow the Creator of the Question to Delete if it has not been reviewed yet
         */
        $question = Question::findOrFail($this->route('question'));
        if(!$question->isApproved() && Auth::id() == $question->creator_id){
            return true;
        }

        /*
         * Otherwise do not allow the question to be deleted
         */
        return false;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
