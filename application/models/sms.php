<?php

class SMS extends Eloquent {

	function user(){
		return $this->belongs_to('user');
	}

}

?>