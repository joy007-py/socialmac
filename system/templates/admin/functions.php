<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$isAdmin=(isset($_COOKIE['admin']) && $_COOKIE['admin']=='4sk1psk'?true:false);


function pluralise($amount, $str, $alt = '') {
	return intval($amount) === 1 ? $str : $str . ($alt !== '' ? $alt : 's');
}

function relative_time($date) {
	if(is_numeric($date)) $date = '@' . $date;

        
	$user_timezone = new DateTimeZone('America/Chicago');
	$date = new DateTime($date, $user_timezone);

	// get current date in user timezone
	$now = new DateTime('now', $user_timezone);

	$elapsed = $now->format('U') - $date->format('U');

	if($elapsed <= 1) {
		return 'Just now';
	}

	$times = array(
		31104000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);

	foreach($times as $seconds => $title) {
		$rounded = $elapsed / $seconds;

		if($rounded > 1) {
			$rounded = round($rounded);
			return $rounded . ' ' . pluralise($rounded, $title) . ' ago';
		}
	}
}

function getBlogComments($post_id, $status="all"){
    global $db;
    
    if($status=='approved'){
        $where=" WHERE release_id='{$post_id}' AND status='approved'";
    }elseif($status=='pending'){
        $where=" WHERE release_id='{$post_id}' AND status='pending'";
    }else{
        $where=" WHERE release_id='{$post_id}' ";
    }
    $result = $db->query('SELECT id, date, name, email, text, parent_id, child_id, member_id, ip_address FROM blog_comments ' . $where . '  ORDER BY date ASC') or error ('Unable to fetch blog comments', __FILE__, __LINE__, $db->error());
    return $result;
}

function getBlogComment($comment_id){
    global $db;
    
        $where=" WHERE id='{$comment_id}' ";
    
    $result = $db->query('SELECT id, date, name, email, text, parent_id, child_id, member_id, ip_address FROM blog_comments ' . $where . '  LIMIT 1') or error ('Unable to fetch blog comment', __FILE__, __LINE__, $db->error());
    return $result;
}

function getAllBlogComments($status="all"){
    global $db;
    
    if($status=='approved'){
        $where=" WHERE status='approved'";
    }elseif($status=='pending'){
        $where=" WHERE status='pending'";
    }else{
        
    }
    $result = $db->query('SELECT c.id, c.release_id, c.date, c.name, c.email, c.text, c.parent_id, c.child_id, c.member_id, c.ip_address, r.title FROM blog_comments c LEFT JOIN rewrites r ON c.release_id=r.release_id ' . $where . '  ORDER BY release_id ASC, date ASC') or error ('Unable to fetch blog comments', __FILE__, __LINE__, $db->error());
    return $result;
}

