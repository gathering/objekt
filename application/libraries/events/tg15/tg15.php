<?php
/*
 * The Gathering 2014 special class.
 *
 */

include("wannabe.php");

class tg15 extends EventTemplate {
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
			// Lesebrukarar
			case 3070: // Andreas Martin Aanerud
			case 3368: // Andreas Lindrupsen
			case 5947: // Trine Jakobsen Rydland
				$role = Role::find(16);
			break;
			default: case "":
				foreach($user->user->crews->crew as $crew){
					switch($crew->id){
						case 301: // Core:Sponsor
							$role = Role::find(15);
						break;
						case 298: // Core:Floor
							$role = Role::find(16);
						break;
						case 328: // Economy
							$role = Role::find(16);
						break;
						case 304: // Info:Desk
							$role = Role::find(17);
						break;
						case 332: // Core:Obj
							$role = Role::find(1);
						break;
						case 303: // Info:Content
							$role = Role::find(23);
						break;
						case 325:
							$role = Role::find(17);
						break;
					}
				}
			break;
		}

		if(!isset($role))	
			return Redirect::to('/invite')->with('error', "Not apart of a crew that are eligible to have an Objekt-account.");

		$objUser = User::where("username", "=", $user->user->username);
		if($objUser->count() > 0){
			$objUser = $objUser->where("email", "=", $user->user->email);
			if($objUser = $objUser->first()){
				if($objUser->events()->where('events.id', '=', 2)->first())
					return Redirect::to('/invite')->with('error', "You are already registred with this event.");
			} else return Redirect::to('/invite')->with('error', "This username already exists, but with another email. We don't know it's you, so.. I'm sorry?");
		} else $objUser = new Verify\Models\User;
		
		$objUser->username = @$user->user->username;
		$objUser->password = $password;
		$objUser->email = @$user->user->email;
		$objUser->name = empty($user->user->realname) ? $user->user->username : $user->user->realname;
		$objUser->verified = 1;
		$objUser->profile_img = isset($user->user->images->image[3]->url) ? $user->user->images->image[3]->url : "";
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

			$params['index'] = 'wannabe_tg15';
			$params['type']  = 'user';
			$params['id']    = $user->id;

			Elastisk::index($params);
			$i++;
		}

		return $i." users added.";

	}

	function searchUsers($term){

		$params['index'] = 'wannabe_tg15';
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