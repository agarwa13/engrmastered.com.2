<?php

namespace App\Http\Middleware;
use Validator;
use Closure;

class BackIfQuestionNotFilledOut
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($request->all(), [
            'var1' => 'sometimes|required_if:request_type,check_solution',
            'var2' => 'sometimes|required_if:request_type,check_solution',
            'var3' => 'sometimes|required_if:request_type,check_solution',
            'var4' => 'sometimes|required_if:request_type,check_solution',
            'var5' => 'sometimes|required_if:request_type,check_solution',
            'var6' => 'sometimes|required_if:request_type,check_solution',
            'var7' => 'sometimes|required_if:request_type,check_solution',
            'var8' => 'sometimes|required_if:request_type,check_solution',
            'var9' => 'sometimes|required_if:request_type,check_solution',
            'var10' => 'sometimes|required_if:request_type,check_solution',
            'var11' => 'sometimes|required_if:request_type,check_solution',
            'var12' => 'sometimes|required_if:request_type,check_solution',
            'var13' => 'sometimes|required_if:request_type,check_solution',
            'var14' => 'sometimes|required_if:request_type,check_solution',
            'var15' => 'sometimes|required_if:request_type,check_solution',
            'var16' => 'sometimes|required_if:request_type,check_solution',
            'var17' => 'sometimes|required_if:request_type,check_solution',
            'var18' => 'sometimes|required_if:request_type,check_solution',
            'var19' => 'sometimes|required_if:request_type,check_solution',
            'var20' => 'sometimes|required_if:request_type,check_solution',
            'var21' => 'sometimes|required_if:request_type,check_solution',
            'var22' => 'sometimes|required_if:request_type,check_solution',
            'var23' => 'sometimes|required_if:request_type,check_solution',
            'var24' => 'sometimes|required_if:request_type,check_solution',
            'var25' => 'sometimes|required_if:request_type,check_solution',
            'var26' => 'sometimes|required_if:request_type,check_solution',
            'var27' => 'sometimes|required_if:request_type,check_solution',
            'var28' => 'sometimes|required_if:request_type,check_solution',
            'var29' => 'sometimes|required_if:request_type,check_solution',
            'var30' => 'sometimes|required_if:request_type,check_solution',
            'var31' => 'sometimes|required_if:request_type,check_solution',
            'var32' => 'sometimes|required_if:request_type,check_solution',
            'var33' => 'sometimes|required_if:request_type,check_solution',
            'var34' => 'sometimes|required_if:request_type,check_solution',
            'var35' => 'sometimes|required_if:request_type,check_solution',
            'var36' => 'sometimes|required_if:request_type,check_solution',
            'var37' => 'sometimes|required_if:request_type,check_solution',
            'var38' => 'sometimes|required_if:request_type,check_solution',
            'var39' => 'sometimes|required_if:request_type,check_solution',
            'var40' => 'sometimes|required_if:request_type,check_solution'
        ]);

        if($validator->fails()){
            if($request->ajax()){
//                return response()->json(['answer' => 'Variables have not been filled out']);
                abort(422);
            }else{
                $request->flash();
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        return $next($request);
    }
}
