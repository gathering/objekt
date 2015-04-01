<?php
/**
 * @Author: Simen A.W. Olsen
 * @Date:   2015-03-29 16:40:02
 * @Last Modified by:   Simen A.W. Olsen
 * @Last Modified time: 2015-04-01 14:04:44
 */


class API_SMS_Controller extends Controller {

	public $restful = true;

	function post_inbound(){

		$sms = new SMS;

		$sms->from = Input::get('sender');
		$sms->message = Input::get('text');
		$sms->received_at = Input::get('received_at');
		$sms->success = 1;
		$sms->type = "inbound";
		$sms->to = Input::get('receiver');

		$event = Config::get('application.event');

		$person = $event->people()->where('phone', 'LIKE', '%'.Input::get('sender'))->first();
		if($person){
			$sms->person_id = $person->id;
			$profile = $person->profile();
			if($responsible = $profile->responsible()){
				Notification::send($responsible->id, 'SMS mottatt fra '.$person->firstname.' '.substr($person->surname, 0, 1).'. ('.$profile->name.')', $sms->message, url($event->slug.'/'.$person->url('sms/inbox')));

			}
		}

		$event->sms()->insert($sms);
	}

}