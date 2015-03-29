<?php
/**
 * @Author: cobraz
 * @Date:   2015-03-28 13:23:13
 * @Last Modified by:   Simen A.W. Olsen
 * @Last Modified time: 2015-03-29 13:52:59
 */

class API_Profile_Person_Controller extends Controller {

	public $restful = true;

	function get_index()
	{
		if(Input::get('search'))
			return $this->get_search(Input::get('search'));

		return Response::json(array(
				'status' => 'not-implemented'
			), 404);
	}

	function get_search($term)
	{
		// Search only works with phone-numbers as of now.
		// This function will only return one person, always.

		$event = Config::get('application.event');

		$person = Person::where(function($query) use ($term){
			$query->where('phone', '=', $term);
			$query->or_where('phone', '=', str_replace('0047', '', $term));
		})->where('event_id', '=', $event->id)->first();
		
		if(!$person)
			return Response::json(array('status' => 'not-found'), 404);		

		$profile = $person->profile();
		$contact_person = $profile->responsible();
		
		if(!$contact_person)
			return Response::json(array('status' => 'missing-contact', 'message' => 'contact person not found'), 404);	

		if(empty($contact_person->phone))
			return Response::json(array('status' => 'missing-contact-phone', 'message' => 'contact person has not saved phonenumber'), 404);	


		return Response::json([
				'phone' => $person->phone,
				'status' => $person->status,
				'profile' => [
					'name' => $profile->name,
					'email' => $profile->email,
					'website' => $profile->website
				],
				'contact_person' => [
					'name' => $contact_person->name,
					'phone' => $contact_person->phone,
					'available' => true // Allways true for this guy.
				]
			]);

	}

}