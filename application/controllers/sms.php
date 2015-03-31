<?php

/*Route::group(array('before' => 'auth|can_sms|event'), function()
{

	Route::get('/sms/(:any)/(:any)', 'sms@person');
	Route::post('/sms/(:any)/(:any)', 'sms@post_person');
	Route::get('/sms/(:any)/(:any)/(:any)', 'sms@person');
	Route::post('/sms/(:any)/(:any)/(:any)', 'sms@post_person');

	Route::get('/sms/send/(:any)/(:any)', 'sms@person');
	Route::post('/sms/send/(:any)/(:any)', 'sms@post_person');
	Route::get('/sms/send/(:any)/(:any)/(:any)', 'sms@person');
	Route::post('/sms/send/(:any)/(:any)/(:any)', 'sms@post_person');
});*/

class SMS_Controller extends Base_Controller {

	public $restful = true;

	public function get_inbox($profile_slug, $person_slug, $child_slug="")
	{
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();

		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		if(!$profile)
			return Redirect::to($person->url())->with('error', __("profile.not_found"));

		if(!$person)
			return Redirect::to($person->url())->with('error', __("person.not_found"));

		$sms = SMS::where('person_id', '=', $person->id)->order_by('created_at', 'desc')->get();

		tplConstructor::set(true);

		return View::make('sms/inbox')->with('sms', $sms)->with('person', $person);
	}

	public function get_send($profile_slug, $person_slug, $child_slug="")
	{
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}
		tplConstructor::set(true);
		return View::make("sms/send_person")->with("person", $person);

	}

	public function post_send($profile_slug, $person_slug, $child_slug="")
	{
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$content = Input::get('content');

		$from = Input::get('from') ? Input::get('from') : "OBJEKT"; // TODO: Fix event settings

		$message = array( 'to' => ltrim($person->phone, '0'), 'message' => $content, 'from' => $from );
		$result = Clockwork::message($message);

		if(!$result)
			return Redirect::to($person->url())->with('error', __("sms.status.not_sent"));

		$event = Config::get('application.event');

		$sms = new SMS;
		$sms->event_id = $event->id;
		$sms->success = $result['success'];
		$sms->person_id = $person->id;
		$sms->to = $result['sms']['to'];
		$sms->from = $result['sms']['from'];
		$sms->message = $result['sms']['message'];
		$sms->message_id = $result['id'];

		$user = Auth::user()->id;
		$user = User::find($user);
		$user->sms()->insert($sms);

		if($result['success'] == '1'){
			Loogie::doo("person", $person, "A SMS was sent to this person with the following content: ({$sms->message})");
			return Redirect::to($person->url())->with("success", __("sms.status.sent"));
		} else {
			Loogie::doo("person", $person, "A SMS was attempted to be transmitted to this person without results. ({$sms->message})");
			return Redirect::to($person->url())->with("error", __("sms.status.not_sent"));
		}
	}

}