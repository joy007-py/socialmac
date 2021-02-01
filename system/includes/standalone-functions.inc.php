<?php
require_once('db.class.php');
$sysdir = dirname(realpath(__FILE__)) . "/../";
define('TPL_PATH', $sysdir . 'templates/');
$db = new DBLayer();

/* Copyright (c) prMac | MacScripter, LLC */

/****************************************************************************
* You shouldn't need to change anything below this.
*****************************************************************************/

function stalone_print_header($title, $breadcrumb = '', $article_title = '', $article = '')
{
	global $stalone_rssFilename;
	global $sysdir;
	if (strlen($stalone_rssFilename))
	{
		$rsp = realpath($stalone_rssFilename);
		$path = substr($rsp, strpos($rsp, $_SERVER['DOCUMENT_ROOT'])+strlen($_SERVER['DOCUMENT_ROOT']));
		if ($path[0] != "/")
			$path = "/" . $path;
		$feed_url = "https://{$_SERVER['SERVER_NAME']}" . $path;
	} else
		$feed_url = stalone_url(true, "prmac_xml=1");
	
	$_t_page_title = htmlspecialchars($title);
	
	require_once($sysdir . "includes/header.inc.php");
}

function stalone_print_footer()
{
  global $sysdir;
	require_once($sysdir . "includes/footer.inc.php");
}


/****************************************************************************
* Core functions
*****************************************************************************/


function get_release($release_id) {
    global $db;
    $result = $db->query("SELECT * FROM rewrites WHERE release_id='" . intval($release_id) . "'");
    $result = $db->fetch_all_assoc($result);
    
    return $result[key($result)];
}

function stalone_title()
{
	global $stalone_articleCount, $stalone_pageTitle;
	return htmlspecialchars(str_replace("%count%", $stalone_articleCount, $stalone_pageTitle));
}

function stalone_url($uri = true, $extraQS = null, $htmlEscape = true)
{
	global $stalone_baseURL, $stalone_usePrettyUrls;
	$url = "";
	$qs = ((strpos($stalone_baseURL, "?") !== false) ? "&" : "?") . $extraQS;
	
	if (strlen($qs) == 1)
		$qs = "";
	
	if ($uri)
		$url = "https://{$_SERVER['SERVER_NAME']}";
	
	if ($stalone_usePrettyUrls && preg_match("/\?prmac_id=([0-9]*)/", $qs, $matches))
		$url .= $stalone_baseURL . $matches[1];
	else
		$url .= $stalone_baseURL . $qs;
			

	return $htmlEscape ? htmlspecialchars($url) : $url;	
}

function stalone_generateRSSFeed()
{
	global $stalone_baseURL,$stalone_agencyCode, $stalone_articleCount, $stalone_showAllStories;
	
	// Print the RSS feed
	$feedTitle = htmlspecialchars(stalone_title());
	$xml = <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
	<channel>
	<title>{$feedTitle}</title>
	<description>{$feedTitle}</description>
	<link>https://{$_SERVER['SERVER_NAME']}{$stalone_baseURL}</link>

EOT;

	$releaseList = prmac_getReleaseList($stalone_agencyCode, $stalone_articleCount, $stalone_showAllStories);

	foreach ($releaseList as $releaseInfo)
	{
		$xml .= join("\n\t\t",
			array (
				"\t\t<item>",
				"<title>" . htmlspecialchars($releaseInfo['title']) . "</title>",
				"<link>" . stalone_url(true, "prmac_id={$releaseInfo['id']}") . "</link>",
				'<guid isPermaLink="true">' . stalone_url(true, "prmac_id={$releaseInfo['id']}") . '</guid>',
				"<description>" . htmlspecialchars($releaseInfo['summary']) . "</description>",
				"</item>"
				));
	}

	$xml .= "\t</channel>\n</rss>";
	
	return $xml;
}

function stalone_rewriteRSSFeed()
{
	global $stalone_rssFilename;
	if (!strlen($stalone_rssFilename))
		return;
		
	$lastUpdated = stalone_XMLGenerationTime();
	
	if (is_numeric($lastUpdated) && (date('d') == date('d', $lastUpdated)) && (time() - $lastUpdated <= (4*60*60)) )
		return;
			
	if (!is_writable($stalone_rssFilename))
		exit("RSS feed isn't writable");
	
	$fh = fopen($stalone_rssFilename, "w");
	fwrite($fh, stalone_generateRSSFeed());
	fclose($fh);
		
	stalone_touchXMLGenerationTime();
}

