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
	This class initalises the system and keeps state.
*/


require_once 'Config.class.php';
require_once 'News.class.php';
require_once 'Timer.class.php';


class Main
{
	var $timer, $config, $news;
	var $page, $action, $offset;
	
	
	function Main()
	{
		$this->timer = new Timer;
		$this->config = new Config;
		
		$query = $_GET['q'];
		
		if (!empty($query))
		{
			$queryArray = explode('/', $query);
			
			$this->page = $queryArray[0];
			$this->action = $queryArray[1];
			$this->offset = $queryArray[2];
		}
		else
		{
			$this->page = $_GET['page'];
			$this->action = $_GET['action'];
			$this->offset = $_GET['offset'];
		}
		
		$actionList = array
		(
			'view',
			'article'
		);
		
		if (empty($this->action) || !in_array($this->action, $actionList))
		{
			$this->action = 'view';
			$this->offset = 0;
		}
		
		$this->news = new News($this->config->data('newsDir'), $this->config->data('itemsPerPage'), $this->action, $this->offset);
	}
	
	
	/* Creates and returns an array with all data the HTML template will display. */
	function getPageData()
	{
		$toReturn = array();
		
		$toReturn['title'] = $this->config->data('title');
		$toReturn['rootDir'] = $this->config->data('rootDir');
		$toReturn['pageURL'] = $this->config->data('pageURL');
		
		if ($this->action == 'article')
		{
			$toReturn['items'] = $this->news->getIndArticle($this->offset);
		}
		else
		{
			$this->news->populateFilesArray();
			$toReturn['items'] = $this->news->getArticles();
			$toReturn['pagination'] = $this->news->getPagination();
		}
		
		$toReturn['time'] = $this->timer->getTime();
		
		return $toReturn;
	}
	
	
	/* Similar to getPageData(), but creates and returns an array of data for the RSS template instead of the HTML template. */
	function getRSSData()
	{
		$toReturn = array();
		
		$toReturn['title'] = $this->config->data('title');
		$toReturn['rootDir'] = $this->config->data('rootDir');
		$toReturn['pageURL'] = $this->config->data('pageURL');
		
		$this->news->populateFilesArray();
		$toReturn['items'] = $this->news->getArticles(true); // true for RSS
		
		return $toReturn;
	}
}


?>
