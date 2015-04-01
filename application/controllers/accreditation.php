<?php

class Accreditation_Controller extends Controller {

	function action_index(){
		
		return View::make('accreditation.index');
	}

	public function action_controll($hash)
	{
		$event = Config::get('application.event');
		if(!$event)
			return Response::error('404');
		
		$person = $event->people()->where('hash', '=', $hash)->first();

		if(!$person)
			die(View::make('controll.denied')->render());

		$entry = $person->entries()
						->where('status', '=', 'valid')
						->where('delivery_date', '>', DB::Raw('NOW()'))
						->where('type', '=', 'badge')
						->first();

		if(!$entry) 
			die(View::make('controll.denied')->render());

		die(View::make('controll.valid')->render());
	}

	public function action_person($profile_slug, $person_slug)
	{
		$profile = profile::find($profile_slug);
		if(!$profile) return Redirect::to(Request::referrer());

		tplConstructor::set(true);

		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		return View::make('accreditation.person_profile')->with("person", $person);
	}
	public function action_child($profile_slug, $person_slug, $child_slug)
	{
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		$child = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		return View::make('accreditation.person_profile')->with("person", $child);
	}
	public function action_wristband($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$input = Input::all();
		$rules = array(
		    'ident' => 'required'
		);

		$entry = Entry::where('ident', '=', $input['ident'])->where('status', '=', 'valid');
		if($entry->count() > 0)
			return Redirect::to(Request::referrer())->with('error', 'Identifikasjonen er allerede brukt, og registrert inn.')->with('post', $input);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);

		$person->status = "arrived";
		$person->save();

		$entry = new Entry;
		$entry->type = "wristband";
		$entry->ident = Input::get('ident');
		$person->entries()->insert($entry);
		Loogie::doo("person", $person, "User «{$person->slug}» at «{$profile->name}» has arrived to stay, equiped with a wristband.");
		$person->sendNotification(__('accreditation.notification.wristband'));

		return Redirect::to("accreditation")->with("success", __('accreditation.registred_arrived', array("name" => $person->firstname." ".$person->surname, "url" => $person->url())));
	}
	public function action_badge($profile_slug, $person_slug, $child_slug=""){

		$profile = Profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}
		return View::make('accreditation.badge')->with("person", $person);
	}
	public function action_post_badge($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$person->status = "arrived";
		$person->save();

		$input = Input::all();
		$rules = array(
		    'delivery_deadline' => 'required|after:'.date("Y-m-d H:i:s", time()-86400)
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
		$entry->delivery_date = date("Y-m-d H:i:s", strtotime($entry->delivery_deadline));
		unset($entry->delivery_deadline);
		
		$person->entries()->update(array('status' => 'denied'));

		$entry = $person->entries()->insert($entry);
		Loogie::doo("person", $person, "User «{$person->slug}» at «{$profile->name}» has arrived for a while, equiped with a badge, departing at {$entry->delivery_date}.");
		$event = Config::get('application.event');
		if(isset($input['automatic'])){
			if($event->has_badge_printer){
				BadgeCreator::printBadge($entry);
			} else {
				BadgeCreator::make($entry);
			}
		}
		$person->sendNotification(__('accreditation.notification.badge'));
		return Redirect::to("accreditation")->with("success", __('accreditation.registred_arrived', array("name" => $person->firstname." ".$person->surname, "url" => $person->url())));
	}
	public function action_save_badge($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		if(!$profile)
			return Redirect::to("accreditation")->with("error", __('accreditation.person_not_found'));

		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug) && $child_slug != "debug"){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}
		$badges = $person->entries()->where("type", "=", "badge")->get();
		if(count($badges) < 1)
			return Redirect::to("accreditation")->with("error", __('accreditation.no_badges_found'));
		$event = Config::get('application.event');
		foreach($badges as $badge){
			if($child_slug == "debug"){
					BadgeCreator::make($badge, false);
					die("……");
			} else {
				if($event->has_badge_printer){
					BadgeCreator::printBadge($badge);
				} else {
					BadgeCreator::make($badge);
				}
			}
		}
		die("Saved!");
	}
	public function action_departed($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		foreach($person->entries()->where("status", "=", "valid")->get() as $entry){
			$entry->status = "denied";
			$entry->save();
		}

		$person->status = "departed";
		$person->save();
		Loogie::doo("person", $person, "User «{$person->slug}» at «{$profile->name}» has departed from the event. {$person->firstname} is not expected to be back again.");
		$person->sendNotification(__('accreditation.notification.departed'));
		return Redirect::to("accreditation")->with("success", __('accreditation.registred_departed', array("name" => $person->firstname." ".$person->surname, "url" => $person->url())));
	}
	public function action_print($profile_slug, $person_slug, $child_slug=""){
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug) && $child_slug != 'debug'){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$badges = $person->entries()->where("type", "=", "badge")->where("status", "=", "valid")->get();
		if(count($badges) < 1)
			return Redirect::to("accreditation")->with("error", __('accreditation.no_badges_found'));

		$event = Config::get('application.event');
		foreach($badges as $badge){
			if($child_slug == "debug"){
					BadgeCreator::make($badge, false);
					die("……");
			} else {
				if($event->has_badge_printer){
					BadgeCreator::printBadge($badge);
				} else {
					BadgeCreator::make($badge);
				}
			}
		}
		$person->sendNotification(__('accreditation.notification.printed'));
		return Redirect::to($person->url('accreditation'))->with("success", __('accreditation.printed'));
	}
}

?>