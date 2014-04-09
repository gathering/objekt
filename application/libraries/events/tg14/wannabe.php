<?php


class Wannabe {

	static private $app_key = "53299290-9920-4cba-9afb-433bc39fe93c";
	static private $api_url = "http://wannabe.gathering.org/tg14/api/";

	static private $api_key = "";

	static private $query_string = array();

	static function auth($username, $password){
		self::setQuery("username", $username);
		self::setQuery("password", md5($password));
		return self::run('auth');
	}

	static function setAPIKey($api_key){
		self::$api_key = $api_key;
	}

	static function user($id){
		self::setQuery("apikey", self::$api_key);
		return self::run('users/view/'.$id);
	}

	private static function setQuery($name, $value){
		self::$query_string[$name] = $value;
	}

	private static function getQuery(){
		self::$query_string['app'] = self::$app_key;
		return "?".http_build_query(self::$query_string);
	}

	private static function run($method=""){

		$url = self::$api_url.$method.self::getQuery();

		$ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    return json_decode(curl_exec($ch));
	}

}

?>