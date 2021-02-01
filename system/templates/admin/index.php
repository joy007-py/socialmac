<?php
require_once 'functions.php';
require_once("../includes/prmac_framework.php");
require_once '../includes/header.inc.php';
?>
<link href="/system/css/admin.css" rel="stylesheet">
<?php
require_once '../includes/nav.inc.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!$isAdmin){
    require_once '../templates/admin/login.php';
    
}else{

?>



<?php
    require 'navigation.php';
?>
<div class="col-sm-10">
    <div class="admin-container">
        <?php
                $ct=50;
                if(isset($_GET['pg'])){
                    $pg=$_GET['pg'];
                    $first=(int)$_GET['pg']*$ct;
                    $limit="LIMIT " .$first.",".$ct;
                }else{
                    $pg=1;
                    $limit="LIMIT " . $ct;
                }
                
                $result=$db->query("SELECT release_id, title, publish_date FROM rewrites WHERE active='1' ORDER BY publish_date DESC " . $limit);
                $releases=$db->fetch_all_assoc($result);
                ?>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="10">
                        Title
                    </th>
                    <th width="2">
                        Date
                    </th>
                       
                </tr>
            </thead>
            <tbody>
                <?php foreach($releases as $release){ 
                   echo '<tr><td><a href="/admin/edit/' . $release['release_id'] . '">' . $release['title'] . '</a></td><td>' . date('m/d/Y', $release['publish_date']).'</tr>'; 
                    
                }?>
            </tbody>
        </table>
                
                
        <?php 
        
        $result=$db->query("SELECT count(release_id) as num_rows FROM rewrites WHERE active='1'");
        $row=$db->fetch_all_assoc($result);
        $row=$row[key($row)];
        $num_rows=$row['num_rows'];
        paginate((int)ceil($num_rows/$ct), $pg, 'index.php');
        
        ?>
    </div>
    
</div>

<?php } ?>
<?php
require_once '../includes/footer.inc.php'; /*?>

<script>
    
    var redirect = "#";
    
function reply(parent_id, post_id, direct){
    $('#replyModal #parent_id').val(parent_id);
    $('#replyModal #post_id').val(post_id);
    $('#replyModal').modal('show');
    
    redirect=direct;
}
            
    $("#newComment").submit(function(event){
    // setup some local variables
    var $form = $(this),
        // let's select and cache all the fields
        $inputs = $form.find("input, select, button, textarea"),
        // serialize the data in the form
        serializedData = $form.serialize();
    $('#newCommentButton').prop("disabled",true);
    var resultDiv=$(this).attr('data-result');
    $('#'+resultDiv).html('<img class="loader" src="<?=$relative_url?>system/images/load-indicator.gif" />');
    // let's disable the inputs for the duration of the ajax request
    //$inputs.attr("disabled", "disabled");

    // fire off the request to /form.php
    $.ajax({
        url: "/system/ajax/comment.php",
        type: "post",
        data: serializedData,
        // callback handler that will be called on success
        success:function(data){
            if(data.error != undefined){
                if(data.error !== false){
                    alert(data.error);
                    $('#'+resultDiv).html("");
                }
                else{
                    
                    $('#'+resultDiv).html("Sent.");
                    $('#'+resultDiv).html("");
                    $('#newComment')[0].reset();
                    window.location=redirect + "&success=1";
                }
            }
        },
        complete:function(){
            
        }
    });

    // prevent default posting of form
    event.preventDefault();
    
   
});
    </script>

<?php require_once '../includes/close.inc.php'; */?>