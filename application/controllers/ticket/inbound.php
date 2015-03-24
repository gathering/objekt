<?php

class Ticket_Inbound_Controller extends Base_Controller {

	public $restful = true;

	public function get_index()
	{
		return Response::json(array('status' => 'nothing_to_show'));
	}

	public function post_index()
	{
		$events = json_decode( Input::get('mandrill_events') );
		if(!$events) Response::json(array('error' => 'Not correct format applied'), 505);

		foreach($events as $event){
			$message = new Message;

			$message->from_email = $event->from_email;
			$message->to_email = $event->email;
			$message->subject = $event->subject;
			$message->tags = json_encode($event->tags);
			$message->raw_message = $event->raw_msg;
			$message->html = $event->html;
			$message->text = $event->text;

			$message->save();
		}

		return Response::json(array('status' => 'saved'));
	}

	public function get_synchronize()
	{

		// Synchronize the Mandrill App.
		Message::synchronize();

		return Response::json(array('status' => 'done'));
	}

}