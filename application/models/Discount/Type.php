<?php

class Discount_Type extends Eloquent {
	function discounts(){
		return $this->has_many('discount');
	}
}