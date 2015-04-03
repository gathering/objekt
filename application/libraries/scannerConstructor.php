<?php

class scannerConstructor {
	static $constructor = true;
	static function has(){
		return self::$constructor;
	}
	static function set($value){
		self::$constructor = $value;
		return true;
	}
}