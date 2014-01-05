<?php

class Eventuser extends Eloquent {
	public static $table = 'event_users';

	static function current(){
		$event = Config::get('application.event');
		return self::where("event_id", "=", $event->id);
	}
	function users(){
		return $this->has_many('users');
	}
}

?>