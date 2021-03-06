<?php
require_once("../includes/prmac_framework.php");
$page="home";
/****************************************************************************
* prMac Integration Bundle Standalone 1.5
* Copyright (c) prMac | MacScripter, LLC
* 
* You must create two PHP-writable folders: 'prmac_cache' and 'tb_tracking' that belong in the same path as this script. The file "feed.xml" must be writable for RSS feeds.
* 
****************************************************************************/
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
    
    //Put into array with id as the key
    $result=$db->fetch_all_assoc($result);
    $comments=array();
    foreach($result as $c){
        $comments[$c['id']]=$c;
    }
    
    return $comments;
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
    $result = $db->query('SELECT id, release_id, date, name, email, text, parent_id, child_id, member_id, ip_address FROM blog_comments ' . $where . '  ORDER BY release_id ASC, date ASC') or error ('Unable to fetch blog comments', __FILE__, __LINE__, $db->error());
    
    //Put into array with id as the key
    $result=$db->fetch_all_assoc($result);
    $comments=array();
    foreach($result as $c){
        $comments[$c['id']]=$c;
    }
    
    return $comments;
}


function display_comment($comments, $id, $offset){
    
    $c=$comments[$id];
    
    $offset=(int)$offset;
    $total=12-(int)$offset;
    echo "
    <div class=\"col-xs-".$total." " . ($offset!='0'?'col-xs-offset-'.$offset:'') . " \">
        <div class=\"panel panel-default panel-comment\">
            <div class=\"panel-heading\">
                <strong>" . $c['name'] . "</strong>&nbsp;&nbsp; <span class=\"text-muted\">" . relative_time($c['date']) . "</span>

            </div>
            <div class=\"panel-body\">
                " . $c['text'] . "

            </div>
        </div>
    </div>";
    
    if($c['child_id']!=""){
        display_comment($comments, $c['child_id'], ++$offset);
    }
}
/*
$stalone_agencyCode = "lvdcerPr";  // Populated by the installer
$stalone_articleCount = 25; // The number of displayed articles

// If you'd like to ignore what channels you have selected in your agency profile and display releases from all channels, set this to true.
$stalone_showAllStories = false;



// You're done! You shouldn't need to edit anything else unless you want fine-grain control.

// Advanced options
$stalone_pageTitle = "SocialMac"; // title for release listing and XML feeds (default: "Latest %count% articles on prMac")
//$stalone_usePrettyUrls = false; // uncomment to force the use of short urls (should be autodetected by the script and thus unnecessary to set this)
$stalone_rssFilename = "feed.xml"; // Path to RSS feed (will be generated by the script; must exist and be writable by PHP; default: "feed.xml").
$stalone_tbTrackingPath = "../tb_tracking"; // Path to the folder in which trackback data is stored (must exist and be writable by php; default: "../tb_tracking")
$_prmac_cacheDir = "../prmac_cache"; // Path to folder in which cached xml data is stored (must exist and be writable by php; default: "../prmac_cache")
$stalone_baseURL = "{$_SERVER['PHP_SELF']}"; // URL from which prmac.php is accessed. (default: "{$_SERVER['PHP_SELF']}")
$stalone_generateProbability = 15; // increase to 100 if you have a very high-traffic site
$stalone_showSummaries = true; // whether or not summaries are displayed on the release listing (default: true)
$stalone_serveArticleBody = true; // if set to false, release links will point to prMac. If true, they will be served by this script.

if ($stalone_agencyCode == '{{agency_code}}') {
	header('Location:https://' . $_SERVER['SERVER_NAME'] . preg_replace('/^(\/.*?)(\/?[^\/]*\/?)$/i', '$1', $_SERVER['PHP_SELF']) . '/installer.php', 307);      
   die;
}
 * 
 * */
?>
<?php /* The sidebar, header, and footer files are included automatically by standalone.php; edit them to edit the structure of your site. */ ?>

<?php
require_once '../includes/header.inc.php';
?>

<?php
require_once '../includes/nav.inc.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
$release_url=str_replace('%release_id%',$release_id, PRMAC_RELEASEURL);

$release=prmac_getReleaseDetail($release_url,$stalone_agencyCode);
*/
$release=get_release($release_id);
// echo '<pre>';
// print_r($release);
// exit;
if (empty($release) || !$release['active']) {
  http_response_code(404);
  echo("Article not found.");
  ?>
  <div class="col-sm-4">
      <?php include_once('../templates/sidebar.php'); ?>
  </div>
  <?php
  require_once '../includes/footer.inc.php';
  require_once '../includes/close.inc.php';
  die();
}
?>



<div itemscope itemtype="http://schema.org/Article">
    <div class="section-heading-page">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <h1 class="heading-page title" itemprop="name"><?php echo $release['title']; ?></h1>
          </div>
          
        </div>
      </div>
    </div>

