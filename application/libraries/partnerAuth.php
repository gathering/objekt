<?php

class partnerAuth {

	static $sessionVariable = "objekt-partner";

	static function model(){
		$event = Config::get('application.event');
		return $event->people();
	}

	static function guest(){
		if(!Session::has(self::$sessionVariable)) return true;
		if(!self::check()) return true;

		return false;
	}

	static function user(){
		$token = Session::get(self::$sessionVariable);
		return self::retrieve($token);
	}

	static function check(){
		if(!Session::has(self::$sessionVariable)) return false;

		$token = Session::get(self::$sessionVariable);
		return self::retrieve($token) ? true : false;
	}

	static function retrieve($token){
		return self::model()->where("people.id", "=", $token)->first();
	}

	protected static function store($token)
	{
		Session::put(self::$sessionVariable, $token);
	}

	static function login($id){
		self::store($id);
	}

	static function logout(){
		return Session::forget(self::$sessionVariable);
	}

	static function attempt($arguments){

		$user = self::model()->where(function($query) use($arguments)
		{		
			$query->where('phone', '=', $arguments['username']);
			$query->or_where('phone', '=', '0047'.$arguments['username']);
			
		})->first();

		// If the credentials match what is in the database we will just
		// log the user into the application and remember them if asked.
		$password = $arguments['password'];

		if ( ! is_null($user) and Hash::check($password, $user->password))
		{
			return partnerAuth::login($user->id);
		}

		return false;
	}

}