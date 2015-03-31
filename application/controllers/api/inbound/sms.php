<?php
/**
 * @Author: Simen A.W. Olsen
 * @Date:   2015-03-29 16:40:02
 * @Last Modified by:   Simen A.W. Olsen
 * @Last Modified time: 2015-03-31 12:11:30
 */

class API_Inbound_SMS_Controller extends Controller {

	public $restful = true;

	function post_index(){

		$sms = new SMS;

		$sms->from = Input::get('sender');
		$sms->message = Input::get('text');
		$sms->received_at = Input::get('received_at');
		$sms->to = Input::get('receiver');

		$event = Config::get('application.event');

		$person = $event->people()->where('phone', 'LIKE', '%'.$sms->from)->first();
		if($person)
			$sms->person_id = $person->id;

		$event->insert($sms);
	}

}