<?php
use League\Csv\Writer;

class Export_Task {

	function csv($attributes){
		if(count($attributes) < 1)
			die("Usage: artisan export:csv <event-id>\n");

		$event = $attributes[0];

		$event = Events::find($event);
		if(!$event)
			die("Could not find event.\n");

		$writer = Writer::createFromFileObject(new SplTempFileObject());
		if(isset($attributes[1]))
			$writer->setDelimiter(';');

		// Insert headers
		$writer->insertOne(['firstname', 'surname', 'phone', 'email', 'partner', 'is_contact_person', 'status', 'accreditation', 'card_id']);

		foreach($event->people()->get() as $person){

			$personData = [
					$person->firstname,
					$person->surname,
					$person->phone,
					$person->email,
					$person->profile()->name,
					$person->contact_person,
					$person->status
				];

			// If person has valid accreditation
			$entry = $person->entries()
			->where('status', '=', 'valid')
			->where(function($query){

				$query->where('type', '=', 'wristband');

			})->or_where(function($query){

				$query->where('type', '=', 'badge');
				$query->where('delivery_date', '>', DB::Raw('NOW()'));

			})->first();

			if($entry){
			
				array_push($personData, $entry->type);
				array_push($personData, $entry->ident);

			} else {
				array_push($personData, '');
				array_push($personData, '');
			}

			$personData = array_map( "utf8_decode", $personData );

			$writer->insertOne($personData);
		}

		$writer->output();
	}

}