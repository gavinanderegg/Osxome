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
	This class reads and parses the config information.
*/


class Config
{
	var $configFile = 'config.php';
	var $data = array();
	
	function Config()
	{
		$configArray = parse_ini_file($this->configFile);
		
		
		while ($option = current($configArray))
		{
			$this->data[key($configArray)] = trim($option);
			
			next($configArray);
		}
	}
	
	
	function data($name)
	{
		$toReturn = '';
		
		if (!empty($this->data[$name]))
		{
			$toReturn = $this->data[$name];
		}
		
		return $toReturn;
	}
}


?>
