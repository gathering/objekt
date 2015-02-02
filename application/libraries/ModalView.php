<?php

class ModalView extends View {

	static function make($name, $data = array()){
		$view = new static($name, $data);
		define('MODALVIEW', true);
		return $view;
	}

}