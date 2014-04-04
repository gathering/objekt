<?php

class Role extends Eloquent {
	
	public function users(){
		return $this->has_many_and_belongs_to('user', 'role_user')->where("deleted", "=", "0");
	}

	public function event(){
		return $this->belongs_to('events', 'event_id');
	}

	static function non_current(){
		$event = Config::get('application.event');
		return self::where("event_id", "!=", $event->id)->where("name", "!=", "superAdmin");
	}

	public function allPermissions(){
		$permissions = Permission::all();
		$thisPermissions = $this->permissions()->get();

		foreach($permissions as $int => $permission){
			foreach($thisPermissions as $thisPermission)
				if($thisPermission->id == $permission->id) $permissions[$int]->access = true;
		}

		return $permissions;
	}

	public function permissions(){
		return $this->has_many_and_belongs_to('permission', 'permission_role');
	}
	
	static function findByName($name){
		$event = Config::get('application.event');
		return self::where("event_id", "=", $event->id)->where("name", "=", $name);
	}

}

?>