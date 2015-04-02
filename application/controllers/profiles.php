<?php

class Profiles_Controller extends Controller {
	public function action_index()
	{
		return View::make('profiles.index');
	}

	public function action_logon_as_partner($slug)
	{
		$profile = profile::find($slug);
		if(!$profile) die("This profile was not found, and will not be found - unless its added. (SLUG: {$slug})");
			
		$person = $profile->contacts()->first();
		PartnerAuth::login($person->id);

		return Redirect::to('partner');
	}

	public function action_edit($slug)
	{
		$profile = profile::find($slug);
		if(!$profile) die("This profile was not found, and will not be found - unless its added. (SLUG: {$slug})");
		return View::make('profiles.edit')->with("profile", $profile);
	}

	public function action_post_edit($slug)
	{
		$profile = profile::find($slug);
		if(!$profile) die("This profile was not found, and will not be found - unless its added. (SLUG: {$slug})");

		$input = Input::all();
		$rules = array(
		    'name'  => 'required|max:255',
		    'email' => 'email'
		);

		if(!empty($input['website'])){
			$rules['website'] = 'url';
		    if(!preg_match("/http/", $input['website'])){
				$input['website'] = "http://".$input['website'];
			}
		}

		if(!empty($input['user_id'])) $rules['user_id'] = 'exists:users,id';

		if(!empty($input['logo']['name'])) $rules['logo'] = 'mimes:jpg,gif,png';
		if(!empty($input['color'])) $rules['color'] = 'required';

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		if(!empty($input['logo']['name'])){
			unset($rules['logo']);
			$ext = substr(strrchr($input['logo']['name'],'.'),1);
			$filename = $profile->slug.".".$ext;
			Input::upload('logo', '/tmp/', $filename);

			$event = Config::get('application.event');
			$filepath = $event->s3_slug."/profiles/".$profile->slug.".".$ext;

			// Upload to S3
			S3::putObject(S3::inputFile('/tmp/'.$filename, false), "s3.obj.no", $filepath, S3::ACL_PUBLIC_READ);
			$profile->logo_url = "http://s3.obj.no/".$filepath;
		}

		if(!empty($input['hiddenpin']) && !empty($input['map_location'])){			
			$pin_location = explode("#", $input['hiddenpin']);
			$location['pin']['x'] = @$pin_location[0]-8;
			$location['pin']['y'] = @$pin_location[1]-28;

			$map_location = explode("#", $input['map_location']);
			$location['map']['w'] = @$map_location[0];
			$location['map']['h'] = @$map_location[1];
			$location['map']['x'] = @$map_location[2];
			$location['map']['y'] = @$map_location[3];
			$location['map']['x2'] = @$map_location[4];
			$location['map']['y2'] = @$map_location[5];

			$profile->location = serialize($location);

			Cache::forget('profile-map-'.$profile->id);
		}


		foreach($rules as $field => $rule){
			$profile->{$field} = $input[$field];
		}

		$profile->slug = $this->slugname_profile($profile, 0);
		$profile->save();

		$profile->sendNotification(__('profile.notification.profile_edited'));

		return Redirect::to($profile->url())->with('success', __('user.profile_saved'));
	}

	public function action_profile($slug)
	{
		$profile = profile::find($slug);
		if(!$profile) die("This profile was not found, and will not be found - unless its added. (SLUG: {$slug})");
		tplConstructor::set(true);
		return View::make('profiles.profile')->with("profile", $profile);
	}
	public function action_person($profile_slug, $person_slug)
	{
		$profile = profile::find($profile_slug);
		if(!$profile) return Event::first('404');
		tplConstructor::set(true);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		return View::make('profiles.person_profile')->with("person", $person);
	}
	public function action_child($profile_slug, $person_slug, $child_slug)
	{
		$profile = profile::find($profile_slug);
		tplConstructor::set(true);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		$child = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		return View::make('profiles.person_profile')->with("person", $child);
	}
	public function action_add_person()
	{
		return View::make('profiles.add-person');
	}
	public function action_add_person_profile($profile_slug)
	{
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');
		return View::make('profiles.add-person')->with("profile", $profile);
	}
	public function slugname_person($person, $profile, $i){
		if($i < 1){
			$person->slug = Str::slug($person->firstname." ".$person->surname);
		}
		$slug_exists = Person::where("slug", "=", $person->slug)->where("profile_id", "=", $profile->id)->first();
		if($slug_exists){
			if(@$person->id == $slug_exists->id) return $person->slug;

			$i++;
			$person->slug = Str::slug($person->firstname." ".$person->surname." ".$i);
			$slugy = Person::where("slug", "=", $person->slug)->where("profile_id", "=", $profile->id)->count();
			if($slugy > 0) return $this->slugname_person($person, $profile, $i);
			else return $person->slug;
		} else return $person->slug;
	}
	public function slugname_profile($profile, $i=0, $id=0){
		if($i < 1){
			$profile->slug = Str::slug($profile->name);
		}
		$slug_exists = profile::find($profile->slug);
		if($slug_exists){
			if(@$profile->id == $slug_exists->id) return $profile->slug;

			$i++;
			$profile->slug = Str::slug($profile->name." ".$i);
			if(!profile::find($profile->slug)) return $profile->slug;
			return $this->slugname_profile($profile, $i);
		} else return $profile->slug;
	}
	public function action_post_add_person($profile_id=""){

		$input = Input::all();
		$rules = array(
		    'firstname'  => 'required|max:255',
		    'surname'  => 'required|max:255',
		    'phone' => 'numeric',
		    'email' => 'email',
		);

		if(empty($profile_id)){
			$rules['profile'] = 'exists:profiles,id';
		}

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}
		if(isset($input['note']))
			$rules['note'] = true;

