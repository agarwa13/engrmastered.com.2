<?php

namespace App\Http\Requests\Questions;

use App\Http\Requests\Request;
use App\Question;
use Auth;

class UpdateQuestionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        /*
         * Admin is Permitted
         */
        if(Auth::user()->isAdmin()){
            return true;
        }

        /*
         * Creator is permitted if Question is not yet approved
         */
        $question = Question::findOrFail($this->route('question'));
        if(!$question->isApproved() && Auth::id() == $question->creator_id){
            return true;
        }

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
            'title' => 'sometimes|max:255',
            'creator_id' => 'sometimes|exists:users,id',
            'reviewer_id' => 'sometimes|exists:users,id',
            'solution_creator_id' => 'sometimes|exists:users,id'
        ];
    }
}
