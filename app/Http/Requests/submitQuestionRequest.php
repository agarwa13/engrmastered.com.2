<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class submitQuestionRequest extends Request
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
            'var1' => 'sometimes|required_without:ready_for_review',
            'var2' => 'sometimes|required_without:ready_for_review',
            'var3' => 'sometimes|required_without:ready_for_review',
            'var4' => 'sometimes|required_without:ready_for_review',
            'var5' => 'sometimes|required_without:ready_for_review',
            'var6' => 'sometimes|required_without:ready_for_review',
            'var7' => 'sometimes|required_without:ready_for_review',
            'var8' => 'sometimes|required_without:ready_for_review',
            'var9' => 'sometimes|required_without:ready_for_review',
            'var10' => 'sometimes|required_without:ready_for_review',
            'var11' => 'sometimes|required_without:ready_for_review',
            'var12' => 'sometimes|required_without:ready_for_review',
            'var13' => 'sometimes|required_without:ready_for_review',
            'var14' => 'sometimes|required_without:ready_for_review',
            'var15' => 'sometimes|required_without:ready_for_review',
            'var16' => 'sometimes|required_without:ready_for_review',
            'var17' => 'sometimes|required_without:ready_for_review',
            'var18' => 'sometimes|required_without:ready_for_review',
            'var19' => 'sometimes|required_without:ready_for_review',
            'var20' => 'sometimes|required_without:ready_for_review',
            'var21' => 'sometimes|required_without:ready_for_review',
            'var22' => 'sometimes|required_without:ready_for_review',
            'var23' => 'sometimes|required_without:ready_for_review',
            'var24' => 'sometimes|required_without:ready_for_review',
            'var25' => 'sometimes|required_without:ready_for_review',
            'var26' => 'sometimes|required_without:ready_for_review',
            'var27' => 'sometimes|required_without:ready_for_review',
            'var28' => 'sometimes|required_without:ready_for_review',
            'var29' => 'sometimes|required_without:ready_for_review',
            'var30' => 'sometimes|required_without:ready_for_review',
            'var31' => 'sometimes|required_without:ready_for_review',
            'var32' => 'sometimes|required_without:ready_for_review',
            'var33' => 'sometimes|required_without:ready_for_review',
            'var34' => 'sometimes|required_without:ready_for_review',
            'var35' => 'sometimes|required_without:ready_for_review',
            'var36' => 'sometimes|required_without:ready_for_review',
            'var37' => 'sometimes|required_without:ready_for_review',
            'var38' => 'sometimes|required_without:ready_for_review',
            'var39' => 'sometimes|required_without:ready_for_review',
            'var40' => 'sometimes|required_without:ready_for_review'
        ];
    }
}
