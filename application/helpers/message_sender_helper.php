<?php
use Twilio\Rest\Client;
function send_otp_global($mobile, $otp, $re_send = false,$c_code="+91") {
    if(APP_ID==106){
        messageaspire($mobile,$otp);
    }elseif(APP_ID==125 && $c_code!='+91'){
      twiliosms($c_code.$mobile,$otp);
  }elseif(APP_ID == 49){
               otp_desi($c_code.$mobile,$otp);
  }else{

    //    pre(CONFIG_PROJECT_GLOBAL_NICK_NAME); die;
        $curl = curl_init();
        if(APP_ID == 53)
        {
            $postData       = array(
                'flow_id'   => '606aa5466506af6d00449dc5',
                'mobiles'   => "+91".$mobile,
                'short_url' => "0",
                'sender'    => 'APSQDZ',
                // 'user'      => 'User',
                'compname'  => "Prasar Bharati",
                'otp'       => $otp
            );
        }
        else
        {
            $postData       = array(
                'flow_id'   => '606aa5466506af6d00449dc5',
                'mobiles'   => "+91".$mobile,
                'short_url' => "0",
                'sender'    => 'APSQDZ',
                // 'user'      => 'User',
                'compname'  => ((defined("CONFIG_PROJECT_FULL_NAME") && CONFIG_PROJECT_FULL_NAME)?CONFIG_PROJECT_FULL_NAME:"Video Crypt"),
                'otp'       => $otp
            );
        }
        
         curl_setopt_array($curl, array(
          CURLOPT_URL               => "https://api.msg91.com/api/v5/flow/",
          CURLOPT_RETURNTRANSFER    => true,
          CURLOPT_ENCODING          => "",
          CURLOPT_MAXREDIRS         => 10,
          CURLOPT_TIMEOUT           => 30,
          CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST     => "POST",
          CURLOPT_POSTFIELDS        => json_encode($postData),
          CURLOPT_HTTPHEADER        => array(
            "authkey: 353522AU35rfTs7z601d0b16P1",
            "content-type: application/JSON"
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
}


function messageaspire($mobile,$otp,$dlt="1207162677814572181",$key="b44b01660f724f9629b844d6fcd3d6a3",$sender="PTHSLA"){
  $message=urlencode("Your One Time Password is $otp Pathshala Classes");
  file_get_contents("http://login.messageaspire.com/api/smsapi?key=$key&route=7&sender=$sender&number=$mobile&sms=$message&templateid=$dlt");
}

function twiliosms($mobile,$otp){
    // Required if your environment does not handle autoloading
    // require __DIR__ . '/vendor/autoload.php';
    require APPPATH . "/third_party/twilio/vendor/autoload.php";
    

    // Your Account SID and Auth Token from twilio.com/console
    // $sid = 'AC4b522001951abc64ed5ae4f50f7f8f54';
    // $token = 'cda8e4f5a2568a9ff59dc971570c8e21';

    $sid = 'ACd6b9f31c95c6a07ee8b416b6801190d5';
    $token = '1b227aca1c5af3fb1f4b3194b5d17ba4';



    $client = new Client($sid, $token);

    // Use the client to do fun stuff like send text messages!
    $client->messages->create(
        // the number you'd like to send the message to
        "$mobile",
        [
            // A Twilio phone number you purchased at twilio.com/console
            'from' => '+19794757933',
            // the body of the text message you'd like to send
            'body' => "Your One Time Password is $otp ".CONFIG_PROJECT_FULL_NAME
        ]
    );
}

 function otp_desi($mobile,$otp){
    

                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://2factor.in/API/V1/1f1ec131-af7d-11ee-8cbb-0200cd936042/SMS/'.$mobile.'/'.$otp.'/OTTAPP_OTP',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                //echo $response;

}


function send_message_txn($mobile) {
    
}