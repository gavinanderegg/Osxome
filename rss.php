<?php


/*
	Osxome v0.2, a text-based news/journal system
	by North Knight Software: http://www.northknight.ca/
	Released under the MIT License:
	  http://www.opensource.org/licenses/mit-license.php
	See LICENSE for more information
	Last modified December 2, 2008
*/


$path = '.' . PATH_SEPARATOR . './phpinc';
ini_set('include_path', $path);


require_once 'Main.class.php';


$main = new Main;


$pageData = $main->getRSSData();


require_once './templates/rss.php';


?>
