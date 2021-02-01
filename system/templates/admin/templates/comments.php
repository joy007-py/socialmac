<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function approve_comment($comment_id){
        global $db;
        $result = $db->query('UPDATE blog_comments SET status = \'approved\' WHERE id = \'' . $comment_id . '\' LIMIT 1') or error ('Unable to update comment', __FILE__, __LINE__, $db->error());
	return true;
    }
    
    function pending_comment($comment_id){
        global $db;
        $result = $db->query('UPDATE blog_comments SET status = \'pending\' WHERE id = \'' . $comment_id . '\' LIMIT 1') or error ('Unable to update comment', __FILE__, __LINE__, $db->error());
	return true;
    }
    
    function delete_comment($comment_id){
        global $db;
        $result = $db->query('DELETE FROM blog_comments WHERE id = \'' . $comment_id . '\' LIMIT 1') or error ('Unable to update comment', __FILE__, __LINE__, $db->error());
	return true;
    }
    
    function ban_ip($comment_id){
        global $db, $pr_config;
        
        $result=getBlogComment($comment_id);
        $c=$db->fetch_assoc($result);
        
        
        
        $ban_ips = $pr_config['ban_ips']."\n".$c['ip_address'];
	$result = $db->query('UPDATE '.$db->prefix.'config SET conf_value = \''.$ban_ips.'\' WHERE conf_name = \'ban_ips\' LIMIT 1') or error ('Unable to update banned IPs', __FILE__, __LINE__, $db->error());
		
        return true;
    }
    
    function display_comment($comments, $id, $offset){
        
        $c=$comments[$id];
        
        $offset=(int)$offset;
        $total=12-(int)$offset;
        
        if((int)$c['member_id']>0){
            $name="<a href=\"https://prmac.com/admin/member_edit.php?id=" . $c['member_id'] . "\" target=\"_blank\">" . $c['name'] . "</a>";
        }else{
            $name=$c['name'];
        }
        
        echo "
        <div class=\"col-xs-".$total." " . ($offset!='0'?'col-xs-offset-'.$offset:'') . " \">
            <div class=\"panel panel-default panel-comment\">
                <div class=\"panel-heading\">
                    <strong>" . $name . "</strong> <span class=\"text-muted\">" . relative_time($c['date']) . " (" . $c['email'] . ")</span>

                </div>
                <div class=\"panel-body\">
                    " . $c['text'] . "
                    <br/><br/><br/>
                    <a href=\"index.php?page=comments&approve=1&id=" . $c['id'] . "\" class=\"btn btn-primary btn-green\" >Approve</a>
                    <a href=\"javascript:reply('" . $c['id'] . "','" . $c['release_id'] . "','index.php?page=comments&approve=1&id=" . $c['id'] . "')\" class=\"btn btn-primary btn-blue\" >Reply & Approve</a>
                    <a href=\"index.php?page=comments&delete=1&id=" . $c['id'] . "\" class=\"btn btn-primary btn-red\" >Delete</a>
                    <a href=\"index.php?page=comments&ban=1&id=" . $c['id'] . "\" class=\"btn btn-primary btn-red\" >Ban IP</a>
                </div>
            </div>
        </div>";

        if($c['child_id']!=""){
            display_comment($comments, $c['child_id'], ++$offset);
        }
    }
    
    function display_comment_approved($comments, $id, $offset){
    
        
        $c=$comments[$id];
        
        $offset=(int)$offset;
        $total=12-(int)$offset;
        
        if((int)$c['member_id']>0){
            $name="<a href=\"https://prmac.com/admin/member_edit.php?id=" . $c['member_id'] . "\" target=\"_blank\">" . $c['name'] . "</a>";
        }else{
            $name=$c['name'];
        }
        
        echo "
        <div class=\"col-xs-".$total." " . ($offset!='0'?'col-xs-offset-'.$offset:'') . " \">
            <div class=\"panel panel-default panel-comment\">
                <div class=\"panel-heading\">
                    <strong>" . $name . "</strong> <span class=\"text-muted\">" . relative_time($c['date']) . " (" . $c['email'] . ")</span>

                </div>
                <div class=\"panel-body\">
                    " . $c['text'] . "
                    <br/><br/><br/>
                    <a href=\"index.php?page=comments&pending=1&id=" . $c['id'] . "\" class=\"btn btn-primary btn-green\" >Set Pending</a>
                    <a href=\"javascript:reply('" . $c['id'] . "','" . $c['release_id'] . "','index.php?page=comments&approve=1&id=" . $c['id'] . "')\" class=\"btn btn-primary btn-blue\" >Reply</a>
                    <a href=\"index.php?page=comments&delete=1&id=" . $c['id'] . "\" class=\"btn btn-primary btn-red\" >Delete</a>
                    <a href=\"index.php?page=comments&ban=1&id=" . $c['id'] . "\" class=\"btn btn-primary btn-red\" >Ban IP</a>
                </div>
            </div>
        </div>";

        if($c['child_id']!=""){
            display_comment_approved($comments, $c['child_id'], ++$offset);
        }
    }
    
    function fetch_all_assoc($query_result = 0)
    {
        global $db;
            if (!$query_result)
                    return false;

            $rows = array();
            while ($row = $db->fetch_assoc($query_result)){
                    $id=$row['id'];
                    $rows[$id] = $row;
            }
            return $rows;
    }

    //HANDLES ACTIONS
    
    if(isset($_GET['id'])){
        if(isset($_GET['approve'])){
            $result=approve_comment($_GET['id']);
            $success="<div class=\"success\">Comment approved.</div>";
        }elseif(isset($_GET['delete'])){
            $result=delete_comment($_GET['id']);
            $success="<div class=\"success\">Comment deleted.</div>";
        }elseif(isset($_GET['pending'])){
            $result=  pending_comment($_GET['id']);
            $success="<div class=\"success\">Comment set to pending.</div>";
        }elseif(isset($_GET['ban'])){
            $result=ban_ip($_GET['id']);
            delete_comment($_GET['id']);
            $success="<div class=\"success\">IP Address banned and comment deleted.</div>";
        }
        
        if(isset($_GET['success'])){
            $success="<div class=\"success\">Reply saved and comment approved.</div>";
        }
    }
    
    
    
    
    
    
    
    
    //END ACTIONS
    
    //$posts=fetch_all_assoc(getBlogPosts());
    
    $pending =getAllBlogComments("pending");
    $pending_s = $db->fetch_all_assoc($pending);
    $pending=array();
    foreach($pending_s as $p){
        $pending[$p['id']]=$p;
    }
    
    $approved =getAllBlogComments('approved');
    $approved_s = $db->fetch_all_assoc($approved);
    $approved=array();
    foreach($approved_s as $a){
        $approved[$a['id']]=$a;
    }
    
    
