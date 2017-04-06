<?php

namespace common\components;

use Yii;
use yii\base\Component;

class Sms extends Component {

    public function __construct($config = array()) {

        parent::__construct($config);

        if (ob_get_contents())
            ob_end_clean();
    }

    public function send($mobile_no, $message) {
        $authKey = "b312a491b1d6b93b96f06a15fd83918";
        $mobileNumber = $mobile_no;
        $senderId = "KHYJAO";
        $message = urlencode($message);

        $url = ("http://sms1.omnetsolution.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=$authKey&message=$message&senderId=$senderId&routeId=1&mobileNos=$mobileNumber&smsContentType=english");
        $data = @file_get_contents($url);
        return true;
    }

//    public function send($mobile_no, $message) {
//
//
//
//        //Your authentication key
//        //$authKey = "5841AJi10IWfz54250192";
//        $authKey = "b312a491b1d6b93b96f06a15fd83918";
//
//
//
//
//        //Multiple mobiles numbers separated by comma
//
//        $mobileNumber = $mobile_no;
//
//
//
//        //Sender ID,While using route4 sender id should be 6 characters long.
//
//        $senderId = "KHYJAO";
//
//
//
//        //Your message to send, Add URL encoding here.
//
//        $message = urlencode($message);
//
//
//
//        //Define route
//
//        $route = "Route 4";
//
//        //Prepare you post parameters
//
//        $postData = array(
//            'authkey' => $authKey,
//            'mobiles' => $mobileNumber,
//            'message' => $message,
//            'sender' => $senderId,
//            'route' => $route
//        );
//
//
//        //API URL
//        //$url = "http://sms1.omnetsolution.com/sendhttp.php";
//        $url = "http://sms1.omnetsolution.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=$authKey&message=$message&senderId=$senderId&routeId=1&mobileNos=$mobileNumber&smsContentType=english";
//
//
//        // init the resource
//
//        $ch = curl_init();
//
//        curl_setopt_array($ch, array(
//            CURLOPT_URL => $url,
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_POST => true,
//            CURLOPT_POSTFIELDS => $postData
//
//                //,CURLOPT_FOLLOWLOCATION => true
//        ));
//
//
//
//
//
//        //Ignore SSL certificate verification
//
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//
//
//
//
//
//        //get response
//
//        $output = curl_exec($ch);
//
//        curl_close($ch);
//
//
//
//        return true;
//    }
}

?>