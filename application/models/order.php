<?php
use Cart\Cart;
use Cart\Storage\Store;
use Cart\CartItem;

class Order extends Eloquent {
	function id(){
		return str_pad($this->id, 5, 0, STR_PAD_LEFT);
	}
	function event(){
		return $this->belongs_to('events')->first();
	}
	function profile(){
		return $this->belongs_to('profile')->first();
	}
	function person(){
		return $this->belongs_to('person')->first();
	}
	function cart(){

		$cart = new Cart($this, new OrderCartStorage);
        $cart->restore();
        return $cart;

	}
}

class OrderCartStorage implements Store{
	public function get($order)
    {
        return $order->order_details;
    }
    public function put($order, $data)
    {
    	$order->order_details = $data;
    	$order->save();
    }
    public function flush($cartId) {}
}

?>