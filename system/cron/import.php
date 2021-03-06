<?php

$sysdir = dirname(realpath(__FILE__)) . "/../";
require_once($sysdir . "includes/prmac_framework.php");
require_once($sysdir . "includes/standalone-functions.inc.php");


/****************************************************************************
* prMac Integration Bundle Standalone 1.5
* Copyright (c) prMac | MacScripter, LLC
* 
* You must create two PHP-writable folders: 'prmac_cache' and 'tb_tracking' that belong in the same path as this script. The file "feed.xml" must be writable for RSS feeds.
* 
****************************************************************************/
 

$stalone_agencyCode = "lvdcerPr";  // Populated by the installer
$stalone_articleCount = 25; // The number of displayed articles

// If you'd like to ignore what channels you have selected in your agency profile and display releases from all channels, set this to true.
$stalone_showAllStories = false;



// You're done! You shouldn't need to edit anything else unless you want fine-grain control.

// Advanced options
$stalone_pageTitle = "SocialMac"; // title for release listing and XML feeds (default: "Latest %count% articles on prMac")
//$stalone_usePrettyUrls = false; // uncomment to force the use of short urls (should be autodetected by the script and thus unnecessary to set this)
$stalone_rssFilename = "feed.xml"; // Path to RSS feed (will be generated by the script; must exist and be writable by PHP; default: "feed.xml").
global $stalone_tbTrackingPath;
$stalone_tbTrackingPath = $sysdir . "/tb_tracking"; // Path to the folder in which trackback data is stored (must exist and be writable by php; default: "system/tb_tracking")
global $_prmac_cacheDir;
$_prmac_cacheDir = $sysdir . "/prmac_cache"; // Path to folder in which cached xml data is stored (must exist and be writable by php; default: "system/prmac_cache")
global $_prmac_enableCache;
// Don't use the cache of previously-imported articles. We want new articles to go over "instantly."
$_prmac_enableCache = FALSE;

$stalone_baseURL = "{$_SERVER['PHP_SELF']}"; // URL from which prmac.php is accessed. (default: "{$_SERVER['PHP_SELF']}")
$stalone_generateProbability = 15; // increase to 100 if you have a very high-traffic site
$stalone_showSummaries = true; // whether or not summaries are displayed on the release listing (default: true)
$stalone_serveArticleBody = true; // if set to false, release links will point to prMac. If true, they will be served by this script.

if ($stalone_agencyCode == '{{agency_code}}') {
	header('Location:https://' . $_SERVER['SERVER_NAME'] . preg_replace('/^(\/.*?)(\/?[^\/]*\/?)$/i', '$1', $_SERVER['PHP_SELF']) . '/installer.php', 307);      
   die;
}
?>

<?php /* The sidebar, header, and footer files are included automatically by standalone.php; edit them to edit the structure of your site. */ ?>

<?php 

   echo "getting releases\n";
    $releaseList = prmac_getReleaseList($stalone_agencyCode, $stalone_articleCount, $stalone_showAllStories, 'https://socialmac.com/');
    if (!$releaseList) {
      die("Fetching release list failed.");
    }
  
    $s=(file_get_contents($sysdir . '/sitemap/sitemap.xml'));
    file_put_contents($sysdir . '/sitemap/sitemap.bkp.xml', $s);
    
    
    stalone_tb_init();
	
    foreach ($releaseList as $releaseInfo)
    { 
        
        $insert_array=array(
            'active' => 0,
            "release_id"=>$releaseInfo['id'],
            "title"=>$releaseInfo['title'],
            "original"=>$releaseInfo['summary'],
            "publish_date"=>$releaseInfo['timestamp'],
            "price"=> '',
            "company_text"=> '',
            "company_url"=> '',
            "product_text"=> '',
            "product_url"=> '',
            "download_text"=> '',
            "download_url"=> '',
            "image_text"=> '',
            "image_url"=> '',
            "trackback"=>$releaseInfo['trackbackurl']
        );
        // Get release body so we can extract URLs
        $release = prmac_getReleaseDetail($releaseInfo['xmlurl']);
		    $insert_array['summary'] = $releaseInfo['summary'] . "\n\n" .removeLinkStack($release['body']);
	
        // Try to extract URLs
        if (preg_match_all('/<a href="([^"]*)[^>]*>([^<]*)</', $release['body'], $matches)) {
          foreach (array(0 => 'company', 1 => 'product', 2 => 'download', 3 => 'image') as $idx => $link) {
            if (isset($matches[0][$idx])) {
              $insert_array[$link . '_text'] = html_entity_decode($matches[2][$idx]);
              $insert_array[$link . '_url'] = urldecode($matches[1][$idx]);
            }
          }
        }

        $columns = implode(", ",array_keys($insert_array));
        $escaped_values = array_map(array($db,'escape'), array_values($insert_array));
        $values  = implode("', '", $escaped_values);
        $values = "'" . $values . "'";
        $sql = "INSERT IGNORE INTO `rewrites`($columns) VALUES ($values)";
        $db->query($sql);
        echo("{$releaseInfo['id']}: {$releaseInfo['title']}\n");
        
    }
    stalone_tb_flush();
    echo "done.";
	pr_log("cron 15min ran");