<?php

Class Index_Task {

	function rebuild($arguments){
		return call_user_method_array("rebuild_{$arguments[0]}", $this, [[$arguments[1]]]);
	}

	function rebuild_profiles($arguments){

		if(count($arguments) < 1)
			die("Usage: artisan index:rebuild:profiles <event-id>\n\n");

		echo "Initiating re-building…";

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
		echo "[OK]\n";
		foreach($profiles as $profile){

			echo "Updating profile: {$profile->name}";

			$params['body']  = $profile->to_array();
			$params['body']['event_id'] = $event->id;

			unset($params['body']['updated_at']); // Not needed.
			unset($params['body']['created_at']); // Not needed.
			unset($params['body']['location']); // Not needed. (SPECIFIC)

			$params['index'] = 'profiles';
			$params['type']  = 'obj';
			$params['id']    = $profile->id;

			Elastisk::index($params);

			echo "[OK]\n";

			// Rebuild people
			$people = $profile->person()->get();
			foreach($people as $person){

				echo "Updating person: «{$person->firstname} {$person->surname}»";
				$params['body']  = $person->to_array();

				unset($params['body']['updated_at']); // Not needed.
				unset($params['body']['created_at']); // Not needed.

				$params['index'] = 'people';
				$params['type']  = 'obj';
				$params['id']    = $person->id;

				$entry = $person
					->validEntries()
					->where('type', '=', 'wristband')
					->first();

				if(isset($entry->ident) && !empty($entry->ident))
					$params['body']['entry']['ident'] = $entry['ident'];

				Elastisk::index($params);

				echo "[OK]\n";

			}
		}
	}

}