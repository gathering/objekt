<?php

class Profiles_Controller extends Controller {
	public function action_index()
	{
		return View::make('profiles.index');
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
		$slug_exists = Person::where("slug", "=", $person->slug)->where("profile_id", "=", $profile->id)->count();
		if($slug_exists > 0){
			$i++;
			$person->slug = Str::slug($person->firstname." ".$person->surname." ".$i);
			$slugy = Person::where("slug", "=", $person->slug)->where("profile_id", "=", $profile->id)->count();
			if($slugy > 0) return $this->slugname_person($person, $profile, $i);
			else return $person->slug;
		} else return $person->slug;
	}
	public function slugname_profile($profile, $i=0){
		if($i < 1){
			$profile->slug = Str::slug($profile->name);
		}
		$slug_exists = profile::find($profile->slug);
		if($slug_exists){
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
		if(isset($input['contact_person']))
			$rules['contact_person'] = true;
		if(isset($input['note']))
			$rules['note'] = true;

		unset($rules['profile']);

		$person = new Person;
		foreach($rules as $field => $rule){
			$person->{$field} = $input[$field];
		}

		if(!empty($profile_id)){
			$profile = profile::find($profile_id);
			if(!$profile->exists) return Event::first('404');
		} else {
			$profile = profile::find($input['profile']);
			if(!$profile->exists) return Event::first('404');
		}

		$person->slug = $this->slugname_person($person, $profile, 0);
		$person->hash = Str::random(32);
		unset($person->i);

		$profile->person()->insert($person);
		return Redirect::to("profile/".$profile->slug)->with("success", "Informasjonen ble lagret!");
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

		$person->slug = $this->slugname_person($person, $profile, 0);
		$person->hash = Str::random(32);
		unset($person->i);
		$person->parent_id = $parent->id;

		$profile->person()->insert($person);
		return Redirect::to("profile/".$profile->slug."/".$parent->slug)->with("success", "Informasjonen ble lagret!");
	}


}