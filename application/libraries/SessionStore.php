<?php

use Cart\Storage\Store;

class SessionStore implements Store
{
    /**
     * {@inheritdoc}
     */
    public function get($cartId)
    {
        return Session::has('cart_'.$cartId) ? Session::get('cart_'.$cartId) : array();
    }

    /**
     * {@inheritdoc}
     */
    public function put($cartId, $data)
    {
        die(var_dump($cartId, $data));
        Session::put('cart_'.$cartId, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function flush($cartId)
    {
        Session::forget('cart_'.$cartId);
    }
}