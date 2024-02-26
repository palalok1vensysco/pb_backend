<?php

function sendAndroidPush($deviceToken, $msg, $image = "", $badge = 0, $check = 0, $type = "") {
    // echo $type;die;
    $registrationIDs = array($deviceToken);
    if (is_array($deviceToken)) {
        $registrationIDs = $deviceToken;
    } else {
        $registrationIDs = array($deviceToken);
    }
    // Message to be sent
    $message = $msg;

    $CI =& get_instance();
    $CI->db->join('application_manager','application_manager.id=meta_information.app_id');
    (defined("APP_ID") ? "" . app_permission("meta_information.app_id") . "" : "0");
    $CI->db->where('meta_name','GSM_KEY');
    $creds=$CI->db->get('meta_information')->row_array();
    $GSM_KEY = "";
    if(!empty($creds)){
        $creds['meta_value']=json_decode($creds['meta_value'],true);
        $GSM_KEY=$creds['meta_value']['GSM_KEY']??'';
        $project_name=$creds['title'];
    }

    $type = json_decode($msg, true);
    $fields = array();
    $fields['registration_ids'] = $registrationIDs;
    $fields['data']['message'] = $type;
    $fields['data']['type'] = 0;
    $url = 'https://fcm.googleapis.com/fcm/send';

   // print_r($fields);die;
    /* in codeigniter GSM_KEY set in constatnt folder */
    $headers = array(
        'Authorization: key=' . $GSM_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();

    //Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    //Execute post
    $result = curl_exec($ch);
    curl_close($ch);
//    pre($result);die;
    return $result;
}

function generatePush($deviceType, $deviceToken, $message) {

    if ($deviceType == '1') {
        return sendAndroidPush($deviceToken, $message);
    } else if ($deviceType == '2') {
        require_once APPPATH . 'libraries/Redis_magic.php';
        $redis_magic = new Redis_magic("data");

        $msg = $message;
        $message = (array) (json_decode($message));
        if (is_array($message) && array_key_exists('message', $message)) {
            if (!isset($message['title']))
                $message['title'] = "Utkarsh Classes";
            $message['message'] = strip_tags($message['message']);
            $body_var = $message['message'];
        } else {
            $body_var = $msg;
        }

        $payload['aps'] = array(
            'alert' => array(
                'body' => $body_var,
                'action-loc-key' => CONFIG_PROJECT_FULL_NAME,
            ),
            'json' => $message,
            'badge' => 0,
            'mutable-content' => 1,
            'sound' => 'default',
        );
        $payload['device_token'] = $deviceToken;

        $redis_magic->publish('ios_push', json_encode($payload));
    } else {
        
    }
}
