<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$cron=$_GET['q'];
$id=$_GET['id'];

require_once('../includes/standalone-functions.inc.php');

if($cron=='15min'){
    echo "Running 15 min cron...<br/>";
    include '../cron/import.php';
}elseif($id=='rss'){
    include '../templates/public/feed.php';
}elseif($id=='sitemap'){
    $s=file_get_contents('../sitemap/sitemap.xml');
    header("Content-type: text/xml");
    print($s);
}elseif($id=='map'){
    $s=file_get_contents('../sitemap/sitemap.xml');
    header("Content-type: text/xml");
    print($s);
}elseif($id=='contact'){
    echo getcwd();
    include '/system/templates/public/contact.php';
}else{
    echo "Static.";
}


