<?php

class Update_People {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		$people = Person::where('phone', '!=', '')->get();
		foreach($people as $person){
			$save = false;
			if(preg_match('/\+([0-9])([0-9])/', $person->phone)){
				$save = true;
				echo "=== NEDENSTÅENDE VAR ".$person->phone." ===\n";
				$person->phone = preg_replace('/\+([0-9])([0-9])/', '00$1$2', $person->phone);
			}
			if(substr($person->phone, 0, 2) != '00'){
				$save = true;
				$person->phone = '0047'.$person->phone;
			}
			echo $person->phone." => (".$person->id.") ".$person->firstname." i ".$person->profile()->name."\n";
			$person->save();
		}
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}