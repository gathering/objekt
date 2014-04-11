<?php

class Parcel extends Eloquent {
	function logs(){
		return $this->has_many("parcellog");
	}
	function current_status(){
		return new parcelCurrentStatus($this->logs()->where("status", "!=", "")->order_by('id', 'desc')->first());
	}
	function user(){
		return $this->belongs_to('user');
	}
}

class parcelCurrentStatus {
	var $object;
	function __construct($object){
		$this->object = $object;
		if($object){
			$array = $object->to_array();
			foreach($array as $name => $value) $this->{$name} = $value;
		} else {
			$this->status = "created";
		}
	}

	function __call($method, $args){
		if($this->object)
			return call_user_func_array(array($this->object, $method), $args);
		return false;
	}

	function can_handout(){
		switch($this->status){
			case "receive":
			case "in_stock":
			case "created":
			return true;
		}
		return false;
	}

	function can_place_in_stock(){
		switch($this->status){
			case "receive":
			return true;
		}
		return false;
	}

	function can_receive(){
		switch($this->status){
			case "handout":
			case "in_transit":
			return true;
		}
	}

}

?>