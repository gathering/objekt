<?php
/*
 * The Gathering 2014 special class.
 *
 */

include("wannabe.php");

class tg14 extends EventTemplate {
	var $tag = "wb";
	function invite(){
		if(!Input::get())
			return $this->view('invite.blade.php');

		$username = Input::get('username');
		$password = Input::get('password');

		$user = Wannabe::auth($username, $password);
		if(isset($user->error))
			return Redirect::to('/invite')->with('error', __('common.login_errors.usernotfound'));

		if(!is_array($user->user->crews->crew))
			return Redirect::to('/invite')->with('error', "You are not apart of any crew, so we cannot add your account.");

		// Special users
		switch($user->user->id){
			case 5497:
				$role = Role::find(6);
			break;
			case 2260:
				$role = Role::find(8);
			break;
			default: case "":
				foreach($user->user->crews->crew as $crew){
					switch($crew->id){
						case 279:
							$role = Role::findByName($crew->name)->first();
						break;
						case 275:
							$role = Role::findByName($crew->name)->first();
						break;
						case 278:
							$role = Role::findByName($crew->name)->first();
						break;
					}
				}
			break;
		}
		if(empty($user->user))
			return Redirect::to('/invite')->with('error', "An error occurred during retrieving data from Wannabe.");

		$user = $user->user;

		if(!$role)
			return Redirect::to('/invite')->with('error', "Not apart of a crew that are eligible to have an Objekt-account.");

		$objUser = User::where("username", "=", $user->username);
		if($objUser->count() > 0){
			$objUser = $objUser->where("email", "=", $user->email);
			if($objUser = $objUser->first()){
				if($objUser->events()->where('events.id', '=', 2)->first())
					return Redirect::to('/invite')->with('error', "You are already registred with this event.");
				$objUser = Verify\Models\User::find($objUser->id); // There must be a better way.
			} else return Redirect::to('/invite')->with('error', "This username already exists, but with another email. We don't know it's you, so.. I'm sorry?");
		} else $objUser = new Verify\Models\User;

		$objUser->username = @$user->username;
		$objUser->password = $password;
		$objUser->email = @$user->email;
		$objuser->name = @$user->realname;
		$objUser->verified = 1;
		$objUser->profile_img = isset($user->images->image[3]['url']) ? $user->images->image[3]['url'] : "";
		$objUser->meta = serialize($user);
		$objUser->save();

		$objUser->roles()->sync(array($role->id));

		return Redirect::to('/login')->with('success', 'Your account has been created.');

		// Crew_ID : SponsorSupport : 279
		// Crew_ID : Info Desk : 275
	}

	function synchronizeUsers(){
		// Create Index
		#$indexParams['index']  = 'wannabe_tg14';
		#Elastisk::indices()->create($indexParams);

		$currentUser = Auth::user();
		if(isset($currentUser->meta()->apikey)){
			$apikey = $currentUser->meta()->apikey;
		} else $apikey = "1d7fcf1944cbc683ea6bc3c044c9c2cd";

		Wannabe::setAPIKey($apikey);
		
		$i=0;
		foreach(Wannabe::users()->users->user as $user){
			$params = array();
			$params['body']  = array(
				'name' => $user->realname,
				'id' => $user->id
				);

			$params['index'] = 'wannabe_tg14';
			$params['type']  = 'user';
			$params['id']    = $user->id;

			Elastisk::index($params);
			$i++;
		}

		return $i." users added.";

	}

	function searchUsers($term){

		$params['index'] = 'wannabe_tg14';
		$params['type']  = 'user';
		$params['body']['query']['query_string']['query'] = $term;
		if($term == "*") $params['body']['size'] = 10000;

		$results = array();
		$elastisk = Elastisk::search($params);

		foreach($elastisk['hits']['hits'] as $result){
			$person = array();
			$person['name'] = "Wannabe: ".$result['_source']['name'];
			$person['value'] = "wb:".$result['_id'];
			array_push($results, $person);
		}

		return $results;

	}

	function getUser($id){
		
		$id = intval($id);

		$currentUser = Auth::user();
		
		if(isset($currentUser->meta()->apikey)){
			$apikey = $currentUser->meta()->apikey;
		} else $apikey = "1d7fcf1944cbc683ea6bc3c044c9c2cd";

		Wannabe::setAPIKey($apikey);
		$user = Wannabe::user($id);

		if(isset($user->user)){
			$user->user->name = $user->user->realname; // Objekt Fix.
			return $user->user;
		} else
			return array();
	}
}

?>