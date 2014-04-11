<?php

class Parcellog extends Eloquent {
	
	public static $table = 'parcel_logs';

	function receiver(){
		if($user = Users::findWithTag($this->receiver_id)){
			return method_exists($user, "display_name") ? $user->display_name() : $user->name;
		} else
			return false;
	}

	function user(){
		return $this->belongs_to('user');
	}

}

?>