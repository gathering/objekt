<?php

class Product extends Eloquent {

	function stock(){
		return $this->stock;
	}

	function category(){
		return $this->belongs_to('category');
	}

	protected function Discount_Type(){
		return Discount_Type::where('name', '=', 'Product');
	}

	function discount(){
		$type = $this->Discount_Type()->first();
		return $this->has_many('discount', 'object_id')->where('type_id', '=', $type->id);
	}

	protected function process_price($price, $discount){
		$value_type = $discount->value_type()->first();
		switch($value_type->name){
			case "percent":
				$price = $price / 100 * (100-$discount->value);
				break;
			case "price":
				$price = $price - $discount->value;
				break;
			default: case "":
				break;
		}
		return $price;
	}

	function price(){

		$price = $this->get_attribute('price');

		if($this->get_attribute('no_discount') == true)
			return $price;

		// First check if there are any global discounts on the profile.
		if(!PartnerAuth::guest()){
			$partner = PartnerAuth::user()->profile();
			foreach($partner->discount()->get() as $discount)
				$price = $this->process_price($price, $discount);
		}

		// Also check if there are any discounts on the product.
		foreach($this->discount()->get() as $discount)
			$price = $this->process_price($price, $discount);

		// Then the category
		foreach($this->category()->first()->discount()->get() as $discount)
			$price = $this->process_price($price, $discount);

		return $price;
	}
}

?>