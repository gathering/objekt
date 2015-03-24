<?php

class Events extends Eloquent {
	
	public static $table = 'events';
	public $featuresArray = array();

	static function current(){
		return Config::get('application.event');
	}

	public function roles(){
		return $this->has_many('role', 'event_id');
	}

	public function totalDiskUse(){
		return round($this->files()->select(array(DB::Raw('SUM(`size`) as `total_size_use`')))->first()->total_size_use/(1024*1024*1024), 2, PHP_ROUND_HALF_UP);
	}

	public function storages(){
		return $this->has_many('storage', 'event_id');
	}

	public function calculateMonths(){
		return array();
		$files = $this->files()
					->select(
						array(
							DB::Raw('SUM(`size`) as `total_size_use`'),
							DB::Raw('DATE_FORMAT(created_at, "%Y-%m") AS Month')
							)
						)
					->group_by(DB::Raw('DATE_FORMAT(created_at, "%Y-%m")'))
					->get();

		$startFile = $this->files()->order_by('created_at', 'asc')->first('created_at');
		$endFile = $this->files()->order_by('created_at', 'desc')->first('created_at');

		$start = strtotime($startFile->created_at);
		$end = strtotime($endFile->created_at);



		$months = array();
		$months[date('Y-m', $start)] = array();
		$month = $start;
		while($month <= $end){
			$month = strtotime("+1 month", $month);
			$months[date('Y-m', $month)] = array();
		}

		foreach($files as $month){
			$months[$month->month] = array('diskuse' => $month->total_size_use);
		}

		$diskuse = 0;
		$totals = 0;
		foreach($months as $month => $data){
			$diskuse += isset($data['diskuse']) ? round(($data['diskuse']/(1024*1024*1024)), 2, PHP_ROUND_HALF_UP) : 0;
			$total = 0.19*$diskuse;
			$totals += $total;
			$months[$month] = array(
				'diskuse' => $diskuse,
				'total' => $total
				);
		}

		$result['diskusage'] = $months;
		$result['diskuse'] = $diskuse;

		$requests = $months;
		foreach($requests as $month => $data){
			$total = 0.04*5;
			$totals += $total;
			$requests[$month] = array(
				'total' => $total
				);
		}
		$result['requests'] = $requests;
		$result['subtotal'] = $totals; 
		return $result;
	}

	public function users(){
		return User::left_join('role_user', 'role_user.user_id', '=', 'users.id')
					->left_join('roles', 'roles.id', '=', 'role_user.role_id')
					->where('roles.event_id', '=', $this->id)
					->or_where('role_user.role_id', '=', 1)
					->select('users.*');
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
		return Person::left_join('profiles', 'profiles.id', '=', 'people.profile_id')
					   ->select('people.*')->where('profiles.event_id', '=', $this->id);
	}

	public function profiles(){
		return $this->has_many('profile', 'event_id');
	}

	public function entries(){
		return Entry::left_join('people', 'people.id', '=', 'entries.person_id')
				->left_join('profiles', 'profiles.id', '=', 'people.profile_id')
				->where('profiles.event_id', '=', $this->id);
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
			$map->jpg = $map->pdf->childs()->where("type", "=", "jpg-map")->first();
			if(!$map->jpg) return false;

			$map->jpg_759 = $map->pdf->childs()->where("type", "=", "jpg-759-map")->first();
			if(!$map->jpg) return false;
		}

		return $map;
	}

	function primary_contact()
	{
		return User::find($this->primary_contact);
	}

	function products(){
		return $this->has_many('product', 'event_id');
	}

	private $specialClass;

	function special(){

		if(isset($this->specialClass)) return $this->specialClass;

		$finfo = new finfo(FILEINFO_MIME);
		$dir = new RecursiveDirectoryIterator(path('app').'libraries/events/',
		    FilesystemIterator::SKIP_DOTS);

		// Flatten the recursive iterator, folders come before their files
		$it  = new RecursiveIteratorIterator($dir,
		    RecursiveIteratorIterator::SELF_FIRST);

		// Maximum depth is 1 level deeper than the base folder
		$it->setMaxDepth(2);

		include(path('app').'libraries/events/eventTemplate.php');

		foreach ($it as $fileinfo) {
		   	if ($fileinfo->isFile()
		   		&& $fileinfo->getExtension() == "php"
		   		&& $fileinfo->getFilename() == $this->slug.".php") {
		        include($fileinfo->getPathname());
		    	$reflection_class = new ReflectionClass($this->slug);
    			return $this->specialClass = $reflection_class->newInstance();
		    }
		}

		return false;
	}

}

?>