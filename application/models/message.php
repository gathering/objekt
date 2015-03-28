<?php

class Message extends Eloquent {
	static function synchronize(){

		$events = Events::all();
		$routes = Mandr::inbound()->routes('io.obj.no');

		foreach($events as $event){

			if(in_array($event->slug.'-*', $routes))
				continue;

			$domain = 'io.obj.no';
			$pattern = $event->slug.'-*';
			$url = "http://app.obj.no/".$event->slug.URL::to('ticket/inbound');

			Mandr::inbound()->addRoute($domain, $pattern, $url);

			if(in_array($event->slug, $routes))
				continue;

			$domain = 'io.obj.no';
		    $pattern = $event->slug;
		    $url = "http://app.obj.no/".$event->slug.URL::to('ticket/inbound');

		    Mandr::inbound()->addRoute($domain, $pattern, $url);

		}

		exit;
	}

	public function replys(){
		return Message::where('thread', '=', $this->thread)
					->where('id', '!=', $this->id)
					->where('profile_id', '=', $this->profile_id);
	}

	public function person(){
		return $this->belongs_to('person')->first();
	}

	public function get_event_from_mail($email=NULL){

		$email = explode("@", ($email == NULL ? $this->get_attribute('to_email') : $email));
		$username = explode("-", $email[0]);

		
		$event = Events::where('slug', '=', $username[0])->first();
		return $event;

	}

	public function get_thread_from_mail($email=NULL){

		$email = explode("@", ($email == NULL ? $this->get_attribute('to_email') : $email));
		$username = explode("-", $email[0]);

		if(count($username) > 1)
			return str_replace($username[0]."-", "", $email[0]);
		else 
			return strtolower(Str::random(32));

	}

	function set_from_email($email){

		$this->set_attribute('from_email', $email);

		// Find user that have sent the email.
		$person = Person::where('email', '=', $email)
					->where('event_id', '=', $this->event_id);

		if($person->count() > 0){
			$person = $person->first();
			$this->set_attribute('person_id', $person->id);
			$this->set_attribute('profile_id', $person->profile_id);
		}

	}

	function set_to_email($email){

		$this->set_attribute('to_email', $email);

		$event = $this->get_event_from_mail($email);

		// Set event
		if($event)
			$this->set_attribute('event_id', $event->id);

		if(empty($this->get_attribute('thread')))
			$this->set_attribute('thread', $this->get_thread_from_mail());
	}
}