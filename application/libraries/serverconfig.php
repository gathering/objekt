<?php


class serverConfig {
	
	static protected $config;
	static protected $fileParsed = false;

	static function get(){
		if(!self::$fileParsed)
			self::getFromFile();
		
		return self::$config;
	}

	static function set(){
		// TODO: Add a setter function.
	}

	static protected function getFromFile(){

		$content = file_get_contents(path('base').'.server.config'); 
		if(!$content) return false;

		$config = json_decode($content);
		if(!$config) return false;

		self::$config = $config;
		self::$fileParsed = true;

		return true;
	}

}