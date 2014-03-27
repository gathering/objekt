<?php


class Push {

	public static function __callStatic($method, $args)
	{	
		
		// load config
		$config = Config::get('pusher'); // from application, not bundle

		// build object
		$pusher = new Pusher($config['app_key'], $config['app_secret'], $config['app_id']);
		
		// return
		return call_user_func_array(array($pusher, $method), $args);
	}

}