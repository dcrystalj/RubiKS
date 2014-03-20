<?php

class Help
{
	
	public static function country($cid)
	{
		switch($cid) {
			case 'SI':
				return 'Slovenija';
				break;
				
			case 'HR':
				return 'Hrvaška';
				break;

			case 'ES':
				return 'Španija';
				break;
				
			case 'FR':
				return 'Francija';
				break;
				
			case 'BR':
				return 'Brazilija';
				break;
				
			default:
				return $cid;
				break;
		}
	}
}