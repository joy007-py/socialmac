<?php

require_once("../includes/prmac_framework.php");
header("Content-type: application/rss+xml");

function prepFieldForXML(&$item1, $key)
{
     $item1 = htmlspecialchars(filter_var($item1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
}

$limit = " LIMIT 50";
$sql = "SELECT release_id, title, summary, publish_date FROM rewrites WHERE active='1' ORDER BY publish_date  DESC ".$limit;

$result = $db->query($sql);
$releases = $db->fetch_all_assoc($result);
    
    
print "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";

print '<rss version="2.0">'."\n";
	
print "\t".'<channel>'."\n";
print "\t".'<title>socialMac RSS Feed</title>'."\n";
print "\t".'<description>socialMac provides breaking coverage for the iPhone, iPad, and all things Mac.</description>'."\n";
print "\t".'<copyright>&#xA9; '.date('Y').' socialMac</copyright>'."\n";


print "\t".'<link>https://socialmac.com</link>'."\n";
print "\t".'<language>en-us</language>'."\n";

foreach ($releases as $releaseInfo)
{
	array_walk($releaseInfo, "prepFieldForXML");
	$summary = strip_tags(preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $releaseInfo['summary']));
        $title=preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $releaseInfo['title']);
        
	print "\t<item>\n";
	print "\t\t<title>{$title}</title>\n";
	print "\t\t<link>https://socialmac.com/articles/{$releaseInfo['release_id']}</link>\n";
	print "\t\t<guid>https://socialmac.com/articles/{$releaseInfo['release_id']}</guid>\n";
        print "\t\t<pubDate>" . date('r', strtotime($releaseInfo['publish_date'])) . "</pubDate>\n";
        print "\t\t<description>{$summary}</description>\n";
	
	
	print "\t</item>\n";
}


print "\t".'</channel>'."\n";
print '</rss>'."\n";
