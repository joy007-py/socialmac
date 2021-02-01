<?php


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(81);

require_once("../includes/prmac_framework.php");
require_once("../includes/standalone.php");



function valid_email($email)
{
	if(strlen($email) > 50)
		return false;

	return preg_match('/^[-A-Za-z0-9_.+]+[@][A-Za-z0-9_-]+([.][A-Za-z0-9_-]+)*[.][A-Za-z]{2,8}$/', $email);
}

if($_POST['saveComment']){
    
    header( "Content-Type: application/json", true );
    $error=false;
    $comment=$_POST['comment'];
    
    if($comment['name']==""){
        $error="You must provide a name to post a comment.";
    }elseif(!valid_email($comment['email'])){
        $error="You must provide a valid email to post a comment. Your email address will not be displayed.";
    }elseif($comment['text']==""){
        $error="You must provide a comment.";
    }elseif($comment['release_id']==""){
        $error="Invalid article. You cannot comment on this article.";
    }
    elseif (!empty($comment['tel'])) {
      $error = "You do not seem to be a human.";
    }
    
    elseif(bannedIP($_SERVER['REMOTE_ADDR'])){
        $error="You cannot post comments from this IP Address.";
    }
    
    if(!$error){
        $comment['ip_address']=$_SERVER['REMOTE_ADDR'];

        if($comment['parent_id']!=""){
            $comment['status']='approved';
        }else{
            $comment['status']='pending';
        }

        
        //Insert
        $columns=$vals="";

        foreach($comment as $key=>$val){
          if ($key !== 'tel') {
            $key = $db->escape($key);
            $columns.="{$key}, ";
            $vals.="'" . $db->escape($val) . "', ";
          }
        }
        $columns=substr($columns, 0,  strlen($columns)-2);
        $vals=substr($vals, 0,  strlen($vals)-2);

        //echo "INSERT INTO blog_posts ({$columns}) VALUES ({$vals})";

        $db->query("INSERT INTO blog_comments ({$columns}) VALUES ({$vals})") or error('could not updated comments.', __FILE__, __LINE__, $db->error());
        $comment_id=$db->insert_id();

        //Update parent if exists
        if($comment['parent_id']!=""){
            $db->query("UPDATE blog_comments SET child_id='{$comment_id}' WHERE id='".$comment['parent_id'] ."'");
        }


        $success= "Your comment has been received. It will display after it is moderated by admin. Thank you!";
    }
    echo json_encode(array("error"=>$error, "success"=>$success));

}




