<?php

class Loogie {
	
	static function doo($type, $object, $message){
		Log::write('info', $message);

		$log = new Logg;
		$log->message = $message;
		$log->type = $type;
		return $object->logs()->insert($log);
	}

}