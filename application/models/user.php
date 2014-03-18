<?php

class User extends Verify\Models\User {
	
	public function role(){
		return $this->belongs_to('Role');
	}

	public function sms(){
		return $this->has_many('sms');
	}

	static function active($event=""){
		return self::left_join('event_users', 'event_users.user_id', '=', 'users.id')
					->where(function($query) use(&$event) {
						$query->where(function($query) use(&$event) {
							$event = !empty($event) ? $event : Config::get('application.event');
							$query->where("event_users.event_id", "=", $event->id);
							$query->where("users.id", "=", DB::Raw('`event_users`.`user_id`'));
						});
						$query->or_where("users.role_id", "=", '1');
					})
					->where("users.disabled", "=", "0")
					->where("users.deleted", "=", "0");
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