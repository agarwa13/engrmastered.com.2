<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreReviewRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_id' => 'required|exists:questions,id',
            'positive_review' => 'required|boolean',
            'refund_requested' => 'required|boolean',
            'comment' => 'string|max:1000'
        ];
    }
}
