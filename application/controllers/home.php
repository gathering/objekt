<?php

class Home_Controller extends Base_Controller {

	public function action_index()
	{
		$feed = new SimplePie;
		$feed->set_feed_url('http://obj.no/feed/');
		$feed->set_cache_location(path('storage').'/cache/rss/');
		$feed->init();
		return View::make('home.index')->with("feed", $feed);
	}

}