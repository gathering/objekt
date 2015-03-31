<?php


class GotSMS {

	protected static $API_URL = "https://sponsor.gbps.io/sms";

	static function send($phone, $message)
	{
		return self::call('/'.$phone.'/'.rawurlencode($message));
	}

	static function call($endpoint){

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, self::$API_URL.$endpoint);
		curl_setopt($c, CURLOPT_HEADER, false);

		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($c);

		return json_decode($response);
	}
}