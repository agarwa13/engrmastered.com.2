<?php

namespace App\Http\Controllers;

use App\User;
use Aws\AwsClient;
use Aws\Sns\SnsClient;
use GuzzleHttp\Client;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Question;
use Auth;
use Log;
use App\ProblemEmail;

class EmailController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',['except' => [
            'postBounceOrComplaint',
            'getConfirmation',
            'postResendConfirmation'
        ]]);

        $this->middleware('admin',['except' => [
            'postBounceOrComplaint',
            'getConfirmation',
            'postResendConfirmation'
        ]]);
    }

    public function getPassword(){
        return view('emails.password')->with('token',uniqid());
    }

    public function getPayoutFailedAdmin(){
        return view('emails.payout_failed_admin')->with('user',Auth::user())->with('amount',100);
    }

    public function getPayoutFailedUser(){
        return view('emails.payout_failed_user')->with('user',Auth::user())->with('amount',100);
    }

    public function getQuestionWasCreated(){
        return view('emails.question_was_created')->with('question',Question::find(1));
    }

    public function getQuestionWasSolved(){
        return view('emails.question_was_solved')->with('question',Question::find(1));
    }

    public function getConfirmation(Request $request, $confirmation_code){

        $user = User::where('confirmation_code',$confirmation_code)->first();

        if ($user === null) {
            // Likely user has already been verified
            $request->session()->flash('notification','Email Address Confirmed');
            return redirect('/');
        }

        $user->confirmed = 1;
//        $user->tokens_remaining += $user->tokens_remaining + 2;
        $user->confirmation_code = null;
        $user->save();

        $request->session()->flash('notification','Email Address Confirmed');
//        $request->session()->flash('notification','2 Free Credits Added to Your Account');

        return redirect('/');

    }

    public function postResendConfirmation(Request $request, Mailer $mailer){

        /*
         * Update the Email Confirmation Code
         */
        $request->user()->confirmation_code = str_random(16);
        $request->user()->save();

        $mailer->send('emails.confirmation_code_subsequent_time',['user' => $request->user()],function($m) use ($request){
            $m->to($request->user()->email)->subject('Engineering Mastered - Confirmation Email');
        });

    }

    public function postBounceOrComplaint(Request $request, Mailer $mailer){

        Log::info($request->json()->all());

        if($request->json('Type') == 'SubscriptionConfirmation'){
            $client = new Client();
            $client->get($request->json('SubscribeURL'));
            return response()->json();
        }
//
        if($request->json('Type') == 'Notification'){

            $message =  json_decode($request->json('Message'));

            switch($message->notificationType){

                case 'Bounce':
                    $bounce = $message->bounce;
                    foreach ($bounce->bouncedRecipients as $bouncedRecipient){
                        $emailAddress = $bouncedRecipient->emailAddress;
                        $emailRecord = ProblemEmail::firstOrCreate(['email_address' => $emailAddress]);
                        $emailRecord->incrementBounceCount(1);
                    }
                    break;
                case 'Complaint':
                    $complaint = $message->complaint;
                    foreach($complaint->complainedRecipients as $complainedRecipient){
                        $emailAddress = $complainedRecipient->emailAddress;
                        $emailRecord = ProblemEmail::firstOrCreate(['email_address' => $emailAddress]);
                        $emailRecord->incrementComplainedCount(1);
                    }
                    break;

                default:
                    //  Do Nothing
                    break;

            }

        }

        return response()->json(['message' => 'success']);

    }

    public function getTestBounceEmail(Mailer $mailer){
        $mailer->raw('testing',function($m){
            $m->to('bounce@simulator.amazonses.com')
                ->subject('please bounce this email');
        });

        return 'Testing Bouncing Email Changed';
    }

    public function getTestComplaintEmail(Mailer $mailer){

        $mailer->raw('testing',function($m){
            $m->to('complaint@simulator.amazonses.com')
                ->subject('please complaint this email');
        });

        return 'Testing Complaint Email';
    }

}