function stalone_XMLGenerationTime()
{
	global $stalone_tbTrackingPath;
	return trim(@file_get_contents("{$stalone_tbTrackingPath}/rss.dat"));
}

function stalone_touchXMLGenerationTime()
{
	global $stalone_tbTrackingPath;
	$fh = fopen("{$stalone_tbTrackingPath}/rss.dat", "w");
	if ( !fwrite($fh, time()) )
		exit("TB bookkeeping folder isn't PHP writable!");
		
	fclose($fh);
}



function stalone_detectPrettyUrls()
{
	global $stalone_usePrettyUrls, $stalone_baseURL;
	if (isset($stalone_usePrettyUrls))
		return;
		
	if (!strlen($htaccess_content = @file_get_contents(".htaccess")))
		return $stalone_usePrettyUrls = false;

	$stalone_usePrettyUrls = preg_match('/^RewriteRule(.*)prmac_id=\$[0-9]{1,2}/mi', $htaccess_content);
	
	if (!$stalone_usePrettyUrls)
		return;
	
	if ((substr($stalone_baseURL, -9) == "index.php") )
		$stalone_baseURL = substr($stalone_baseURL, 0, strlen($stalone_baseURL)-9);	
}

//
// Output paging links
//
function paginate($num_pages, $cur_page, $link_to, $querystring = array(), $url_rewrite = true, $returnOutput = false) {

    $querystring2 = array();

    foreach ($querystring as $name => $value) {
        $querystring2[htmlentities($name)] = htmlentities($value);
    } // clean query string...

    $querystring = $querystring2; // cleaned query string...

    if ($returnOutput) {
        ob_start();
    }

    if ($num_pages <= 1) {
        return $returnOutput ? "" : true;
    }

    echo '<div id="paging">
              <ul class="pagination">';

    $jump = 0;
    $pages = array();
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i == 1 || $i == $num_pages || ($i > ($cur_page - 5) && $i < ($cur_page + 5))) {
            if ($jump) {
                $pages[] = '<li id="seperator">&#8230;</li>';
            }
            $jump = 0;
            if ($i == $cur_page) {
                $pages[] = '<li class="current"><a href="#" ><strong>' . $i . '</strong></a></li>';
            } else {
                $querystring['pg'] = $i;
                $pages[] = '<li><a href="' . url($link_to, $querystring, $url_rewrite) . '" class="pagerlink">' . $i . '</a></li>';
            }
        } else {
            $jump = 1;
        }
    }

    if ($cur_page > 1) {
        $querystring['pg'] = $cur_page - 1;
        echo '<li><a href="' . url($link_to, $querystring, $url_rewrite) . '" class="pagerlink">&laquo; Previous</a></li>';
    }
    echo implode(' ', $pages);
    if ($cur_page < $num_pages) {
        $querystring['pg'] = $cur_page + 1;
        echo '<li><a href="' . url($link_to, $querystring, $url_rewrite) . '" class="pagerlink">Next &raquo;</a></li>';
    }

    echo '</ul></div>';

    if ($returnOutput) {
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}

//
// Create URL
//
function url($file = '', $args = array(), $use_config = true) {
    global $base_url, $url_rewrite;

    if ($url_rewrite && $use_config) {
        $url = $base_url . str_replace('.php', '', $file);
        if (!empty($args)) {
            foreach ($args as $k => $v) {
                $url .= '-' . $k . '-' . $v;
            }
            $url .= '.htm';
        }
        return $url;
    }

    if (!empty($args)) {
        $url_args = array();
        foreach ($args as $name => $value) {
            $url_args[] = $name . '=' . $value;
        }
        $url = $base_url . $file . '?' . implode('&', $url_args);
    } else {
        $url = $base_url . $file;
    }

    return $url;
}

//	----------------------------------------------
//		BANNING
//	----------------------------------------------