<!-- GRIDS -->
    <!--===============================================================-->
    <div class="container">
      <div class="row">
        <!-- GRID POSTS -->
        <!--===============================================================-->
        <div class="col-sm-8 grid-posts">
          <!-- POST ITEM 1 -->
          <div class="row">
            <div class="col-sm-12">
                <section class="content wrap justify" >
			
                    <article>
                        <p>
                            <?=$release['summary']?>
                        </p>
                    </article>

			
		</section>
        
            <?php 
                    if ($release['image_url'] != "") { ?>

        <section class="innerimage text-center">
                         <?php
                             $image = $release['image_url'];                            
                             $imageData = base64_encode(file_get_contents($image));
                             echo '<img src="data:image/jpeg;base64,'.$imageData.'">';
                             session_start();
                            //  $_SESSION["image1"] = $image;
                            // //   echo $_SESSION["image1"];
                            // //   echo 'Welcome to page #1';
                            // //   $_SESSION['favcolor'] = 'green';
                             ?>

        </section>           
                            <?php } ?>
        
                <section class="voting">
                    <?php $votes=get_votes($release_id); ?>
                    <div class="thumbs thumbs-up" onclick="javascript:vote('<?=$release_id?>','yes');">
                        <span class="fa fa-2x fa-thumbs-up"></span> <span id="voteYes"><?=$votes['yes']?></span>
                    </div>
                    <div class="thumbs thumbs-down" onclick="javascript:vote('<?=$release_id?>','no');">
                        <span class="fa fa-2x fa-thumbs-down"></span> <span id="voteNo"><?=$votes['no']?></span>
                    </div>
                </section>
		
		<section class="comments">
			<?php $comments=  getBlogComments($release_id, 'approved');
                        
                        if(count($comments)>0): ?>
			<div class="row">
                            <h3 style="margin-left:15px;">Comments</h3>
				<?php  
                                foreach($comments as $c):
                                            
                                            if($c['parent_id']==""){ $i=0; 
                                                display_comment($comments, $c['id'], $i);
                                            }
                                endforeach; ?>

                                
			</div>
			<?php endif; ?>
                            

                    
                      
                        <div class="panel panel-light mt-20">
                            
                            
                            <div class="panel-body">
                                <h3>Post a comment</h3>
                                <form role="form" action="" method="post" id="newComment">

                                    <div class="form-group">
                                        <input type="text" name="comment[name]" class="form-control" placeholder="Your Name" value=""/>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="comment[email]" class="form-control" placeholder="Your email (won't be published)" value=""/>
                                    </div>
                                  <div class="form-group hp">
                                      <input type="tel" name="comment[tel]" class="form-control" placeholder="Lea&#118;e th&#105;s fie&#108;d bla&#110;k!" />
                                  </div>
                                    <div class="form-group">
                                        <textarea name="comment[text]" class="form-control" placeholder="Your comment..."></textarea>
                                    </div>
                                    <input type="hidden" name="comment[release_id]" value="<?=$release_id?>" />
                                    <input type="hidden" name="comment[member_id]" value="" />
                                    <input type="hidden" name="saveComment" value="1"/>
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
		</section>
              
            </div>
          </div>
        </div>
        <!-- GRID POSTS END -->
        <!--===============================================================-->
        <div class="col-sm-4">
            <?php include_once('../templates/sidebar.php'); ?>
        </div>
      </div>
    </div>
</div>

    



<?php
require_once '../includes/footer.inc.php';
?>
<script>

            
$("#newComment").submit(function(event){
    // setup some local variables
    var $form = $(this),
        // let's select and cache all the fields
        $inputs = $form.find("input, select, button, textarea"),
        // serialize the data in the form
        serializedData = $form.serialize();
    $('#newCommentButton').prop("disabled",true);
    var resultDiv=$(this).attr('data-result');
    $('#'+resultDiv).html('<img class="loader" src="/system/images/load-indicator.gif" />');
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
                    showMessage('Error',data.error,'ok','yes');
                    $('#'+resultDiv).html("");
                }
                else{
                    showMessage('Success!',data.success,'ok','yes');
                    $('#'+resultDiv).html("Sent.");
                    $('#'+resultDiv).html("");
                    $('#newComment')[0].reset();
                }
            }
        },
        complete:function(){
            $('#newCommentButton').removeAttr('disabled');
        }
    });

    // prevent default posting of form
    event.preventDefault();
    
   
});
</script>

<script>

            
function vote(release_id,vote){
    // setup some local variables
    
    $.ajax({
        url: "/system/ajax/vote.php",
        type: "post",
        data: {release_id:release_id,
            vote:vote},
        // callback handler that will be called on success
        success:function(data){
            if(data.error != undefined){
                if(data.error !== false){
                    showMessage('Error',data.error,'ok','yes');
                }
                else{
                    $('#voteYes').html(data.yes);
                    $('#voteNo').html(data.no);
                }
            }
        },
        complete:function(){
            
        }
    });

    
   
}
</script>
<?php

require_once '../includes/close.inc.php';