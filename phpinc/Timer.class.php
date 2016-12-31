<?php


/*
	Osxome v0.2, a text-based news/journal system
	by North Knight Software: http://www.northknight.ca/
	Released under the MIT License:
	  http://www.opensource.org/licenses/mit-license.php
	See LICENSE for more information
	Last modified December 2, 2008
*/


class Timer
{
	var $start, $stop, $time;
	
	
	/* If $started is true, start the timer */
	function Timer($started = true)
	{
		if ($started)
		{
			$this->start = microtime(true);
		}
	}
	
	
	/* Start or restart the timer */
	function start()
	{
		$this->start = microtime(true);
	}
	
	
	/* Stop the timer and set the time taken */
	function stop()
	{
		$this->stop = microtime(true);
		
		$this->time = $this->stop - $this->start;
	}
	
	
	/* Stop the timer if it's still going, and get the time taken */
	function getTime()
	{
		if (empty($this->stop))
		{
			$this->stop();
		}
		
		return $this->time;
	}
}


?>
