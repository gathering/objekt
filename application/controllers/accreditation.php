<?php

class Accreditation_Controller extends Controller {

	function action_index(){
		
		return View::make('accreditation.index');
	}

	public function action_person($sponsor_slug, $person_slug)
	{
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		return View::make('accreditation.person_profile')->with("person", $person);
	}
	public function action_child($sponsor_slug, $person_slug, $child_slug)
	{
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		$child = $sponsor->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		return View::make('accreditation.person_profile')->with("person", $child);
	}
	public function action_wristband($sponsor_slug, $person_slug, $child_slug=""){
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $sponsor->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$person->status = "arrived";
		$person->save();

		$entry = new Entry;
		$entry->type = "wristband";
		$person->entries()->insert($entry);
		Loogie::doo("person", $person, "User «{$person->slug}» at «{$sponsor->name}» has arrived to stay, equiped with a wristband.");

		return Redirect::to("accreditation")->with("success", __('accreditation.registred_arrived', array("name" => $person->firstname." ".$person->surname, "url" => $person->url())));
	}
	public function action_badge($sponsor_slug, $person_slug, $child_slug=""){
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $sponsor->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}
		return View::make('accreditation.badge')->with("person", $person);
	}
	public function action_post_badge($sponsor_slug, $person_slug, $child_slug=""){
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $sponsor->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$person->status = "arrived";
		$person->save();

		$input = Input::all();
		$rules = array(
		    'badge_id'  => 'required|numeric',
		    'date' => 'required|after:today',
		    'time' => 'required'
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$entry = new Entry;
		foreach($rules as $field => $rule){
			$entry->{$field} = $input[$field];
		}
		$entry->type = "badge";
		$entry->delivery_date = $entry->date." ".$entry->time;
		unset($entry->date, $entry->time);
		$entry = $person->entries()->insert($entry);
		Loogie::doo("person", $person, "User «{$person->slug}» at «{$sponsor->name}» has arrived for a while, equiped with a badge, departing at {$entry->delivery_date}.");
		return Redirect::to("accreditation")->with("success", __('accreditation.registred_arrived', array("name" => $person->firstname." ".$person->surname, "url" => $person->url())));
	}
	public function action_departed($sponsor_slug, $person_slug, $child_slug=""){
		$sponsor = Sponsor::find($sponsor_slug);
		$person = $sponsor->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $sponsor->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		foreach($person->entries()->where("status", "=", "valid")->get() as $entry){
			$entry->status = "denied";
			$entry->save();
		}

		$person->status = "departed";
		$person->save();
		Loogie::doo("person", $person, "User «{$person->slug}» at «{$sponsor->name}» has departed from the event. {$person->firstname} is not expected to be back again.");
		return Redirect::to("accreditation")->with("success", __('accreditation.registred_departed', array("name" => $person->firstname." ".$person->surname, "url" => $person->url())));
	}

}

?>