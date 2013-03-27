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

	public function logs(){
		return $this->has_many('logg', 'related_id')->where("type", "=", "sponsor")->order_by("created_at", "desc");
	}

	public function person_x(){
		return $this->has_many('person');
	}
	function is_current_event(){
		$event = Config::get('application.event');

		if($this->event_id == $event->id){
			return true;
		} else return false;
	}
	public function url(){
		return URL::to('sponsor/'.$this->slug);
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