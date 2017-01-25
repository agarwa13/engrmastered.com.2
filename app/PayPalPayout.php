<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayPalPayout extends Model
{


    /*
     * Database Table
     */
    protected $table = 'pay_pal_payouts';

    /*
     * Mass Assignable Fields
     */
    protected $fillable = ['user_id', 'email_address', 'amount', 'fee', 'payout_batch_id', 'payout_item_id', 'transaction_status'];

    /*
     * Relationship to Receiver
     */
    public function receiver(){
        return $this->hasOne('App\User');
    }

    /**
     * @param $status
     * @return $this
     */
    public function updateTransactionStatus($status)
    {
        $this->transaction_status = $status;
        $this->save();
        return $this;
    }


    /**
     * @return $this
     */
    public function failTransaction(){
        // Update the User
        $user = User::find($this->user_id);
        $user->income_redeemed = $user->income_redeemed - $this->amount;
        $user->save();

        // Update the Transaction
        $this->updateTransactionStatus('FAILED');
        return $this;
    }


}
