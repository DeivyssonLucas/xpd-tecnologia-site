<?php
namespace T4\Helper;

use Joomla\CMS\Factory as JFactory;

class T4Bootstrap {
	public static function getAttrs($data = array()){
		if(empty($data)) return "";
		$attribs = " ";
		foreach ($data as $name => $value) {
			if(J3J4::isJ4() || defined('T4_BS5')){
				$attribs .= "data-bs-".$name."=".$value;
			}else{
				$attribs .= "data-".$name."=".$value;
			}
			$attribs .= " ";
		}
		return $attribs;
	}
}
