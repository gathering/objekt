<?php
/*
 * The Gathering 2014 special class.
 *
 */

include("wannabe.php");

class tg14 extends EventTemplate {
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

		var_dump($user); exit;

		// Special users
		switch($user->user->id){
			case 5497:
				$role = Role::find(6);
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

		if(!$role)
			return Redirect::to('/invite')->with('error', "Not apart of a crew that are eligible to have an Objekt-account.");

		$objUser = User::where("username", "=", $user->user->username);
		if($objUser->count() > 0){
			$objUser = $objUser->where("email", "=", $user->user->email);
			if($objUser = $objUser->first()){
				if($objUser->events()->where('events.id', '=', 2)->first())
					return Redirect::to('/invite')->with('error', "You are already registred with this event.");
				$objUser = Verify\Models\User::find($objUser->id); // There must be a better way.
			} else return Redirect::to('/invite')->with('error', "This username already exists, but with another email. We don't know it's you, so.. I'm sorry?");
		} else $objUser = new Verify\Models\User;

		$objUser->username = $user->user->username;
		$objUser->password = $password;
		$objUser->email = $user->user->email;
		$objuser->name = $user->user->realname;
		$objUser->verified = 1;
		$objUser->profile_img = isset($user->user->images->image[3]['url']) ? $user->user->images->image[3]['url'] : "";
		$objUser->meta = serialize($user);
		$objUser->save();

		$objUser->roles()->sync(array($role->id));

		return Redirect::to('/login')->with('success', 'Your account has been created.');

		// Crew_ID : SponsorSupport : 279
		// Crew_ID : Info Desk : 275
	}

	function searchUsers($id){
		
		$id = intval($id);

		$currentUser = Auth::user();
		
		if(isset($currentUser->meta()->apikey)){
			$apikey = $currentUser->meta()->apikey;
		} else $apiKey = "1d7fcf1944cbc683ea6bc3c044c9c2cd";

		Wannabe::setAPIKey($apikey);
		$user = Wannabe::user($id);
		if(isset($user->user))
			return array(array('name' => "Wannabe: ".$user->user->realname." (".$user->user->id.")", 'value' => "wb:".$user->user->id));
		else
			return array();
	}
}

?>