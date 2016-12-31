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
	$pageData['items = array']
	(
		$pageData['items'][$x] = array
		(
			$pageData['items'][$x]['title']
			$pageData['items'][$x]['user']
			$pageData['items'][$x]['time']
			$pageData['items'][$x]['data']
			$pageData['items'][$x]['link']
		)
	)
	$pageData['time']
	$pageData['pagination'] = array
	(
		$pageData['newOffset']
		$pageData['sep']
		$pageData['oldOffset']
	)
*/




?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>
	<title><?php print $pageData['title']; ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php print $pageData['pageURL']; ?>main.css">
	<link href="<?php print $pageData['pageURL']; ?>rss.php" rel="alternate" type="application/rss+xml" title="RSS Feed">
</head>

<body>
<div id="root">
	<div id="main">
		<div id="header">
			<h1><a href="<?php print $pageData['pageURL']; ?>"><?php print $pageData['title']; ?></a></h1>
			<h2><a href="<?php print $pageData['pageURL']; ?>">An Osxome example</a></h2>
		</div>
		
		<div id="content">
			
			<?php foreach($pageData['items'] as $article): ?>
			
			<h3><?php print $article['title']; ?></h3>
			<h4><?php 
			
				if (!empty($article['user']))
				{
					print 'Posted by ' . $article['user'] . ' on ';
				}
				
				if ($article['time'] != '-')
				{
					print date('F jS, Y', $article['time']) .
					' at ' . date('g:i a', $article['time']);
				}
				
			?></h4>
			<?php print $article['data']; ?>
			
			<p class="newsIndLink"><a href="<?php print $pageData['pageURL']; ?><?php print $article['link']; ?>">Link to this article</a></p>
			
			<hr>
			
			<?php endforeach; ?>
		
		
			<?php if (!empty($pageData['pagination'])): ?>
			
			
			<div id="newsNav">
				<?php if (isset($pageData['pagination']['newOffset'])): ?>
				
				<span class="newerNav"><a href="<?php print $pageData['pageURL']; ?>news/view/<?php print $pageData['pagination']['newOffset']; ?>">&laquo; Newer Articles</a></span>
				
				<?php endif; ?>
				
				<?php if (!empty($pageData['pagination']['sep'])): ?>
				
				|
				
				<?php endif; ?>
				
				<?php if (isset($pageData['pagination']['oldOffset'])): ?>
				
				<span class="olderNav"><a href="<?php print $pageData['pageURL']; ?>news/view/<?php print $pageData['pagination']['oldOffset']; ?>">Older Articles &raquo;</a></span>
				
				<?php endif; ?>
				
				<hr class="newsNavBottom">
			</div>
			<?php endif; ?>
			
		</div>
		
		
		<div id="bottom">
			Powered by <a href="http://northknight.ca/">Osxome</a>, copyright 2008.
			
			<!-- Took: <?php print $pageData['time']; ?> seconds. -->
		</div>
	</div>
</div>

</html>