function release_rewrite($release) {
    $summary = $release['summary'];

    //Add company links
    if ($release['company_url'] != "") {
        $summary = str_replace($release['company_text'], '<a href="' . $release['company_url'] . '" target="_blank">' . $release['company_text'] . '</a>', $summary);
    }

    //Add product links
    if ($release['product_url'] != "") {
        $summary = str_replace($release['product_text'], '<a href="' . $release['product_url'] . '" target="_blank">' . $release['product_text'] . '</a>', $summary);
    }

    //Add download links
    if ($release['download_url'] != "") {
        $summary = str_replace($release['download_text'], '<a href="' . $release['download_url'] . '" target="_blank">' . $release['download_text'] . '</a>', $summary);
    }

    if ($release['product_text'] != "" && $release['product_url'] != "" && $release['price'] != "") {
        $summary.=' <a href="' . $release['product_url'] . '" target="_blank">' . $release['product_text'] . '</a>';
        if ($release['price'] == 'free') {
            $summary.= " is completely free";
        } elseif ((float) str_replace('$', '', $release['price'] > 0.99)) {
            $summary.=" is just " . $release['price'];
        } else {
            $summary.= " is " . $release['price'];
        }
        if ($release['download_url'] != "" && $release['download_text'] != "") {
            $summary.= ' and can be downloaded from <a href="' . $release['download_url'] . '" target="_blank">' . $release['download_text'] . "</a>.";
        }
    }

    return $summary;
}

function bannedIP($ip) {
    global $db;
    $query = "SELECT 1 FROM {$db->prefix}bans WHERE ip = \"" . preg_replace("/[^\d\.]/", '', $ip) . '"';
    $rez = $db->query($query);
    return $rez && $db->result($rez);
}

function bannedUsername($username) {
    global $pr_config;
    return pr_ban_check($username, $pr_config['ban_usernames']);
}

function bannedEmail($email) {
    global $pr_config;
    return pr_ban_check($email, $pr_config['ban_emails']);
}

function pr_ban_check($str, $ban_lines_combined) {
    if (!$lines = explode("\n", $ban_lines_combined)) {
        return false;
    }

    foreach ($lines as $line) {
        if (!strlen($line)) {
            continue;
        }

        $pattern = '/^' . str_replace('\*', '.*?', preg_quote(trim($line), '/')) . '$/i';

        if (preg_match($pattern, $str)) {
            return true;
        }
    }

    return false;
}

function get_votes($release_id) {
    global $db;
    
    $results = array();

    $result = $db->query("SELECT count(id) as yes FROM votes WHERE vote='yes' AND release_id='" . $release_id . "'");
    $data = $db->fetch_all_assoc($result);
    $data = $data[key($data)];

    $results['yes'] = (int) $data['yes'];

    $result = $db->query("SELECT count(id) as no FROM votes WHERE vote='no' AND release_id='" . $release_id . "'");
    $data = $db->fetch_all_assoc($result);
    $data = $data[key($data)];

    $results['no'] = (int) $data['no'];

    return $results;
}

function error($message, $file, $line, $db_error = 0) {

    $error_message = "Date: " . date("Y-m-d g:ia") . "\nError Message: $message \nFile: $file \nLine: $line \n\n";

    $error_message_full = $error_message . "REQUEST VARS: \n===============\n";

    foreach ($_REQUEST as $k => $v) {
        $error_message_full .= "$k = $v \n";
    }

    if (isset($_SESSION)) {
    $error_message_full .= "\nSESSION VARS: \n===============\n";
      foreach ($_SESSION as $k => $v) {
          $error_message_full .= "$k = $v \n";
      }
    }

    if ($db_error) {
        if (is_array($db_error)) {
            foreach ($db_error as $k => $v) {
                $error_message .= $k . ": " . $v . "\n";
            }
        }
        // Mail Error
        //mailer("ray@prmac.com","prMac DB Error",$error_message,"From:prMac<noreply@prmac.com>");
        //mailer("bryan@daytoncreative.com","prMac DB Error",$error_message,"From:prMac<noreply@prmac.com>");
    }

    print_r($error_message_full);

    exit;
}

function stalone_tb_init() {
    global $stalone_submittedTBs, $stalone_tbTrackingPath;

    $tbBookkeepingFile = "{$stalone_tbTrackingPath}/tb.dat";

    if (file_exists($tbBookkeepingFile) && !is_writable($tbBookkeepingFile)) {
        exit("TB Bookkeeping folder isn't PHP writable! Add write permissions to '{$stalone_tbTrackingPath}' (located at: '" . realpath($stalone_tbTrackingPath) . "')");
    }

    $s = unserialize(@file_get_contents($tbBookkeepingFile));
    $stalone_submittedTBs = (is_array($s) && count($s)) ? $s : array();
}

