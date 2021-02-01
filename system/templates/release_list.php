<?php
// Print a html list of releases

$ct=10;
if(isset($_GET['pg'])){
    $pg=$_GET['pg'];
    $first=((int)$_GET['pg']-1)*$ct;
    $limit="LIMIT " .$first.",".$ct;
    $limit_pg2="LIMIT " . $first+$ct . ",".$ct;
}else{
    $pg=1;
    $limit="LIMIT " . $ct;
    $limit_pg2="LIMIT " . $ct . "," . $ct;
}
                
$sql = "SELECT * FROM rewrites WHERE active='1' ORDER BY publish_date  DESC ".$limit;

$result = $db->query($sql);
$releaseList = $db->fetch_all_assoc($result);
$page="home";

stalone_print_header(stalone_title());

global $sysdir;
require_once($sysdir . "includes/nav.inc.php");
?>

<div id="primary" class="content-areax col-md-9 col-sm-12">

    <main id="main" class="site-main" role="main">
        
<?php
stalone_tb_init();
foreach ($releaseList as $releaseInfo) {
    
    ?>
        <div class="row mb-50">
            <div class="col-sm-2 col-md-2 hidden-xs">
                <time datetime="<?= date('Y-m-d', ($releaseInfo['publish_date'])) ?>" class="icon">
                    
                    <strong><?= strtoupper(date('M', ($releaseInfo['publish_date']))) ?></strong>
                    <span><?= date('j', ($releaseInfo['publish_date'])) ?></span>
                </time>
                <div class="year"><?= date('Y', ($releaseInfo['publish_date'])) ?></div>
            </div>
            <div class="col-sm-10 col-md-10">
                <article id="post-<?= $releaseInfo['release_id'] ?>" class="post-<?= $releaseInfo['release_id'] ?> post type-post status-publish format-standard has-post-thumbnail hentry category-4-5-star category-device category-ipad category-iphone category-reviews tag-arts tag-design tag-journal tag-journaling-file-system tag-online-writing tag-thought tag-twitter tag-united-states">
                   
                    <div class="entry-content" style="min-height: 162px;">
                        <h1 class="entry-title"><a href="/articles/<?= $releaseInfo['release_id'] ?>" rel="bookmark"><?= $releaseInfo['title'] ?></a></h1>		

                        <p>
                            <?= generateExcerpt($releaseInfo, 'article_cover') ?>
                        </p>

                    </div><!-- .entry-content -->

                    <div class="entry-format">
                        <a href="/articles/<?= $releaseInfo['release_id'] ?>">
                            <span class="fa fa-apple"></span>
                        </a>	
                    </div>
                </article><!-- #post-## -->
            </div>
        </div>
            
    <?php
    // Print out this release's summary (user must click-through to the article for the body)
    $articleURL = ($stalone_serveArticleBody) ? stalone_url(false, "prmac_id={$releaseInfo['release_id']}") : $releaseInfo['url'];
    ?>
    <?php
    //stalone_submit_trackback($releaseInfo['release_id'], $releaseInfo['title'], $releaseInfo['trackbackurl']);
}

$result=$db->query("SELECT count(release_id) as num_rows FROM rewrites WHERE active='1'");
        $row=$db->fetch_all_assoc($result);
        $row=$row[key($row)];
        $num_rows=$row['num_rows'];
        ?>
        <div class="col-sm-offset-3 col-md-offset-2 col-sm-9 col-md-10">
            <?php paginate((int)ceil($num_rows/$ct), $pg, 'index.php');?>
        </div>
        <?php
stalone_tb_flush();
?>

    </main>
</div>
<div class="hidden-xs hidden-sm col-md-3 side-bar">
    <?php require_once($sysdir . 'templates/sidebar.php'); ?>
</div>

<?php require_once($sysdir . "includes/footer.inc.php"); ?>