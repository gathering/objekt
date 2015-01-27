<?php

use Cart\Cart;
use Cart\CartItem as CartItem;

class Partner_Shop_Controller extends Base_Controller
{
 	
 	var $cartId = "partner";

    public function action_index()
    {

    	$cart = new Cart($this->id, new SessionStore);
    	var_dump($cart->all());

        return View::make('partner.shop')->with('cart', $cart->all());
    }

    public function action_cart($id)
    {

    	if(!$product = Product::find($id))
    		return Redirect::to('partner/shop')->with('error', 'Kunne ikke finne varen.');
    	
		$item = new CartItem;
		$item->name = 'Macbook Pro';
		$item->sku = 'MBP8GB';
		$item->price = 1200;
		$item->tax = 200;

		$cart = new Cart($this->id, new SessionStore);
		$cart->add($item);

		return Redirect::to('partner/shop')->with('success', 'Varen ble lagt til i handlevognen');
	}
}