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
			return Redirect::to('/invite')->with('error', __('common.login_errors.userdisabled'));

		#var_dump($user); exit;

		foreach($user->user->crews->crew as $crew){
			switch($crew->id){
				case 279:
					$role = Role::findByName($crew->name)->first();
				break;
				case 275:
					$role = Role::findByName($crew->name)->first();
				break;
			}
		}

		if(!$role)
			return Redirect::to('/invite')->with('error', "Not apart of a crew that are eligible to have an Objekt-account.");

		$objUser = User::where("username", "=", $user->user->username);
		if($objUser->count() > 0){
			$objUser = $objUser->where("email", "=", $user->user->email);
			if($objUser->count() > 0){
				// Skip creating user, we already have it.
			} else return Redirect::to('/invite')->with('error', "This username already exists, but with another email. We don't know it's you, so.. I'm sorry?");
		}

		// Crew_ID : SponsorSupport : 279
		// Crew_ID : Info Desk : 275
		// Save users meta
		# $serialize = serialize($user);

		return true;
	}
}

?>