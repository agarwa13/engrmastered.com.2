<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 11/2/2015
 * Time: 9:56 PM
 */

namespace App\Services;


use Session;

class Notification
{

    /**
     * Notification constructor.
     * @param $notifications
     */
    public function __construct()
    {
        if(!Session::has('notifications')){
            Session::set('notifications',array());
        }
    }

    /**
     * @param $message
     * @param string $type
     */
    public function add_notification($message, $type = 'success'){
        Session::push('notifications',['message' => $message, 'type' => $type]);
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return Session::pull('notifications',array());
    }


    public function resetNotifications(){
        Session::set('notifications',array());
    }


}