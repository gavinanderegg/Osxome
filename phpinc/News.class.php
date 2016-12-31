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
	This class deals with loading and parsing the news data.
*/


require_once 'Config.class.php';


class News
{
	var $config;
	var $newsDir, $itemsPerPage, $offset, $action;
	var $filesArray;
	
	var $titleLine = 0;
	var $userLine = 1;
	var $timeLine = 2;
	
	
	function News($newsDir, $itemsPerPage, $action, $offset)
	{
		$this->config = new Config();
		
		$this->newsDir = $newsDir;
		$this->itemsPerPage = $itemsPerPage;
		$this->action = $action;
		$this->offset = $offset;
	}
	
	
	/* Make an array of the files in the news directory, and sort the array by date. The array is stored as an instance variable. */
	function populateFilesArray()
	{
		$dir = new DirectoryIterator($this->newsDir);
		
		$addCount = 1;
		
		while ($dir->valid())
		{
			if
			(
				!(substr($dir->getFilename(), 0, 1) == '.') &&
				(substr(strrev($dir->getFilename()), 0, 4) == 'txt.') &&
				($dir->isReadable($dir->getFilename()))
			)
			{
				$fileTime = $this->getFileTime($dir->getFilename());
				
				if (!empty($fileTime))
				{
					/* fakeTime is the time used if two articles have the exact same Unix timestamp. */
					$fakeTime = 0;
					
					if (!empty($this->filesArray[strtotime($fileTime)]))
					{
						while (!empty($this->filesArray[strtotime($fakeTime)]))
						{
							$fakeTime = strtotime($fileTime) + $addCount;
							
							$addCount++;
						}
						
						$this->filesArray[$fakeTime] = $dir->getFilename();
					}
					else
					{
						$this->filesArray[strtotime($fileTime)] = $dir->getFilename();
					}
				}
				else
				{
					$fakeTime = 0;
					
					if (!empty($this->filesArray[$dir->getMTime()]))
					{
						while (!empty($this->filesArray[strtotime($fakeTime)]))
						{
							$fakeTime = strtotime($fileTime) + $addCount;
							
							$addCount++;
						}
						
						$this->filesArray[$fakeTime] = $dir->getFilename();
					}
					else
					{
						$this->filesArray[$dir->getMTime()] = $dir->getFilename();
					}
				}
			}
			
			$dir->next();
		}
		
		if ($this->filesArray > 1)
		{
			krsort($this->filesArray);
		}
	}
	
	
	/* Gets the articles for the current page and returns them as an array. */
	function getArticles($rss = false)
	{
		$toReturn = array();
		
		if ($this->filesArray > 0)
		{
			$thisPageArray = array_slice($this->filesArray, ($this->offset * $this->itemsPerPage), $this->itemsPerPage, true);
			
			while ($item = current($thisPageArray))
			{
				$toReturn[] = $this->getPost($item, key($thisPageArray), $rss);
				
				next($thisPageArray);
			}
		}
		
		return $toReturn;
	}
	
	
	/* Gets a single article based on the provided offset. Returns the article as an array, or returns an error array if the offset didn't match an available file. */
	function getIndArticle($offset)
	{
		$toReturn = array();
		
		$fileID = urldecode($offset);
		$file = $fileID . '.txt';
		$fullPath = $this->newsDir . '/' . $file;
		
		if (is_file($fullPath) && is_readable($fullPath))
		{
			$time = filemtime($fullPath);
			
			$toReturn[] = $this->getPost($file, $time);
		}
		else
		{
			$toReturn[0]['time'] = '-';
			$toReturn[0]['title'] = 'No such article';
			$toReturn[0]['data'] = '<p>No such article</p>';
		}
		
		return $toReturn;
	}
	
	
	function getPost($fileName, $time, $rss = false)
	{
		$toReturn = array();
		
		$noTxt = str_replace('.txt', '', $fileName);
		$linkHref = $this->config->data('indHref') . urlencode($noTxt);
		$fileToOpen = $this->newsDir . '/' . $fileName;
		
		$fileData = file($fileToOpen);
		
		$toReturn['title'] = trim($fileData[$this->titleLine]);
		$userText = $fileData[$this->userLine];
		$fileTime = trim($fileData[$this->timeLine]);
		$toReturn['user'] = $this->getUser($userText);
		
		if (empty($fileTime))
		{
			$toReturn['time'] = $this->offsetTime($time, false);
		}
		else
		{
			$toReturn['time'] = $this->offsetTime($fileTime, true);
		}
		
		$data = '';
		
		for ($line = 3; $line < count($fileData); $line++)
		{
			$data .= $fileData[$line];
		}
		
		if ($rss)
		{
			$data = htmlentities($data, ENT_QUOTES);
		}
		
		$toReturn['data'] = $data;
		
		$toReturn['link'] = $linkHref;
		
		return $toReturn;
	}
	
	
	/* Returns the time set in the news post's file. This time isn't offset by the timeOffset variable set in the config.php file. */
	function getFileTime($fileName)
	{
		$fileToOpen = $this->newsDir . '/' . $fileName;
		
		$fileArray = file($fileToOpen);
		
		return chop($fileArray[$this->timeLine]);
	}
	
	
	/* Returns an array of data used to set up the page navigation. */
	function getPagination()
	{
		$toReturn = null;
		
		$prev = ($this->offset > 0);
		$next = (count($this->filesArray) > ($this->itemsPerPage * ($this->offset + 1)));
		
		if ($prev || $next)
		{
			$toReturn = array();
			
			if ($prev)
			{
				$toReturn['newOffset'] = ($this->offset - 1);
			}
			
			if ($next)
			{
				if ($prev)
				{
					$toReturn['sep'] = true;
				}
				
				$toReturn['oldOffset'] = ($this->offset + 1);
			}
		}
		
		return $toReturn;
	}
	
	
	/* Gets and returns the username (if available) from a news post. */
	function getUser($userText)
	{
		$toReturn = '';
		
		$userText = trim($userText);
		
		if (!empty($userText))
		{
			$toReturn .= $userText;
		}
		
		return $toReturn;
	}
	
	
	function offsetTime($time, $fileTime = true)
	{
		$timeOffset = $this->getTimeOffset();
		
		if (empty($time))
		{
			$utime = time();
		}
		else
		{
			if ($fileTime)
			{
				$offsetTime = 0;
				
				$utime = strtotime($time);
			}
			else
			{
				$sign = substr($timeOffset, 0, 1);
				$hour = substr($timeOffset, 1, 2) * 60 * 60;
				$min = substr($timeOffset, 3, 2) * 60;
				$seconds = $hour + $min;
				
				if ($sign == '+')
				{
					$offsetTime = +$seconds;
				}
				else
				{
					$offsetTime = -$seconds;
				}
				
				$utime = $time;
			}
		}
		
		return $utime + $offsetTime;
	}
	
	
	function getTimeOffset()
	{
		$offset = $this->config->data['timeOffset'];
		
		$pattern = '/(\-|\+)\d{4}/';
		
		if (!preg_match($pattern, $offset))
		{
			$offset = '+0000';
		}
		
		return $offset;
	}
}


?>
