<?php


/*
	Osxome v0.2, a text-based news/journal system
	by North Knight Software: http://www.northknight.ca/
	Released under the MIT License:
	  http://www.opensource.org/licenses/mit-license.php
	See LICENSE for more information
	Last modified December 2, 2008
*/


/*
	Layout of the $pageData array:
	
	$pageData['title']
	$pageData['pageURL']
	$pageData['rssDescription']
	$pageData['items = array']
	(
		$pageData['items'][$x] = array
		(
			$pageData['items'][$x]['title']
			$pageData['items'][$x]['time']
			$pageData['items'][$x]['data']
			$pageData['items'][$x]['link']
		)
	)
*/

header('Content-type: application/rss+xml');

/* PHP doesn't really like it when a markup starts with "<?", so this is
printed instead. */
print '<?xml version="1.0"?>' . "\n\n";


?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><?php print $pageData['title']; ?></title>
		<link><?php print $pageData['pageURL']; ?></link>
    <atom:link href="<?php print $pageData['pageURL']; ?>rss.php" rel="self" type="application/rss+xml" />
		<description><?php print $pageData['rssDescription']; ?></description>
		<language>en-us</language>
		<generator>Osxome by North Knight Software</generator>
		
		<?php foreach($pageData['items'] as $article): ?>
		
		<item>
			<title><?php print $article['title']; ?></title>
			<link><?php print $pageData['pageURL']; ?><?php print $article['link']; ?></link>
			<description><?php print $article['data']; ?></description>
			<pubDate><?php print date('r', $article['time']); ?></pubDate>
			<guid><?php print $pageData['pageURL']; ?><?php print $article['link']; ?></guid>
		</item>
		
		<?php endforeach; ?>
		
		
  </channel>
</rss>
