<?php

$result=$db->query("SELECT count(id) as total FROM blog_comments WHERE status='pending'");
$data=$db->fetch_all_assoc($result);
$data=$data[key($data)];


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="col-sm-2 col-md-2 admin-nav">
  <a href="/admin/">Home</a><br/>
  <a href="/admin/index">Published Articles</a><br />
  <a href="/admin/wordlist">Word List</a><br />
  <a href="/admin/comments">Comments<?=(int)$data['total']>0?' ('.$data['total'].')':'';?></a><br/>
</div>