<?php
/**
 * @Author: Simen A.W. Olsen
 * @Date:   2015-03-28 15:56:13
 * @Last Modified by:   Simen A.W. Olsen
 * @Last Modified time: 2015-03-29 14:10:57
 */

class API_Profile_Contact_Persons_Controller extends Controller {

	public $restful = true;

	function get_index(){
		$contact_persons = [];

		foreach(User::where('phone', '>', 0)->get() as $user){
			if($user->profiles()->get()){
				array_push($contact_persons, [
					'name' => $user->name,
					'phone' => $user->phone
					]);
			}
		}

		return Response::json($contact_persons);
	}

}