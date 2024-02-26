<?php

function send_mail($to, $subject, $message)
{


    require_once base_url(). 'third_party/phpmailerclass/class.phpmailer.php';
    $from_name  = '';
    $from_email     = 'mailto:web@appsquadz.com';
    // try{
    //     $mail = new PHPMailer(true);
    //     $mail->SMTPDebug = 1;
    //     $mail->isSMTP();
    //     $mail->Host = 'email-smtp.us-west-2.amazonaws.com';
    //     $mail->SMTPAuth = true;

    //       $mail->Username = 'AKIAJTE42KEUHU3OKY2A';
    //       $mail->Password = 'AkC5z3ZO9CPErlAWGa/s2A2dW6dfjeD8Uayz4TYCLqaq';
    //     // $mail->Priority = 1;
    //     $mail->SMTPSecure = 'tls';
    //     $mail->Port = 587;
    //     //Recipients
    //     $mail->setfrom($from_email, $from_name);
    //     $mail->addAddress($to);
    //     $mail->isHTML(true);
    //     $mail->Subject = $subject;
    //     $mail->Body = $message;
    //     $mail->WordWrap = 80;

    //     if (!$mail->send()) {
    //         echo 'Message was not sent.';
    //         echo 'Mailer error: ' . $mail->ErrorInfo;
    //     } else {
    //         return true;
    //        // echo "message"; die;
    //     }
    // } catch (Exception $ex) {
    //     //print_r($ex); 
    // }

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = "smtp@gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "tyagishubham973@gmail.com";
    $mail->Password = "tyagi@9012";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->From = "tyagishubham973@gmail.com";
    $mail->FromName = "shubham";
    $mail->addAddress($to); //"mailto:21.00.chandrankant@gmail.com"
    $mail->addreplyto('tyagishubham973@gmail.com', 'Reply to BOS-CRET');
    $mail->isHTML(true);
    $mail->Subject = $subject; //No Reply- Submit your mode of communication && preferences to ICAI

    $mail->Body = $message;
    //$mail->AltBody = "This is the plain text version of the email content";
    try { 
        $mail->send();
        // echo "Message has been sent successfully";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}


function send_status_mail($to, $subject, $message)
{
//echo"ff" ;die;
   //require_once base_url(). 'vdc_test/application/third_party/phpmailerclass/class.phpmailer.php';
  // require_once base_url(). 'third_party/phpmailerclass/class.phpmailer.php';
   // require_once getcwd() . '/phpmailerclass/class.phpmailer.php';
   // echo"dd";die;
   require_once($_SERVER['DOCUMENT_ROOT'].'/application/third_party/phpmailerclass/class.phpmailer.php');
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 1;
    $mail->isSMTP();
   // echo"ddd";die;
    $mail->Host = "smtp.gmail.com";
   // echo"d";die;
    $mail->SMTPAuth = true;
    $mail->Username = "tyagishubham973@gmail.com";
   // echo"ddddd";die;
    $mail->Password = "rifcyypynfehqehe";
    //echo"ded";die;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->From = "tyagishubham973@gmail.com";
    $mail->FromName = "shubham";
    //echo"ded";die;
    $mail->addAddress($to); //"mailto:21.00.chandrankant@gmail.com"
    // $mailto:mail->addreplyto('replytoboscret@icai.in', 'Reply to BOS-CRET');
    $mail->isHTML(true);
    $mail->Subject = $subject; //No Reply- Submit your mode of communication && preferences to ICAI
    $mail->Body = $message;
    //$mail->AltBody = "This is the plain text version of the email content";
    try {
        $mail->send();
        // echo "Message has been sent successfully";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}