<?php

class colorPalette {
	static function get($imageFile, $numColors, $granularity = 5) 
	{
		$granularity = max(1, abs((int)$granularity)); 
		$colors = array(); 
		$size = @getimagesize($imageFile); 
		if($size === false) 
		{ 
			return false; 
		} 
		$raw = file_get_contents($imageFile);
		$img = @imagecreatefromstring($raw); 
		if(!$img) 
		{ 
			return false; 
		} 
		for($x = 0; $x < $size[0]; $x += $granularity) 
		{ 
			for($y = 0; $y < $size[1]; $y += $granularity) 
			{ 
				$thisColor = imagecolorat($img, $x, $y); 
				$rgb = imagecolorsforindex($img, $thisColor); 
				$red = round(round(($rgb['red'] / 0x33)) * 0x33);  
				$green = round(round(($rgb['green'] / 0x33)) * 0x33);  
				$blue = round(round(($rgb['blue'] / 0x33)) * 0x33);  
				$thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue);
				if($thisRGB != "FFFFFF" && $thisRGB != "000000"){
					if(array_key_exists($thisRGB, $colors)) 
						$colors[$thisRGB]++; 
					else
						$colors[$thisRGB] = 1;
				}
			} 
		} 
		arsort($colors); 
		return array_slice(array_keys($colors), 0, $numColors);
	}
	static function getContrast($hexcolor){
	    $r = hexdec(substr($hexcolor,0,2));
		$g = hexdec(substr($hexcolor,2,2));
		$b = hexdec(substr($hexcolor,4,2));
		$yiq = (($r*299)+($g*587)+($b*114))/1000;
		return ($yiq >= 128) ? 'black':'white';
	}
	static function getReversedContrast($hexcolor){
	    $r = hexdec(substr($hexcolor,0,2));
		$g = hexdec(substr($hexcolor,2,2));
		$b = hexdec(substr($hexcolor,4,2));
		$yiq = (($r*299)+($g*587)+($b*114))/1000;
		return ($yiq >= 128) ? 'f1f5f9':'1d2d3d';
	}
}