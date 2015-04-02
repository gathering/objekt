<?php

class Discount extends Eloquent {

	function type(){
		return $this->belongs_to('discount_type');
	}

	function value_type(){
		return $this->belongs_to('discount_value_type');
	}

	function object(){
		$type = $this->type()->first();
		return $this->belongs_to($type->name);
	}
	
}