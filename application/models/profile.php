<?php

class Profile extends Eloquent {
	
	public function role(){
		return $this->belongs_to('Role');
	}

	public function responsible(){
		return User::find($this->user_id);
	}

	public function discounts(){
		return $this->has_many('discount');
	}

	static function all(){
		$event = Config::get('application.event');
		return parent::order_by('name', 'asc')->where("event_id", "=", $event->id)->get();
	}

	static function everyone(){
		return parent::all();
	}

	public function messages(){
		return $this->has_many('message');
	}

	public function tags(){
		return array_unique(array_map('strtolower', array_merge(array($this->slug), explode(" ", $this->name))), SORT_REGULAR);
	}

	protected function Discount_Type(){
		return Discount_Type::where('name', '=', 'Profile');
	}

	function discount(){
		$type = $this->Discount_Type()->first();
		return $this->has_many('discount')->where('type_id', '=', $type->id);
	}

	public function media($take=0){

		$tags = $this->tags();

		$event = Config::get('application.event');
		$params['index'] = 'mediabank';
		$params['type']  = 'image';
		$params['body']['query']['query_string']['query'] = implode(" OR", $tags);
		if($take > 0) $params['body']['filter']['limit']['value'] = $take;
		
		tplConstructor::set(true);
		$elastisk = Elastisk::search($params);
		$results = array();

		if($elastisk['hits']['total'] == 0)
			return array();

		foreach($elastisk['hits']['hits'] as $result){
			$model = Fil3::find($result['_id']);
			if($model) array_push($results, $model);
		}

		return $results;
	}

	public function person(){
		return $this->has_many('person')->where("parent_id", "=", "0");
	}

	public function event(){
		return $this->belongs_to('events', 'event_id');
	}


	public function comments(){
		return $this->has_many('comment', 'belongs_to')->where("type", "=", "profile");
	}

	public function followers(){
		return $this->has_many('following', 'belongs_to')->where("type", "=", "profile");
	}

	public function sendNotification($message){
		$followers = $this->followers()->get();
		foreach($followers as $follower){
			if(isset($follower->user_id) &&
				Auth::user()->id != $follower->user_id)
				Notification::send($follower->user_id, $this->name, $message, $this->url());
		}
	}

	var $locationClass;

	public function location(){
		if(!empty($this->locationClass)) return $this->locationClass;

		$return = new stdClass;
		$return->w = 0;
		$return->h = 0;
		$return->x = 0;
		$return->y = 0;
		$return->x2 = 0;
		$return->y2 = 0;
		$return->pin_x = 0;
		$return->pin_y = 0;

		if(empty($this->location)) return $return;

		$location = unserialize($this->location);

		$return->w = @$location['map']['w'];
		$return->h = @$location['map']['h'];
		$return->x = @$location['map']['x'];
		$return->y = @$location['map']['y'];
		$return->x2 = @$location['map']['x2'];
		$return->y2 = @$location['map']['y2'];
		$return->pin_x = @$location['pin']['x'];
		$return->pin_y = @$location['pin']['y'];

		$this->locationClass = $return;

		return $return;
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
		return 'profile/'.$this->slug;
	}

	public function contactpersons(){
		return $this->person()->where("contact_person", "=", "1")->get();
	}

	public function contacts(){
		return $this->person()->where("contact_person", "=", "1");
	}
	
	function delete(){
		$params['index'] = 'profiles';
		$params['type']  = 'obj';
		$params['id']    = $this->id;
		Elastisk::delete($params);
		return parent::delete();
	}

	function save(){
		$event = Config::get('application.event');
		$this->event_id = $event->id;
		$return = parent::save();

		$params['body']  = $this->to_array();
		unset($params['body']['updated_at']); // Not needed.
		unset($params['body']['created_at']); // Not needed.
		unset($params['body']['location']); // Not needed. (SPECIFIC)

		$params['index'] = 'profiles';
		$params['type']  = 'obj';
		$params['id']    = $this->id;
		Elastisk::index($params);

		return $return;
	}

	function color(){

		if(!empty($this->color) && $this->color != "#")
			return $this->color;

		$logo = $this->logo();
		if(empty($this->logo_url)) return "#000000";

		$color = colorPalette::get($this->logo(), 1);
		$color = isset($color[0]) ? $color[0] : false;
		$this->color = "#".$color;
		if($this->color) $this->save();

		return $color;
	}

	function logo(){
		if(empty($this->website)) return asset('images/firmahval.png');
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

				$curl_handle=curl_init();
				curl_setopt($curl_handle, CURLOPT_URL, $src);
				curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
				curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Objekt');
				$hostFile = curl_exec($curl_handle);
				curl_close($curl_handle);

				if(!$hostFile) return asset('images/firmahval.png');
				file_put_contents($tmp_path, $hostFile);
				if(!exif_imagetype($tmp_path)) {
					unlink($tmp_path);
					return asset('images/firmahval.png');
				}

				$event = Config::get('application.event');
				$filepath = $event->s3_slug."/profiles/".$this->slug.".".$ext;

				// Upload to S3
				S3::putObject(S3::inputFile($tmp_path, false), "s3.obj.no", $filepath, S3::ACL_PUBLIC_READ);

				unlink($tmp_path);

				$this->logo_url = "http://s3.obj.no/".$filepath;
				$this->save();
				return $this->logo_url;
				#Cache::forever('logo_'.$this->slug, $src);
				#return $src;
			}
		}

		return asset('images/firmahval.png');
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