		unset($rules['profile']);

		$person = new Person;
		foreach($rules as $field => $rule){
			$person->{$field} = $input[$field];
		}

		if(isset($input['contact_person']))
			$person->contact_person = '1';
		else
			$person->contact_person = '0';

		if(!empty($profile_id)){
			$profile = profile::find($profile_id);
			if(!$profile->exists) return Event::first('404');
		} else {
			$profile = profile::find($input['profile']);
			if(!$profile->exists) return Event::first('404');
		}

		$event = Config::get('application.event');
		$person->event_id = $event->id;
		$person->slug = $this->slugname_person($person, $profile, 0);
		$person->hash = Str::random(32);
		unset($person->i);
		$person = $profile->person()->insert($person);

		$profile->sendNotification(sprintf(__('profile.notification.new_person'), $person->firstname." ".$person->surname));
		return Redirect::to($person->url())->with("success", "Informasjonen ble lagret!");
	}

	public function action_add(){
		return View::make('profiles.add');
	}
	public function action_post_add(){
		$input = Input::all();
		$rules = array(
		    'name'  => 'required|max:255',
		    'email' => 'email'
		);

		if(!empty($input['website'])){
			$rules['website'] = 'url';
		    if(!preg_match("/http/", $input['website'])){
				$input['website'] = "http://".$input['website'];
			}
		}

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$event = Config::get('application.event');

		$profile = new profile;
		foreach($rules as $field => $rule){
			$profile->{$field} = $input[$field];
		}

		$profile->event_id = $event->id;

		$profile->slug = $this->slugname_profile($profile);
		unset($profile->i);
		$profile->save();
		return Redirect::to("profile/".$profile->slug)->with("success", "Informasjonen ble lagret!");
	}
	public function action_add_child($profile_slug, $person_slug){
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		return View::make('profiles.add-person')->with("profile", $profile)->with("person", $person);
	}
	public function action_post_add_child($profile_slug, $person_slug){
		$profile = profile::find($profile_slug);
		$parent = $profile->person()->where("slug", "=", $person_slug)->first();

		$input = Input::all();
		$input['phone'] = str_replace('+', '00', $input['phone']);

		if(substr($input['phone'], 0, 2) != '00')
		    return Redirect::to(Request::referrer())->with('error', 'Telefonnummeret var feil lagt inn. Dette må begynne med 00XX.')->with('post', $input);


		$rules = array(
		    'firstname'  => 'required|max:255',
		    'surname'  => 'required|max:255',
		    'phone' => 'numeric',
		    'email' => 'email'
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}
		if(isset($input['note']))
			$rules['note'] = true;

		$person = new Person;
		foreach($rules as $field => $rule){
			$person->{$field} = $input[$field];
		}
		$event = Config::get('application.event');
		$person->event_id = $event->id;
		$person->slug = $this->slugname_person($person, $profile, 0);
		$person->hash = Str::random(32);
		unset($person->i);
		$person->parent_id = $parent->id;

		$profile->sendNotification(sprintf(__('profile.notification.new_child'), $person->firstname." ".$person->surname));

		$profile->person()->insert($person);
		return Redirect::to("profile/".$profile->slug."/".$parent->slug)->with("success", "Informasjonen ble lagret!");
	}
	public function action_profile_map($profile_slug)
	{
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');

		header('Content-type: image/jpeg');
		header('Content-Disposition: filename="map-'.$profile->slug.'.jpg"');
		echo Cache::remember('profile-map-'.$profile->id, function() use ($profile) {
			$event = $profile->event()->first();
			$contents = file_get_contents($event->map()->jpg_759->url);
			$map = imagecreatefromstring($contents);
			$map_width = imagesx($map);
 			$map_height = imagesy($map);

			$map = PHPImageWorkshop\ImageWorkshop::initFromResourceVar($map);

			$location = $profile->location();
			$rx = (200 / $location->w);
			$ry = (200 / $location->h);
			$width = $rx * $map_width;
			$height = $ry * $map_height;
			$map->resizeInPixel($width, $height, false, 0, 0, 'MM');
			
			$layer = PHPImageWorkshop\ImageWorkshop::initVirginLayer(200, 200, "000000");
			$layer->addLayerOnTop($map, -($rx * $location->x), -($rx * $location->y), 0);

			// Pin
			$pin = PHPImageWorkshop\ImageWorkshop::initFromPath(path('public').'img/map-pin.png');
			$layer->addLayerOnTop($pin, $location->pin_x, $location->pin_y); 
			
			$image = $layer->getResult();
			ob_start();
			imagejpeg($image, null, 95); // We choose to show a JPEG (quality of 95%)
			$return = ob_get_contents();
			ob_end_clean();
			return $return;
		}, 10);
		
		exit;
	}
	public function action_delete($profile_slug){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');

		return View::make('profiles.delete')->with("profile", $profile);
	}
	public function action_post_delete($profile_slug){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');

		$password = Input::get('password');
		$user = Auth::user();
		if(!Hash::check($user->salt . $password, $user->password))
			return Redirect::to(Request::referrer())->with("error", __('profile.incorrect_password'));

		$profile->sendNotification(sprintf(__('profile.notification.deleted_profile'), $user->username));

		$profile->person_x()->delete();
		$profile->delete();

		return Redirect::to("/")->with("success", __('profile.deleted'));
	}
	public function action_post_comment($profile_slug)
	{
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');

		$commentContent = Input::get('comment');
		if(empty($commentContent)) return Redirect::to($profile->url())->with("error", __('profile.comment_empty'));

		$comment = new Comment;
		$comment->comment = $commentContent;
		$comment->user_id = Auth::user()->id;
		$comment->type = "profile";
		$profile->comments()->insert($comment);

		$profile->sendNotification(sprintf(__('profile.notification.new_comment'), Auth::user()->username));

		return Redirect::to($profile->url())->with("success", __('profile.comment_posted'));
	}
	public function action_person_follow($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$user = User::find(Auth::user()->id);
		$user->following()->where("type", "=", "person")->where("belongs_to", "=", $person->id)->delete();
		$follow = new Following;
		$follow->type = "person";
		$follow->belongs_to = $person->id;
		$user->following()->insert($follow);
		die("true");
	}
	public function action_person_not_follow($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$user = User::find(Auth::user()->id);
		$user->following()->where("type", "=", "person")->where("belongs_to", "=", $person->id)->delete();
		die("true");
	}
	public function action_make_contactperson($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$person->contact_person = 1;
		$person->save();

		$person->sendNotification(sprintf(__('profile.notification.person_made_contactperson'), $profile->name));

		return Redirect::to($person->url())->with("success", __('profile.made_contactperson'));
	}
	public function action_delete_comment($profile_slug, $comment_id){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');

		$comment = Comment::find($comment_id);
		if(!$comment->exists) return Event::first('404');

		$comment->delete();
		$profile->sendNotification(__('profile.notification.deleted_comment'));

		return Redirect::to($profile->url())->with("success", __('profile.deleted_comment'));
	}
	public function action_person_edit($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}
		tplConstructor::set(true);
		return View::make('profiles.person_edit')->with("profile", $profile)->with("person", $person);
	}
	public function action_post_person_edit($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$input = Input::all();

		$input['phone'] = str_replace('+', '00', $input['phone']);

		if(substr($input['phone'], 0, 2) != '00')
		    return Redirect::to(Request::referrer())->with('error', 'Telefonnummeret var feil lagt inn. Dette må begynne med 00XX.')->with('post', $input);

		$rules = array(
		    'firstname'  => 'required|max:255',
		    'surname'  => 'required|max:255',
		    'phone' => 'numeric',
		    'email' => 'email',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		if(isset($input['note']))
			$rules['note'] = true;

		foreach($rules as $field => $rule){
			$person->{$field} = $input[$field];
		}

		if(isset($input['contact_person']))
			$person->contact_person = '1';
		else
			$person->contact_person = '0';

		$person->slug = $this->slugname_person($person, $profile, 0);
		unset($person->i);
		$person->save();

		$person->sendNotification(__('profile.notifications.person_edited'));
		return Redirect::to($person->url())->with("success", "Informasjonen ble lagret!");
	}

	public function action_person_delete($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}
		return View::make('profiles.person_delete')->with("person", $person);
	}
	public function action_post_person_delete($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$password = Input::get('password');
		$user = Auth::user();
		if(!Hash::check($user->salt . $password, $user->password))
			return Redirect::to(Request::referrer())->with("error", __('profile.incorrect_password'));

		$person->sendNotification(sprintf(__('profile.notification.deleted_person'), $user->username));

		$person->delete();

		return Redirect::to($profile->url())->with("success", __('profile.person_deleted'));
	}
	public function action_messages($profile_slug){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');

		return View::make('profiles.messages')->with('profile', $profile);
	}
}