<?php

class Entry extends Eloquent {
	function person(){
		return $this->belongs_to("person")->first();
	}
}

?>