<?php

class User extends Verify\Models\User {
	
	public function role(){
		return $this->belongs_to('Role');
	}

	public function sms(){
		return $this->has_many('sms');
	}

	public function notifications(){
		return $this->has_many('notification');
	}

	public function following(){
		return $this->has_many('following');
	}

	public function isFollowing($what, $id){
		return $this->following()->where("type", "=", $what)->where("belongs_to", "=", $id)->count() > 0 ? true : false;
	}

	static function active($event=""){
		return self::left_join('event_users', 'event_users.user_id', '=', 'users.id')
					->where(function($query) use(&$event) {
						$query->where(function($query) use(&$event) {
							$event = !empty($event) ? $event : Config::get('application.event');
							$query->where("event_users.event_id", "=", $event->id);
							$query->where("users.id", "=", DB::Raw('`event_users`.`user_id`'));
						});
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
		return $this->has_many_and_belongs_to('events', 'role_user', 'user_id', 'event_id');
	}

	public function events(){
		if($this::is('superAdmin'))
			return Events::where("status", "=", "activated")->order_by('date', 'desc');
		return $this->user_events();
	}

}

?>