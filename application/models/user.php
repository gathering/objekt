<?php

class User extends Verify\Models\User {

	public function sms(){
		return $this->has_many('sms');
	}

	public function notifications(){
		return $this->has_many('notification');
	}

	public function image($size=36){
		return !empty($this->profile_img) ? $this->profile_img : "http://www.gravatar.com/avatar/".md5( strtolower( trim( $this->email ) ) )."?d=http%3A%2F%2Fsupersponsor.no%2Fimages%2Fdefault_profile.png&s=".$size;
	}

	public function meta(){
		return unserialize($this->meta);
	}

	public function display_name(){
		return !empty($this->name) ? $this->name : $this->username;
	}

	public function following(){
		return $this->has_many('following');
	}

	public function isFollowing($what, $id){
		return $this->following()->where("type", "=", $what)->where("belongs_to", "=", $id)->count() > 0 ? true : false;
	}

	public function allRoles(){
		$event = Config::get('application.event');
		$roles = $event->roles()->get();
		$thisRoles = $this->roles()->get();

		foreach($roles as $int => $role){
			foreach($thisRoles as $thisRole)
				if($thisRole->id == $role->id) $roles[$int]->access = true;
		}

		return $roles;
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

	public function events(){
		if($this::is('superAdmin'))
			return Events::where("status", "=", "activated")->order_by('date', 'desc');

		return Events::left_join('roles', 'roles.event_id', '=', 'events.id')
						->left_join('role_user', 'role_user.role_id', '=', 'roles.id')
						->where('events.status', '=', 'activated')
						->where('role_user.user_id', '=', $this->id)
						->select('events.*');
	}

}

?>