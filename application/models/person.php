<?php

class Person extends Eloquent {
	function user(){
		return $this->firstname." ".$this->surname;
	}
	function profile(){
		return $this->belongs_to('profile')->first();
	}
	function child(){
		return $this->has_many("person", "parent_id");
	}
	function is_current_event(){
		$profile = $this->profile();
		$event = Config::get('application.event');
			
		if($profile->event_id == $event->id){
			return true;
		} else return false;
	}
	public function logs(){
		return $this->has_many('logg', 'related_id')->where("type", "=", "person")->order_by("created_at", "desc");
	}
	function entries(){
		return $this->has_many("entry");
	}
	function url($url="profile"){
		$profile = $this->profile();

		if($this->is_child()){
			return URL::to($url.'/'.$this->profile()->slug.'/'.$this->parent()->slug.'/'.$this->slug);
		} else {
			return URL::to($url.'/'.$this->profile()->slug.'/'.$this->slug);
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
	function gravatar($size=36){
		$email = $this->email;
        $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size . "&d=http%3A%2F%2Fsupersponsor.no%2Fimages%2Fdefault_profile.png";
		return $grav_url;
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