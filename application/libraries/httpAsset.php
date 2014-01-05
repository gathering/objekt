<?php

class httpAsset {
	static function get($url){
		$ch = curl_init(); 
		$timeout = 0; 
		curl_setopt ($ch,	CURLOPT_URL, $url); 
		curl_setopt ($ch,	CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch,	CURLOPT_CONNECTTIMEOUT, $timeout); 
		curl_setopt($ch, 	CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,	CURLOPT_ENCODING, "gzip");
		curl_setopt($ch,	CURLOPT_HTTPHEADER, array(
			'Accept-Encoding: gzip,deflate',
			'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'));
		$file_contents = curl_exec($ch); 
		curl_close($ch); 
		return $file_contents;
	}
}

?>