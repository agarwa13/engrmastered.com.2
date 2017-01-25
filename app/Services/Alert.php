<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 11/2/2015
 * Time: 9:56 PM
 */

namespace App\Services;


use Session;

class Alert
{

    /**
     * Alert constructor.
     * @param $alerts
     */
    public function __construct()
    {
        if(!Session::has('alerts')){
            Session::set('alerts',array());
        }
    }

    /**
     * @param $message
     * @param string $type
     */
    public function add_alert($message, $type = 'success'){
        Session::push('alerts',['message' => $message, 'type' => $type]);
    }

    /**
     * @return mixed
     */
    public function getAlerts()
    {
        return Session::pull('alerts',array());
    }


    public function resetAlerts(){
        Session::set('alerts',array());
    }


}