function stalone_tb_flush() {
    global $stalone_submittedTBs, $stalone_tbTrackingPath;
    $tbBookkeepingFile = "{$stalone_tbTrackingPath}/tb.dat";
    //echo "CWD: " . getcwd() . " TB: " . $tbBookkeepingFile . "<br/>";

    $fh = fopen($tbBookkeepingFile, "w");
    if (!fwrite($fh, serialize(array_unique($stalone_submittedTBs)))) {
        exit("TB bookkeeping folder isn't PHP writable!");
    }

    fclose($fh);
}

function stalone_submit_trackback($releaseID, $title, $tbURL) {
    global $stalone_submittedTBs;

    if (!$releaseID || !$title) {
        return;
    }

    if (isset($stalone_submittedTBs) && in_array($releaseID, $stalone_submittedTBs)) {
        return;
    }

    $articleURL = "https://socialmac.com/articles/" . $releaseID;
    $postVars = array("title" => $title, "url" => $articleURL);
    if (!stalone_submit_http_request($tbURL, $postVars)) {
        return;
    }
    $stalone_submittedTBs[] = $releaseID;
}

function stalone_submit_http_request($url, $args) {
    global $prmac_last_error, $stalone_error;
    //echo "TB URL: " . $url . " <br/>";
    $postData = stalone_build_post_query($args);
    if (is_callable("curl_init")) {
        //echo "<br/> Curling it...<br/>";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $xml = curl_exec($ch);
        if (!$xml) {
            $stalone_error = "Problem sending trackback";
            //echo $stalone_error;
            return false;
        } else {
            //var_dump($xml);
        }

        curl_close($ch);
    } elseif (ini_get("allow_url_fopen")) {

        $params = array('http' => array(
                'method' => 'POST',
                'content' => $postData,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                "Content-length: " . strlen($postData) . "\r\n"
        ));
        $ctx = stream_context_create($params);

        if (!($fp = fopen($url, 'rb', false, $ctx))) {
            $stalone_error = "Problem sending trackback - couldn't connect to host";
            //echo $stalone_error;
            return false;
        }
        fclose($fp);
    } else {
        exit("No way to send trackbacks to prMac integration server (allow_url_fopen and libcurl are both disabled)");
    }

    return true;
}

function stalone_build_post_query($arr) {
    $outarr = array();
    foreach ($arr as $k => $v) {
        $outarr[] = urlencode($k) . "=" . urlencode($v);
    }

    return implode("&", $outarr);
}

function pr_log($message, $label = "") {

    $time = date('j M Y g:i:s a');

    if (is_array($message) || is_object($message)) {
        $message = print_r($message, true);
    }

    if ($label) {
        $message = $label . ": \n" . $message;
    }

    $cache_new = $time . "  " . $message . "\n";
    $file = "../../../private/cron.log";
    $handle = fopen($file, "r+");
    if ($handle == false) {
        return false;
    }
    while (!flock($handle, LOCK_EX)) {
        // code...
        usleep(100000);
    }

    $len = strlen($cache_new);
    $final_len = filesize($file) + $len;
    $cache_old = fread($handle, $len);
    rewind($handle);
    $i = 1;
    while (ftell($handle) < $final_len) {
        fwrite($handle, $cache_new);
        $cache_new = $cache_old;
        $cache_old = fread($handle, $len);
        fseek($handle, $i * $len);
        $i++;
    }
    fflush($handle);            // flush output before releasing the lock
    flock($handle, LOCK_UN);    // release the lock
    fclose($handle);
    return false;
}

/**
 * @param array $content
 * @param string $key
 * @return string
 */
function generateExcerpt($content, $key)
{
    if($key == 'article_cover') {
        return getContentSummary($content['summary']);
    }else if ($key == 'article_detail') {
        return $content['summary'];
    }
    
    return '';
}

/**
 * Dynamically chop article summary from the first line break
 * 
 * @param string $content
 */
function getContentSummary($content)
{
    if(strpos($content, '<br />') !== false) {
        $first_line_break = strpos($content, '<br />');
        if($first_line_break !== false)
        {
            return substr($content, 0, $first_line_break);
        }   
        else
        {
            return $content;
        }
    }
    else 
    {
        return $content;
    }
}

/**
 * Remove link stack and <br /> from the end of the content body
 * 
 * @param string $content
 * @return string
 */
function removeLinkStack($content) 
{
    $final_string = substr($content, 0, strpos($content, '<a'));
    
    return rtrim(trim(rtrim($final_string, '<br />')), '<br />');
}