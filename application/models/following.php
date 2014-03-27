<?php

class Following extends Eloquent {
	function user(){
		return $this->belongs_to('user');
	}
}