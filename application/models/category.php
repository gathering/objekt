<?php

class Category extends Eloquent {

	function products(){
		return $this->has_many('product');
	}

	protected function Discount_Type(){
		return Discount_Type::where('name', '=', 'Category');
	} 

	function discount(){
		$type = $this->Discount_Type()->first();
		return $this->has_many('discount', 'object_id')->where('type_id', '=', $type->id);
	}

	function get_sortsize(){
		// Set Sort Size to minimum of six.
		return $this->get_attribute('sortsize') > 0 ? $this->get_attribute('sortsize') : 6;
	}

}

?>