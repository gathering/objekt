<?php

class Role extends Eloquent {
	
	public function users(){
		return $this->has_many('User');
	}

}

?>