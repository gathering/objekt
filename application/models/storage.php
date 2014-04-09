<?php

class Storage extends Eloquent {
	static function findBySlug($slug){
		$event = Config::get('application.event');
		return self::where("slug", "=", $slug)->where("event_id", "=", $event->id)->first();
	}
	static function current(){
		$event = Config::get('application.event');
		return self::where("event_id", "=", $event->id)->get();
	}
}

?>