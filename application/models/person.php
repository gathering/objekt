<?php

class Person extends Eloquent {
	function user(){
		return $this->firstname." ".$this->surname;
	}
	function sponsor(){
		return $this->belongs_to('sponsor')->first();
	}
	function child(){
		return $this->has_many("person", "parent_id");
	}
	function parent(){
		if(!$this->is_child()) return false;
		return self::find($this->parent_id);
	}
	public function is_child(){
		return $this->parent_id > 0 ? true : false;
	}
	public function is_parent(){
		return $this->parent_id > 0 ? false : true;
	}
	function num_child(){
		return $this->child()->count();
	}
	static public function find($any){
		if(is_numeric($any)){
			return parent::where("id", "=", $any)->first();
		}

		return parent::where("slug", "=", $any)->first();
	}
}

?>