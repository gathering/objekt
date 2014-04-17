<?php

class Date {

    public static function nice($datetime=0) {
        if($datetime == "0000-00-00 00:00:00") return "Ukjent dato.";
        $time = ($datetime > 0) ? strtotime($datetime) : time();

        if(date('H:i:s', $time) != '00:00:00'){
             $ttime = " kl. ".date('H:i', $time);
        } else {
            $ttime = "";
        }
        
        if(date('Y-m-d-H') == date('Y-m-d-H', $time)){
            return __("time.justnow").$ttime;
        }
        if(date('Y-m-d') == date('Y-m-d', $time)){
        	return __("time.today").$ttime;
        }
        if(date('Y-m-d', strtotime('-1 day')) == date('Y-m-d', $time)){
            return __("time.yesterday").$ttime;
        }
        if(date('YMW') == date('YMW', $time)){
        	return __("day.".strtolower(date('l', $time))).$ttime;
        }

        $ttime = " ".date("Y", $time)." ".$ttime;

        return date('j', $time).". ".__("month.".strtolower(date('F', $time))).$ttime;
    }

    public static function regular($datetime=0) {
        if($datetime == "0000-00-00 00:00:00") return "Ukjent dato.";
        $time = ($datetime > 0) ? strtotime($datetime) : time();
        if(date('H:i:s', $time) != '00:00:00'){
             $ttime = " kl. ".date('H:i', $time);
        } else {
            $ttime = "";
        }
        $ttime = " ".date("Y", $time)." ".$ttime;

        return date('j', $time).". ".__("month.".strtolower(date('F', $time))).$ttime;
    }

    public static function raw_countdown($to=0, $from=0){
        if($from == 0) $from = time();
        $dt_end = new DateTime(date("Y-m-d H:i:s", $from));
        return $dt_end->diff(new DateTime(date("Y-m-d H:i:s", strtotime($to))));
    }

    public static function countdown($to=0, $from=0) {
        if($from == 0) $from = time();
        $remain = self::raw_countdown($to, $from);

        if($from > strtotime($to))
            return __('time.countdown_done');

        if($remain->d > 0)
            return __("time.countdown_days", array("days" => $remain->d, "hours" => $remain->h));
        else
            return __("time.countdown_hours", array("hours" => $remain->h, "minutes" => $remain->i)); 
    }

    public static function snice($datetime=0) {
        $time = ($datetime > 0) ? strtotime($datetime) : time();

        if(date('H', $time) != '00' && date('i', $time) != '00' && date('s', $time) != '00'){
            $ttime = " kl. ".date('H:i', $time);
        } else {
            $ttime = "";
        }

        $ttime = " ".date("Y", $time);

        if(date('YmdH') == date('YmdH', $time)){
        	return __("time.justnow");
        }
        if(date('Ymd') == date('Ymd', $time)){
        	return __("time.today");
        }
        if(date('YW') == date('YW', $time)){
        	return __("day.".substr(strtolower(date('l', $time)), 0, 3))." kl. ".$ttime;
        }
        return date('j', $time).". ".substr(__("month.".strtolower(date('F', $time))), 0, 3).$ttime;
    }

    public static function month($datetime=0) {
    	$time = ($datetime > 0) ? strtotime($datetime) : time();
    	return __("month.".strtolower(date('F', $time)));
    }
    public static function smonth($datetime=0) {
    	$time = ($datetime > 0) ? strtotime($datetime) : time();
    	return substr(__("month.".strtolower(date('F', $time))), 0, 3);
    }
}