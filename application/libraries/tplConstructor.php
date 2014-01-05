<?php

class tplConstructor {
	static $constructor = false;
	static function has(){
		return self::$constructor;
	}
	static function set($value){
		self::$constructor = $value;
		return true;
	}
}