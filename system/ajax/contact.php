<?php


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(81);

require_once("../includes/prmac_framework.php");
require_once("../includes/standalone.php");
require_once '../includes/recaptcha.lib.php';


function valid_email($email)
{
	if(strlen($email) > 50)
		return false;

	return preg_match('/^[-A-Za-z0-9_.+]+[@][A-Za-z0-9_-]+([.][A-Za-z0-9_-]+)*[.][A-Za-z]{2,8}$/', $email);
}

if($_POST['sendContact']){
    
    header( "Content-Type: application/json", true );
    $error=false;
    $comment=$_POST['contact'];
    // your secret key
    $secret = "6LdgXRUTAAAAAOFrwEWC1ZNCbeQLpczHe1C9AO4V";

    // empty response
    $response = null;

    // check secret key
    $reCaptcha = new ReCaptcha($secret);
    // if submitted check response
    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
    }
    if($response == null || !$response->success){
        $error = "Please check the box.";
    }elseif($comment['name']==""){
        $error="You must provide a name.";
    }elseif(!valid_email($comment['email'])){
        $error="You must provide a valid email.";
    }elseif($comment['message']==""){
        $error="You must provide a message.";
    }
    
    /*elseif(bannedIP($_SERVER['REMOTE_ADDR'])){
        $error="You cannot post comments from this IP Address.";
    }*/
    
    if(!$error){
        $comment['ip_address']=$_SERVER['REMOTE_ADDR'];
        $msg= "You have a new message\n\nFrom: " . $comment['name'] . "\nEmail: " . $comment['email'] . "\n\nMessage:\n" . $comment['message'] . "\n\nThanks,\nSocialMac";
        $headers = 'From: socialMac <media@prmac.com>' . PHP_EOL .
        'Reply-To: GeekSuit <media@prmac.com>' . PHP_EOL .
        'X-Mailer: PHP/' . phpversion();
        
        mail('ray@geeksuit.com','socialMac Contact',$msg,$headers);
        $success="Your message has been sent. You can expect a reply within 1 business day.";
    }
    echo json_encode(array("error"=>$error, "success"=>$success));

}




