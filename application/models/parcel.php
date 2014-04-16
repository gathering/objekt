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
	function ids(){
		return explode(",", $this->ids);
	}
	function delete(){
		$params['index'] = 'logistics';
		$params['type']  = 'parcel';
		$params['id']    = $this->id;
		Elastisk::delete($params);
		return parent::delete();
	}
	static function duplicates(){
		return self::select(
						array(
							"parcels.id",
							"parcels.name",
							"parcels.description",
							"parcels.serialnumber",
							"dup.ids"
							)
						)
					->join(DB::Raw("(SELECT
										`parcels`.`name`,
										`parcels`.`description`,
										`parcels`.`serialnumber`,
										GROUP_CONCAT(DISTINCT `parcels`.`id` SEPARATOR ',') AS ids
									 FROM
									 	`parcels`
									 GROUP BY
									 	`parcels`.`name`,
									 	`parcels`.`description`,
									 	`parcels`.`serialnumber`
									 HAVING
									 	count(`parcels`.`id`) > 1
									) AS dup"), function($join){
						$join->on('parcels.name', '=', 'dup.name');
						$join->on('parcels.description', '=', 'dup.description');
						$join->on('parcels.serialnumber', '=', 'dup.serialnumber');
					});
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