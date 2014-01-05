<?php

class User extends Verify\Models\User {
	
	public function role(){
		return $this->belongs_to('Role');
	}

	static function current(){
		$currentRoles = Role::current();

		$users = array();
		foreach($currentRoles as $role){
			$users = array_merge($users, $role->users()->get());
		}
		return $users;
	}

	static function non_current(){
		$currentRoles = Role::non_current();

		$users = array();
		foreach($currentRoles as $role){
			$users = array_merge($users, $role->users()->get());
		}
		return $users;
	}

	public function user_events(){
		return $this->has_many_and_belongs_to('events', 'event_users', 'user_id', 'event_id');
	}

	public function events(){
		if($this::is('superSponsorAdmin'))
			return Events::where("status", "=", "activated")->order_by('date', 'desc');
		return $this->user_events();
	}

}

?>