<?php

class Events extends Eloquent {
	
	public static $table = 'events';
	public $featuresArray = array();

	static function current(){
		return Config::get('application.event');
	}

	public function s3_slug(){
		$s3_slug = $this->get_attribute('s3_slug');
		if(empty($s3_slug)){
			$this->s3_slug = $this->slug;
			$this->save();
		} else {
			return $s3_slug;
		}
	}

	public function files($type=""){
		if(empty($type))
			return $this->has_many('fil3', 'event_id');

		return $this->has_many('fil3', 'event_id')->where("type", "=", $type);
	}

	public function get_s3_slug(){
		return $this->s3_slug();
	}

	public function people(){
		return $this->has_many('person', 'event_id');
	}

	public function entries(){
		return Entry::left_join('people', 'people.id', '=', 'entries.person_id')
				->where("entries.person_id", "=", DB::Raw('people.id'))
				->where("people.event_id", "=", $this->id);
	}

	public function aid(){
		if(empty($this->aid_users)) return false;
		$user_ids = explode(",", $this->aid_users);
		return User::active()->where("pushover_key", "!=", "")->where_in('users.id', $user_ids)->get();
	}
	
	public function tags(){
		return explode(",", $this->tags);
	}

	public function features(){
		
		if(count($this->featuresArray) > 0) return $this->featuresArray;

		if(empty($this->features)) return array();

		// TODO: Make a more centralized location for the features array. This can be linked with settings page.
		$features = array(
			'mediabank' => false,
			'profiles' => false,
			'accreditation' => false,
			'sms' => false,
			'logistics' => false,
			'helpdesk' => false,
			'chat' => false
			);

		foreach(unserialize($this->features) as $feature => $switch){
			$features[$feature] = ($switch == "on" OR $switch == "1") ? true : false;
		}

		$this->featuresArray = $features;

		return $features;
	}

	public function hasFeature($feature){
		$features = $this->features();
		return isset($features[$feature]) ? $features[$feature] : false;
	}

	public function map(){
		$map = new StdClass;
		$map->pdf = $this->files("map")->first();
		if(!$map->pdf) return false;
		if($map->pdf->converted == "1"){
			$map->jpg = $this->files("map")->first();
			if(!$map->jpg) return false;
		}

		return $map;
	}

}

?>