echo $error;
echo $success;
?>
<h2>Comments</h2>
    <div id="exTab2" class="">	
        <ul class="nav nav-tabs">
            <li class="active">
                <a  href="#1" data-toggle="tab">Pending</a>
            </li>
            <li><a href="#2" data-toggle="tab">Approved</a>
            </li>
        </ul>

        <div class="tab-content ">
            <div class="tab-pane active clearfix" id="1">
                <?php  $current_post=""; foreach($pending as $key=>$c){
                        $i=0;
                        
                        if($c['release_id']==$current_post){
                            //SAME POST
                        }else{
                            echo "<h4 class=\"mt-30\"><a href=\"https://socialmac.com/articles/" . $c['release_id'] . "\" target=\"_blank\">" . $c['title']."</a></h4>"; 
                        }
                        $current_post=$c['release_id'];
                        if($c['parent_id']==""){
                            display_comment($pending, $key, $i);
                        }
                } ?>
                
            </div>
            <div class="tab-pane clearfix" id="2">
                <?php  $current_post=""; foreach($approved as $key=>$c){
                        $i=0;
                        
                        if($c['release_id']==$current_post){
                            //SAME POST
                        }else{
                            echo "<h4 class=\"mt-30\"><a href=\"https://socialmac.com/articles/" . $c['release_id'] . "\" target=\"_blank\">" . $c['title']."</a></h4>"; 
                        }
                        $current_post=$c['release_id'];
                        if($c['parent_id']==""){
                            display_comment_approved($approved, $key, $i);
                        }
                } ?>
            </div>
        </div>
    </div>

        

<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Post a Reply Comment</h4>
                </div>
                <div class="modal-body" style="text-align:right">
                    <div class="panel panel-light mt-20">
                            
                            
                        <div class="panel-body">
                            <form role="form" action="" method="post" id="newComment">

                                <div class="form-group">
                                    <input type="text" name="comment[name]" class="form-control" placeholder="Your Name" value="<?=$admin_name?>"/>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="comment[email]" class="form-control" placeholder="Your email (won't be published)" value="<?=$pr_admin['email']?>"/>
                                </div>
                                <div class="form-group">
                                    <textarea name="comment[text]" class="form-control" placeholder="Your comment..."></textarea>
                                </div>
                                <input type="hidden" name="comment[release_id]" id="post_id" value="<?=$post['id']?>" />
                                <input type="hidden" name="comment[member_id]" value="<?=$pr_admin['member_id']?>" />
                                <input type="hidden" name="saveComment" value="1"/>
                                <input type="hidden" name="comment[parent_id]" id="parent_id" value=""/>

                                <div class="clearfix">
                                    <div class="pull-right">
                                      <p class="submit">
                                            <input id="newCommentButton" type="submit" class="btn btn-default" value="submit" />
                                        </p>
                                        <div id="data-result"></div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
</div>        
