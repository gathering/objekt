<?php

class Role extends Eloquent {
	
	public function users(){
		return $this->has_many('User')->where("deleted", "=", "0");
	}

	static function current(){
		$event = Config::get('application.event');
		return parent::where("event_id", "=", $event->id)->get();
	}

	static function non_current(){
		$event = Config::get('application.event');
		return parent::where("event_id", "!=", $event->id)->get();
	}

}

?>