<?php

class Fil3 extends Eloquent {
	public static $table = 'files';

	function event(){
		return $this->belongs_to('events', 'event_id');
	}

	function childs(){
		return $this->has_many('fil3', 'parent_id');
	}

	function meta(){
		return unserialize($this->get_attribute('meta'));
	}

	function get_meta(){
		return $this->meta();
	}

	function tags(){
		return explode(",", $this->tags);
	}

	function camera(){
		if(isset($this->meta['exif']['IFD0'])){
			return @$this->meta['exif']['IFD0']['Model'];
		}
	}

	function thumbnail(){
		return $this->childs()->where("type", "=", "mediabank-thumbnail")->first();
	}
}

?>