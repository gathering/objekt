<?php

class Product extends Eloquent {

	function stock(){
		return $this->stock;
	}

	function price(){
		return $this->price;
	}
}

?>