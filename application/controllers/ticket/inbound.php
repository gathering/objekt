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

		$response = Mandr::messages()->send(array(
            'html' => nl2br(var_export($events, true)),
            'subject' => 'Return of your message.',
            'from_email' => Lang::line('user.noreply')->get(),
            'from_name' => Lang::line('user.noreply_name')->get(),
            'to' => array(
                array('email' => 'cobraz@cobraz.no')
                )
            )
        ), false);

		foreach($events as $event){
			$message = new Message;

			$message->from_email = $event->msg->from_email;
			$message->to_email = $event->msg->email;
			$message->subject = $event->msg->subject;
			$message->tags = json_encode($event->msg->tags);
			$message->raw_message = $event->msg->raw_msg;
			$message->html = $event->msg->html;
			$message->text = $event->msg->text;

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