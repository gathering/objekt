<?php

class SMS_Controller extends Base_Controller {

	public function action_index()
	{
	}

	public function action_person($profile_slug, $person_slug, $child_slug="")
	{
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}
		tplConstructor::set(true);
		return View::make("sms/send_person")->with("person", $person);

	}

	public function action_post_person($profile_slug, $person_slug, $child_slug="")
	{
		$profile = profile::find($profile_slug);
		$person = $profile->person()->where("slug", "=", $person_slug)->first();
		if(!empty($child_slug)){
			$person = $profile->person_x()->where("slug", "=", $child_slug)->where("parent_id", "=", $person->id)->first();
		}

		$content = Input::get('content');

		$from = Input::get('from') ? Input::get('from') : "OBJEKT"; // TODO: Fix event settings

		$message = array( 'to' => '47'.$person->phone, 'message' => $content, 'from' => $from );
		$result = Clockwork::message($message);

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