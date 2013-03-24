<?php

class Sponsor extends Eloquent {
	
	public function role(){
		return $this->belongs_to('Role');
	}

	static function all(){
		return parent::order_by('name', 'asc')->get();
	}

	public function person(){
		return $this->has_many('person')->where("parent_id", "=", "0");
	}

	public function person_x(){
		return $this->has_many('person');
	}

	public function contactpersons(){
		return $this->person()->where("contact_person", "=", "1")->get();
	}

	function save(){
		$event = Config::get('application.event');
		$this->event_id = $event->id;
		parent::save();
	}

	static function find($any){
		$event = Config::get('application.event');
		if(is_numeric($any)){
			return parent::where("id", "=", $any)->where("event_id", "=", $event->id)->first();
		}

		return parent::where("slug", "=", $any)->where("event_id", "=", $event->id)->first();
	}

}

?>