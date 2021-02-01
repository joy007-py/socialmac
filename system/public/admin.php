<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

error_reporting(E_ALL);

require_once('../includes/standalone-functions.inc.php');

$page = $_GET['q'];

if($page=='login'){
    include '../templates/admin/login.php';
}
elseif($page == 'wordlist') {
    include '../templates/admin/wordlist.php';
}
elseif (empty($page)) {
    include '../templates/admin/rewrites.php';
}
else {
    $page = str_replace(".", "", $page);
    include "../templates/admin/{$page}.php";
}
