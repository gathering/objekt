<?php

class Format {


	static function phone($num, $type="AUTO")
	{
		$num = preg_replace('/[^0-9]/', '', $num);
		 
		$len = strlen($num);

		if($type == "AUTO"){
			$start = substr($num, 0, 1);

			$homenumber = array(6, 8);
			$mobilenumber = array(9, 4);
			$type = "home";
			foreach($homenumber as $startnum){
				if($startnum == $start) {
					$type = "home";
				}
			}
			foreach($mobilenumber as $startnum){
				if($startnum == $start) {
					$type = "mobile";
				}
			}

			
		}

		switch($type){
			case "mobile":
			$num = preg_replace('/([0-9]{3})([0-9]{2})([0-9]{3})/', '$1 $2 $3', $num);
			break;
			case "home":
			$num = preg_replace('/([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/', '$1 $2 $3 $4', $num);
			break;
		}
		
		 
		return $num;
	}

	static function orgnr($num)
	{
		$num = preg_replace('/[^0-9]/', '', $num);
		 
		$len = strlen($num);
		$num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{3})/', '$1 $2 $3', $num);
		 
		return $num;
	}
}

?>