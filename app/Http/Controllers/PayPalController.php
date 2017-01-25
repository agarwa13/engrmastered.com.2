<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 10/7/2015
 * Time: 4:58 PM
 */

namespace App\Http\Controllers;


use App\Events\PayoutFailed;
use App\PayPalPayout;
use App\User;
use Log;
use Illuminate\Http\Request;
use PayPal\Api\Currency;
use PayPal\Api\Payout;
use PayPal\Api\PayoutItem;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Exception\PayPalInvalidCredentialException;
use PayPal\Rest\ApiContext;
use PayPal\Test\Api\CurrencyTest;


class PayPalController extends Controller
{

    private $apiContext;


    public function __construct(){
        $this->apiContext = new ApiContext(new OAuthTokenCredential(env('PAYPAL_CLIENT_ID'),env('PAYPAL_CLIENT_SECRET')));
    }

    /**
     * @return PayoutSenderBatchHeader
     */
    private function getNewPayoutSenderBatchHeader(){
        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setEmailSubject("Payment from EM");
        $senderBatchHeader->setSenderBatchId(uniqid());
        return $senderBatchHeader;
    }

    /**
     * @param $email_address
     * @param $amount
     * @return PayoutItem
     */
    private function getNewPayoutItem($email_address, $amount)
    {

        $amount = floatval($amount);
        $amount = number_format($amount, 1, '.' ,'');

        $senderItem = new PayoutItem();
        $senderItem->setRecipientType('Email');
        $senderItem->setNote('Thanks for your solutions!');
        $senderItem->setReceiver($email_address);
        $senderItem->setAmount(new Currency('{"value":"'.$amount.'","currency":"USD"}'));
        $senderItem->setSenderItemId(uniqid());
        return $senderItem;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function postRedeemEarnings(Request $request){

        /*
         * Determine which email address to send the Payment to
         */
        $email_address = $request->input('email_address',$request->user()->email);

        /*
         * Ensure the the user has a confirmed email address
         */
        if(!$request->user()->confirmed){
            abort(401);
        }

        /*
         * Ensure the user is not an administrator
         */
        if($request->user()->isAdmin()){
            abort(402);
        }

        /*
         * Compute Amount to be paid
         * Important to do this on the server side
         */
        $amount = $request->user()->income - $request->user()->income_redeemed;

        /*
         * Ensure the Amount is greater than 20$
         */
        if($amount < 20){
            abort(403);
        }

        /*
         * Do not automatically redeem if amount is greater than 500$, instead notify an administrator
         */
        if($amount > 500){
            abort(404);
        }

        /*
         * Update the Users Profile
         */
        $request->user()->income_redeemed = $amount + $request->user()->income_redeemed;
        $request->user()->save();

        /*
         * Setup and Store the Payout in the Database
         */
        $pp_payout = PayPalPayout::create([
            'user_id' => $request->user()->id,
            'email_address' => $email_address,
            'amount' => $amount,
            'payout_batch_id' => uniqid(),
            'payout_item_id' => uniqid(),
            'transaction_status' => 'Setup'
        ]);

        /*
         * Send the Payment
         */
        $pp_payout = $this->pay($pp_payout);

        /*
         * Return Success or Failure
         */
        if($pp_payout->transaction_status == "FAILED"){

            /*
             * Reverse the changes in the database
             */
            $request->user()->income_redeemed = $request->user()->income_redeemed - $amount;
            $request->user()->save();

            /*
             * Fire the Payout Failed Event
             */
            event(new PayoutFailed($request->user(), $amount));

            /*
             * Return the response to the user
             */
            abort(400);
        }

        if($request->ajax()){
            return response()->json(['success' => $pp_payout->transaction_status],200);
        }else{
            return 'TODO: Create View for Non-AJAX Requests to Redeem Earnings!';
        }

    }

    /**
     * @param $pp_payout
     * @return mixed
     */
    private function pay($pp_payout){

        /*
         * Create a New Payout
         */
        $payout = new Payout();

        /*
         * Get a New PayoutSenderBatchHeader
         */
        $senderBatchHeader = $this->getNewPayoutSenderBatchHeader();

        /*
         * Get a New Payout Item
         */
        $senderItem = $this->getNewPayoutItem($pp_payout->email_address, $pp_payout->amount);

        /*
         * Attach the SenderBatchHeader and Payout Item to the Payout
         */
        $payout->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);

        /*
         * Update the Transaction Status to Attempting
         */
        $pp_payout->updateTransactionStatus('Attempting');

        /*
         * Send the Request
         * Update the Transaction Status in the Database and Return
         */
        $request = clone $payout;
        try{
            $output = $payout->createSynchronous($this->apiContext);

            if($this->getFirstItem($output->getItems())->transaction_status == "FAILED"){
                Log::alert($output);
                $pp_payout->failTransaction();
            }

        }catch(\Exception $e){
            Log::alert($request);
            Log::alert($e->getMessage());
            Log::alert($e->getTraceAsString());
            $pp_payout->failTransaction();
        }

        return $pp_payout;
    }

    /*
     * Helper Function for Better Readability
     */
    private function getFirstItem($array){
        return $array[0];
    }



}