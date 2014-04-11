<?php

class Users {
	
	static function findWithTag($tagid){
		$tagid = explode(":", str_replace(" ", "", strtolower($tagid)));
		if(count($tagid) != 2) return false;
		$tag = $tagid[0];
		$id = $tagid[1];

		$event = Config::get('application.event');
		if(method_exists($event->special(), "searchUsers")){
			if(isset($event->special()->tag) && $event->special()->tag == $tag){
				return $event->special()->getUser($id);
			}
		}

		switch($tag){
			case "person":
				return Person::find($id);
			break;
			case "profile":
				return Profile::find($id);
			break;
		}
	}

} 