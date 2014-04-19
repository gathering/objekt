<?php


class UpdateDB_Task {
	var $event;

	function run(){
		/*$events = Events::all();
		foreach($events as $event){
			$this->event = $event;

			// People
			$this->updatePeople(array());

		}*/
	}

	function rebuildElastic($arguments){
		if(isset($arguments[0])){

			if(is_numeric($arguments[0]))
				$event = Events::find($arguments[0]);
			else
				$event = Events::where("slug", "=", $arguments[0])->first();

			if(!$event) die("EVENT NOT FOUND!");
		}

		Config::set('application.event', $event);

		// Rebuild profiles
		$profiles = $event->profiles()->get();
		foreach($profiles as $profile){
			$params['body']  = $profile->to_array();
			$params['body']['event_id'] = $event->id;
			unset($params['body']['updated_at']); // Not needed.
			unset($params['body']['created_at']); // Not needed.
			unset($params['body']['location']); // Not needed. (SPECIFIC)

			$params['index'] = 'profiles';
			$params['type']  = 'obj';
			$params['id']    = $profile->id;
			Elastisk::index($params);

			// Rebuild people
			$people = $profile->person()->get();
			foreach($people as $person){
				$params['body']  = $person->to_array();
				unset($params['body']['updated_at']); // Not needed.
				unset($params['body']['created_at']); // Not needed.

				$params['index'] = 'people';
				$params['type']  = 'obj';
				$params['id']    = $person->id;
				Elastisk::index($params);
			}
		}


	}

	function updateEntries($arguments){
		if(isset($arguments[0])){

			if(is_numeric($arguments[0]))
				$event = Events::find($arguments[0]);
			else
				$event = Events::where("slug", "=", $arguments[0])->first();

			if(!$event) die("EVENT NOT FOUND!");
		}

		Config::set('application.event', $event);

		$endtime = strtotime($event->to_date);

		if($endtime < time()){
			$entries = $event->entries()->select('entries.*');
		} else {
			$entries = $event
					->entries()
					->where(function($query){
						$query->where("entries.delivery_date", "!=", "0000-00-00 00:00:00");
						$query->where("entries.delivery_date", "<", date("Y-m-d H:i:s"));
						$query->where("entries.status", "!=", "denied");
						$query->where("people.status", "=", "arrived");
					})->select('entries.*');
		}

		foreach($entries->get() as $entry){
			$entry->status = "denied";
			$entry->save();

			$person = $entry->person();
			$person->status = "departed";
			$person->save();

			Loogie::doo("person", $person, "User «{$person->slug}» at «{$profile->name}» has departed from the event. {$person->firstname} is not expected to be back again.");
			$person->sendNotification(__('accreditation.notification.departed'));

			echo $person->firstname." ".$person->surname." has been marked departed\n";
		}
	}

}