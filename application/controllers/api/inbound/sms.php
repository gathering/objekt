<?php
/**
 * @Author: Simen A.W. Olsen
 * @Date:   2015-03-29 16:40:02
 * @Last Modified by:   Simen A.W. Olsen
 * @Last Modified time: 2015-03-31 12:18:51
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
		if($person){
			$sms->person_id = $person->id;
			$profile = $person->profile();
			if($responsible = $profile->resposible()){
				Notification::send($responsible->id, 'Ny SMS mottatt fra '.$person->firstname.' '.substr(0, 1, $person->surname), $sms->message, url($person->url('sms/inbox')));
			}
		}

		$event->insert($sms);
	}

}