<?php

class Entry extends Eloquent {
	function person(){
		return $this->belongs_to("person")->first();
	}
	function people(){
		return $this->belongs_to("person");
	}
	function save(){

		$person = $this->person();

		$params['body']  = $person->to_array();

		unset($params['body']['updated_at']); // Not needed.
		unset($params['body']['created_at']); // Not needed.

		$params['index'] = 'people';
		$params['type']  = 'obj';
		$params['id']    = $person->id;

		$entries = $person
			->validEntries()
			->where('type', '=', 'wristband')
			->first();

		if(empty($entries->ident))
			$params['body']['entry']['ident'] = $entries['ident'];

		Elastisk::index($params);

		parent::save();
	}
}

?>