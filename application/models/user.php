<?php

class User extends Eloquent {
	
	public function role(){
		return $this->belongs_to('Role');
	}

}

?>