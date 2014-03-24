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

	public function event(){
		return $this->belongs_to('events', 'event_id');
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

		if(!empty($this->color))
			return $this->color;

		$color = colorPalette::get($this->logo(), 1);
		$color = isset($color[0]) ? $color[0] : false;
		$this->color = "#".$color;
		if($this->color) $this->save();

		return $color;
	}

	function logo(){
		if(empty($this->website)) return false;
		if(!empty($this->logo_url)) return $this->logo_url;
		
		$html = httpAsset::get($this->website);

		$doc = new DOMDocument();
		@$doc->loadHTML($html);

		$tags = $doc->getElementsByTagName('img');

		foreach ($tags as $tag) {
			$src = $tag->getAttribute('src');
			if(preg_match("/logo/", $src)){
				$host = parse_url($this->website);
				$src = preg_match("/http/", $src) ? $src : "http://".$host['host'].$src;

				$ext = substr(strrchr($src,'.'),1);
				$tmp_path = "/tmp/".$this->slug.".".$ext;
				file_put_contents($tmp_path, file_get_contents($src));

				$event = Config::get('application.event');
				$filepath = $event->s3_slug."/profiles/".$this->slug.".".$ext;

				// Upload to S3
				S3::putObject(S3::inputFile($tmp_path, false), "s3.obj.no", $filepath, S3::ACL_PUBLIC_READ);
				$this->logo_url = "http://s3.obj.no/".$filepath;
				$this->save();
				return $this->logo_url;
				#Cache::forever('logo_'.$this->slug, $src);
				#return $src;
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