<?php

class EventTemplate
{
    public function invite() { return false; }
    public function hasInvite() {
    	return method_exists($this, 'invite');
    }
    public function view($view, $speciality=true){
    	if($speciality && !defined('EVENT_SPECIALITY')) define('EVENT_SPECIALITY', true);
    	return View::make('path: '.path('app').'libraries/events/' . get_class($this) . '/views/'.$view);
    }
}

?>