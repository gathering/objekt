<?php


class Elastisk {

	public static function __callStatic($method, $args)
	{	
		
		// load config
		$config = Config::get('elastisk'); // from application, not bundle

		// build object
		$Elasticsearch = new Elasticsearch\Client($config);
		
		// return
		return call_user_func_array(array($Elasticsearch, $method), $args);
	}

}