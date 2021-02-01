<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$cron=$_GET['id'];

if($cron=='15min'){
    echo "Running 15 min cron...<br>";
    include '../cron/import.php';
}

