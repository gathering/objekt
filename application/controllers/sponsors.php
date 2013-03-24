<?php

class Sponsors_Controller extends Controller {
	public function action_index()
	{
		return View::make('sponsors.index');
	}
	public function action_profile($slug)
	{
		$sponsor = Sponsor::find($slug);
		return View::make('sponsors.profile')->with("sponsor", $sponsor);
	}
	public function action_person($sponsor_slug, $person_slug)
	{
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		return View::make('sponsors.person_profile')->with("person", $person);
	}
	public function action_child($sponsor_slug, $person_slug, $child_slug)
	{
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		$child = $sponsor->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		return View::make('sponsors.person_profile')->with("person", $child);
	}
	public function action_add_person()
	{
		return View::make('sponsors.add-person');
	}
	public function action_add_person_sponsor($sponsor_slug)
	{
		$sponsor = Sponsor::find($sponsor_slug);
		if(!$sponsor->exists) return Event::first('404');
		return View::make('sponsors.add-person')->with("sponsor", $sponsor);
	}
	public function slugname_person($person, $sponsor, $i){
		if($i < 1){
			$person->slug = Str::slug($person->firstname." ".$person->surname);
		}
		$slug_exists = Person::where("slug", "=", $person->slug)->where("sponsor_id", "=", $sponsor->id)->count();
		if($slug_exists > 0){
			$i++;
			$person->slug = Str::slug($person->firstname." ".$person->surname." ".$i);
			$slugy = Person::where("slug", "=", $person->slug)->where("sponsor_id", "=", $sponsor->id)->count();
			if($slugy > 0) return $this->slugname_person($person, $sponsor, $i);
			else return $person->slug;
		} else return $person->slug;
	}
	public function slugname_sponsor($sponsor, $i=0){
		if($i < 1){
			$sponsor->slug = Str::slug($sponsor->name);
		}
		$slug_exists = Sponsor::find($sponsor->slug);
		if($slug_exists){
			$i++;
			$sponsor->slug = Str::slug($sponsor->name." ".$i);
			if(!Sponsor::find($sponsor->slug)) return $sponsor->slug;
			return $this->slugname_sponsor($sponsor, $i);
		} else return $sponsor->slug;
	}
	public function action_post_add_person($sponsor_id=""){

		$input = Input::all();
		$rules = array(
		    'firstname'  => 'required|max:255',
		    'surname'  => 'required|max:255',
		    'phone' => 'numeric',
		    'email' => 'email',
		);

		if(empty($sponsor_id)){
			$rules['sponsor'] = 'exists:sponsors,id';
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

		unset($rules['sponsor']);

		$person = new Person;
		foreach($rules as $field => $rule){
			$person->{$field} = $input[$field];
		}

		if(!empty($sponsor_id)){
			$sponsor = Sponsor::find($sponsor_id);
			if(!$sponsor->exists) return Event::first('404');
		} else {
			$sponsor = Sponsor::find($input['sponsor']);
			if(!$sponsor->exists) return Event::first('404');
		}

		$person->slug = $this->slugname_person($person, $sponsor, 0);
		$person->hash = Str::random(32);
		unset($person->i);

		$sponsor->person()->insert($person);
		return Redirect::to("sponsor/".$sponsor->slug)->with("success", "Informasjonen ble lagret!");
	}

	public function action_add(){
		return View::make('sponsors.add');
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

		$sponsor = new Sponsor;
		foreach($rules as $field => $rule){
			$sponsor->{$field} = $input[$field];
		}

		$sponsor->slug = $this->slugname_sponsor($sponsor);
		unset($sponsor->i);
		$sponsor->save();
		return Redirect::to("sponsor/".$sponsor->slug)->with("success", "Informasjonen ble lagret!");
	}
	public function action_add_child($sponsor_slug, $person_slug){
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		return View::make('sponsors.add-person')->with("sponsor", $sponsor)->with("person", $person);
	}
	public function action_post_add_child($sponsor_slug, $person_slug){
		$sponsor = Sponsor::find($sponsor_slug);
		$parent = $sponsor->person()->where("slug", "=", $person_slug)->first();

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

		$person->slug = $this->slugname_person($person, $sponsor, 0);
		$person->hash = Str::random(32);
		unset($person->i);
		$person->parent_id = $parent->id;

		$sponsor->person()->insert($person);
		return Redirect::to("sponsor/".$sponsor->slug."/".$parent->slug)->with("success", "Informasjonen ble lagret!");
	}
}