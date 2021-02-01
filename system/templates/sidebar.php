<?php

$ct=1;
$lmt="LIMIT ";
if(isset($_GET['pg'])){
    $pg=$_GET['pg'];
    $first=((int)$_GET['pg']-1)*$ct;
    $lmt.=(int)$first+(int)$ct . ",".$ct;
}else{
    $pg=1;
    $lmt.= $ct . "," . $ct;
}
$sql = "SELECT title, release_id FROM rewrites WHERE active='1' ORDER BY publish_date DESC ".$lmt;

$result = $db->query($sql);
$rl = $db->fetch_all_assoc($result)
?>

<div class="sidebar">
    <div class="panel panel-releases">
        <div class="panel-heading">
            Recent Articles
        </div>
        <div class="panel-body">
            <div class="release-container">
            <?php foreach($rl as $release){
               echo '<a href="/articles/' . $release['release_id'] . '" >' . $release['title'] . '</a><br/>'; 
            }?>
            </div>
        </div>
    </div>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- Responsive -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-2844242203467249"
         data-ad-slot="4684004692"
         data-ad-format="auto"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
        <br />
        <script type="text/javascript"><!--
          amazon_ad_tag = "bbsapplescrine0e";  amazon_ad_width = "300";  amazon_ad_height = "250";  amazon_ad_logo = "hide";  amazon_ad_link_target = "new";  amazon_ad_border = "hide";  amazon_ad_discount = "remove";  amazon_color_border = "FFFFFF";  amazon_color_text = "666666";  amazon_color_link = "003399";  amazon_color_logo = "003399";  amazon_ad_exclude = "windows;longhorn;pc";  amazon_ad_include = "apple;mac+osx;leopard;iphone;itunes";  amazon_ad_categories = "af";//--></script>
        <script type="text/javascript" src="https://www.assoc-amazon.com/s/ads.js"></script>

</div>