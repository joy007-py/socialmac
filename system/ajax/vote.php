<?php


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(81);

require_once("../includes/prmac_framework.php");
require_once("../includes/standalone.php");


function valid_email($email)
{
    if (strlen($email) > 50) {
        return false;
    }

    return preg_match('/^[-A-Za-z0-9_.+]+[@][A-Za-z0-9_-]+([.][A-Za-z0-9_-]+)*[.][A-Za-z]{2,8}$/', $email);
}

if($_POST['release_id']){
    
    header( "Content-Type: application/json", true );
    $error=false;
    $release_id=  $db->escape($_POST['release_id']);
    $vote=$db->escape($_POST['vote']);
    $ip_address=$db->escape($_SERVER["REMOTE_ADDR"]);
    
    
    if($vote!="yes" && $vote!="no"){
        $error="Invalid vote.";
    }elseif($release_id==""){
        $error="Invalid article.";
    }elseif(bannedIP($ip_address)){
        $error="You have been banned from voting.";
    }
    
    
    
    if(!$error){
        $result=$db->query("SELECT vote FROM votes WHERE ip_address='" . $ip_address . "' AND release_id='" . $release_id . "'");
        if($db->num_rows($result)==1){
            $query="UPDATE votes SET vote='" . $vote . "' WHERE ip_address='" . $ip_address . "'";
            $db->query($query);
        }else{
            $fields="vote='" . $vote . "', ip_address='" . $ip_address . "', release_id='" . $release_id . "'"; 
            $query = "INSERT INTO `votes` SET $fields;";
            $db->query($query);
        }
        
        $v_arr=get_votes($release_id);
        
    }
    echo json_encode(array("error"=>$error, "success"=>$success, "yes"=>$v_arr['yes'], "no"=>$v_arr['no']));

}




