<?php

namespace App\Http\Controllers;
use Auth;
use App\Services\StripeClient;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;

class StripeController extends Controller
{
    public function addPaymentMethod(StripeClient $stripe_client ,Request $request){

        $params = array();


        if(Auth::user()->has_payment_method()){
            $previous_default_card_id = $stripe_client->get_default_payment_method_card_id(Auth::user()->id);
            $params['previous_default_token'] = $previous_default_card_id;
        }

        if($request->has('token')){
            $card = Auth::user()->add_payment_method($request->input('token'));
        }else {
            return response()->json(['message' => 'Token Not Provided'], 400);
        }

        if(isset($previous_default_card_id)){
            $previous_default_html = view('user.payment_method')->with('payment_method',Auth::user()->get_payment_method($previous_default_card_id))->render();
            $params['previous_default_html'] = $previous_default_html;
        }


        if($request->ajax()){
            $html = view('user.payment_method')->with('payment_method',$card)->render();
            $params['html'] = $html;
            return response()->json($params,200);
        }else{
            return redirect('/');
        }
    }


    public function deletePaymentMethod(Request $request, $card_id){
        Auth::user()->delete_payment_method($card_id);

        if($request->ajax()){
            return response()->json(['message','Payment Method Successfully Deleted'],200);
        }else{
            return back();
        }
    }

    public function updateDefaultPaymentMethod(StripeClient $stripe_client, Request $request, $new_default_payment_method_id){
        $previous_default_payment_method_token = Auth::user()->get_default_payment_method_card_id();
        Auth::user()->update_default_payment_method($new_default_payment_method_id);
        $previous_default_html = view('user.payment_method')->with('payment_method',Auth::user()->get_payment_method($previous_default_payment_method_token))->render();
        $new_default_html = view('user.payment_method')->with('payment_method',Auth::user()->get_payment_method($new_default_payment_method_id))->render();

        if($request->ajax()){
            return response()->json([
                'new_default_token' => $new_default_payment_method_id,
                'previous_default_token' => $previous_default_payment_method_token,
                'new_default_html' => $new_default_html,
                'previous_default_html' => $previous_default_html
            ],200);
        }else{
            return back();
        }
    }

    public function handleDispute(Request $request){

        $type = $request->json('type');

        $result = Mail::send('emails.report_dispute',['type' => $type],function($message){
            $message->to(env('ADMIN_EMAIL_1'))->subject('Dispute Update');
        });
        return response()->json(['result' => $result , 'type' => $type]);
    }


}
