<?php

class Fil3 extends Eloquent {
	public static $table = 'files';

	function event(){
		return $this->belongs_to('events', 'event_id');
	}
}

?>