<?php

class Profile extends Eloquent {
	
	public function role(){
		return $this->belongs_to('Role');
	}

	static function all(){
		$event = Config::get('application.event');
		return parent::order_by('name', 'asc')->where("event_id", "=", $event->id)->get();
	}

	static function everyone(){
		return parent::all();
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
		return URL::to('profile/'.$this->slug);
	}

	public function contactpersons(){
		return $this->person()->where("contact_person", "=", "1")->get();
	}

	function save(){
		$event = Config::get('application.event');
		$this->event_id = $event->id;
		parent::save();
	}

	function color(){
		$logo = $this->logo();
		if(!$logo) return false;

		if (Cache::has('color_'.$this->slug))
			return Cache::get('color_'.$this->slug);

		$color = colorPalette::get($this->logo(), 1);
		$color = isset($color[0]) ? $color[0] : false;
		Cache::forever('color_'.$this->slug, $color);
		return $color;
	}

	function logo(){
		if(empty($this->website)) return false;
		if (Cache::has('logo_'.$this->slug))
			return Cache::get('logo_'.$this->slug);

		$html = httpAsset::get($this->website);

		$doc = new DOMDocument();
		@$doc->loadHTML($html);

		$tags = $doc->getElementsByTagName('img');

		foreach ($tags as $tag) {
			$src = $tag->getAttribute('src');
			if(preg_match("/logo/", $src)){
				$host = parse_url($this->website);
				$src = preg_match("/http/", $src) ? $src : "http://".$host['host'].$src;
				Cache::forever('logo_'.$this->slug, $src);
				return $src;
			}
		}
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