<?php

class Dir
{
	public static function read($path)
	{
		$contents = array();
		if (is_dir($path) AND $handle = opendir($path)) {
			while (FALSE !== ($entry = readdir($handle))) {
		        if ($entry != "." && $entry != "..") $contents[] = $entry;
		    }
		    closedir($handle);
		}

		return $contents;
	}
}