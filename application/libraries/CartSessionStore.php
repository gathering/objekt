<?php

use Cart\Storage\Store;

class CartSessionStore implements Store
{
    /**
     * {@inheritdoc}
     */
    public function get($cartId)
    {
        return Session::has('cart-' . $cartId) ? Session::get('cart-' . $cartId) : array();
    }

    /**
     * {@inheritdoc}
     */
    public function put($cartId, $data)
    {
        Session::put('cart-' . $cartId, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function flush($cartId)
    {
        Session::forget('cart-' . $cartId);
    }
}
