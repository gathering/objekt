<?php

class Mandr {
	public static function __callStatic($method, $args)
	{	
		
		// load config
		$config = Config::get('mandrill'); // from application, not bundle

		// build object
		$Mandrill = new Mandrill($config['api_key']);
		
		// return
		if(count($args) > 0)
			return call_user_func_array(array($Mandrill, $method), $args);
		return $Mandrill->{$method};
	}
}