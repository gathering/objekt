<?php
use League\Csv\Writer;

class Export_Task {

	function csv($attributes){
		if(count($attributes) > 1)
			die("Usage: artisan export:csv <event-id>\n");

		$event = $attributes[0];

		$event = Events::find($event);
		if(!$event)
			die("Could not find event.\n");

		$writer = Writer::createFromFileObject(new SplTempFileObject());

		// Insert headers
		$writer->insertOne(['firstname', 'surname', 'phone', 'email', 'partner', 'is_contact_person', 'accreditation', 'card_id']);

		foreach($event->people()->get() as $person){

			$personData = [
					$person->firstname,
					$person->surname,
					$person->phone,
					$person->email,
					$person->profile()->name,
					$person->contact_person
				];

			// If person has valid accreditation
			$entry = $person->entries()->where(function($query){

				$query->where('status', '=', 'valid');
				$query->where('type', '=', 'badge');

			})->or_where(function($query){

				$query->where('status', '=', 'valid');
				$query->where('type', '=', 'wristband');
				$query->where('delivery_date', '>', DB::Raw('NOW()'));

			})->order_by('created_at', 'desc')->first();

			if($entry){
			
				array_push($personData, $entry->type);
				array_push($personData, $entry->ident);

			}

			$writer->insertOne($personData);
		}

		$writer->output();
	}

}