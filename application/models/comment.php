<?php

class Comment extends Eloquent {
	function user(){
		return $this->belongs_to('user')->first();
	}
}

?>