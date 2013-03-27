<?php

class Person extends Eloquent {
	function user(){
		return $this->firstname." ".$this->surname;
	}
	function sponsor(){
		return $this->belongs_to('sponsor')->first();
	}
	function child(){
		return $this->has_many("person", "parent_id");
	}
	function is_current_event(){
		$sponsor = $this->sponsor();
		$event = Config::get('application.event');
			
		if($sponsor->event_id == $event->id){
			return true;
		} else return false;
	}
	public function logs(){
		return $this->has_many('logg', 'related_id')->where("type", "=", "person")->order_by("created_at", "desc");
	}
	function entries(){
		return $this->has_many("entry");
	}
	function url($url="sponsor"){
		$sponsor = $this->sponsor();

		if($this->is_child()){
			return URL::to($url.'/'.$this->sponsor()->slug.'/'.$this->parent()->slug.'/'.$this->slug);
		} else {
			return URL::to($url.'/'.$this->sponsor()->slug.'/'.$this->slug);
		}
	}
	function parent(){
		if(!$this->is_child()) return false;
		return self::find($this->parent_id);
	}
	public function is_child(){
		return $this->parent_id > 0 ? true : false;
	}
	public function is_parent(){
		return $this->parent_id > 0 ? false : true;
	}
	function num_child(){
		return $this->child()->count();
	}
	static public function find($any){
		if(is_numeric($any)){
			return parent::where("id", "=", $any)->first();
		}

		return parent::where("slug", "=", $any)->first();
	}
}

?>