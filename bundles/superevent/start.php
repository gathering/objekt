<?php

$uri = URI::current();
foreach (Events::where("status", "=", "activated")->get() as $event)
{
	$slug = $event->slug;
	if (preg_match("#^{$slug}(?:$|/)#i", $uri))
	{
		Config::set('application.event', $event);
		$uri = trim(substr($uri, strlen($event->slug)), '/'); break;
	}
}

if ($uri == '') $uri = '/';

URI::$uri = $uri;

$event = Config::get('application.event');
if(!empty($event) && is_object($event->id)){
	Event::first('404');
}





?>