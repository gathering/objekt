<?php

class BadgeTemplate {
	
	private static $classes = array();

	static function fontsDir(){
		return path('app')."libraries/badgeTemplates/fonts/";
	}

	static function findTemplate($event_id, $data){
		self::findClasses();

		return isset(self::$classes[$event_id]) ? self::$classes[$event_id]->template($data) : false; 
	}

	private static function findClasses(){

		if(count(self::$classes)) return false;

		$finfo = new finfo(FILEINFO_MIME);

		$dir = new RecursiveDirectoryIterator(path('app').'libraries/badgeTemplates/',
		    FilesystemIterator::SKIP_DOTS);

		// Flatten the recursive iterator, folders come before their files
		$it  = new RecursiveIteratorIterator($dir,
		    RecursiveIteratorIterator::SELF_FIRST);

		// Maximum depth is 1 level deeper than the base folder
		$it->setMaxDepth(1);

		// Basic loop displaying different messages based on file or folder

		$classes = get_declared_classes();

		foreach ($it as $fileinfo) {
		   	if ($fileinfo->isFile() && $fileinfo->getExtension() == "php") {
		        include($fileinfo->getPathname());
		    }
		}

		$classes = array_diff(get_declared_classes(), $classes);

		foreach($classes as $class){
			if(isset($class::$event_id)){
				self::$classes[$class::$event_id] = new $class;
			}
		}
	}

}