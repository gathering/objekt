<?php

use Cart\Cart;
use Cart\CartItem as CartItem;

class Partner_Shop_Controller extends Base_Controller
{
 	
 	var $cartId = "partner";

    private function cart(){
        $cart = new Cart($this->cartId, new CartSessionStore);
        try {
            $cart->restore();
        } catch (Exception $e){ }

        return $cart;
    }

    public function action_index()
    {

    	$cart = $this->cart();

        return View::make('partner.shop')->with('cart', $this->cart());
    }

    public function action_checkout()
    {
        if(!count($this->cart()->all()))
            return Redirect::to('partner/shop');

        return View::make('partner.checkout')->with('cart', $this->cart());
    }

    public function action_order($id)
    {
        $pdf = PDF::view('order');

        Messages::synchronize();

        $order = Order::find($id);
        if(is_dir(path('app').'views/pdf/layouts/'.$order->event()->slug))
            $pdf->layout(path('app').'views/pdf/layouts/'.$order->event()->slug);
        
        $pdf->with("order", $order);

        $content = View::make("partner.email.order")->with("order", $order);

        $response = Mandr::messages()->send(array(
            'html' => $content->render(),
            'subject' => 'Ordrebekreftelse fra Objekt - #'.$order->id(),
            'from_email' => Lang::line('user.noreply')->get(),
            'from_name' => Lang::line('user.noreply_name')->get(),
            'to' => array(
                array('email' => partnerAuth::user()->email)
                ),
            'attachments' => array(
                array(
                    'type' => 'application/pdf',
                    'name' => 'ordrebekreftelse-'.$order->id().'.pdf',
                    'content' => base64_encode($pdf->string())
                    )
            )
        ), false);

        var_dump($response); exit;
    }

    public function action_finish()
    {
        $CartStore = new CartSessionStore;
        $event = Config::get('application.event');

        if(count($CartStore->get($this->cartId)) < 1)
            return Redirect::to('partner/shop')->with('error', 'Handlevognen er tom.');

        $order = new Order;
        $order->event_id = $event->id;
        $order->person_id = partnerAuth::user()->id;
        $order->profile_id = partnerAuth::user()->profile()->id;

        // Save the cart
        $order->order_details = $CartStore->get($this->cartId);

        $order->save();

        $CartStore->flush($this->cartId);

        return Redirect::to('partner/shop');

       # $memo = PDF::view('memo')->with("order", $order);
    }

    public function action_edit_cart_item($id)
    {
        $cart = $this->cart();

        if(Input::get('quantity') == 0)
            $cart->remove($id);
        else
            $cart->update($id, 'quantity', (int) Input::get('quantity'));

        $cart->save();

        return Redirect::to('partner/shop/checkout');
    }

    public function action_delete_cart_item($id)
    {
        $cart = $this->cart();
        $cart->remove($id);
        $cart->save();

        return Redirect::to('partner/shop/checkout');
    }

    public function action_cart($id)
    {

    	if(!$product = Product::find($id))
    		return Redirect::to('partner/shop')->with('error', 'Kunne ikke finne varen.');
    	   
        $item = new CartItem;
        $item->name = $product->name;
        $item->price = $product->price();
        $item->id = $product->id;
        $item->tax = ($product->price/100)*$product->tax;
        $item->sku = str_pad($product->event_id, 3, 0)
                    .date("y").
                    str_pad(($product->id*10), 5, 0, STR_PAD_LEFT);

		$cart = $this->cart();
		$cart->add($item);
        $cart->save();

		return Redirect::to('partner/shop')->with('success', 'Varen ble lagt til i handlevognen');
	}
}