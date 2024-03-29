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
	public function followers(){
		return $this->has_many('following', 'belongs_to')->where("type", "=", "person");
	}

	public function delete(){
		$params['index'] = 'people';
		$params['type']  = 'obj';
		$params['id']    = $this->id;
		Elastisk::delete($params);
		return parent::delete();
	}

	public function _save(){
		return parent::save();
	}

	public function save(){
		$event = Config::get('application.event');
		$this->event_id = $event->id;
		$return = parent::save();

		$params['body']  = $this->to_array();
		unset($params['body']['updated_at']); // Not needed.
		unset($params['body']['created_at']); // Not needed.

		$params['index'] = 'people';
		$params['type']  = 'obj';
		$params['id']    = $this->id;
		Elastisk::index($params);

		return $return;
	}

	public function sendNotification($message){

		$sentTo = array();

		$followers = $this->followers()->get();
		foreach($followers as $follower){
			if(Auth::user()->id != $follower->user_id){
				Notification::send($follower->user_id, $this->firstname." ".$this->surname, $message, $this->url());
				$sentTo[$follower->user_id] = true;
			}
		}

		$followers = $this->profile()->followers()->get();
		foreach($followers as $follower){
			if(Auth::user()->id != $follower->user_id && isset($sentTo[$follower->user_id]))
				Notification::send($follower->user_id, $this->firstname." ".$this->surname, $message, $this->url());
		}
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
	function validEntries(){
		return $this->entries()
					->where('status', '=', 'valid')
					->where(function($query){
						$query->where('type', '=', 'wristband');
					})->or_where(function($query){
						$query->where('type', '=', 'badge');
						$query->where('delivery_date', '>', DB::Raw('NOW()'));
					});
	}
	function url($url="profile"){
		$profile = $this->profile();

		if($this->is_child()){
			return $url.'/'.$this->profile()->slug.'/'.$this->parent()->slug.'/'.$this->slug;
		} else {
			return $url.'/'.$this->profile()->slug.'/'.$this->slug;
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
        $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size . "&d=".urlencode('http://s3.obj.no/default_profile.png');
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