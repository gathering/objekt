<?php

class Notification extends Eloquent {

	static function send($user_id, $title, $message, $url=""){
		
		$event = Config::get('application.event');
		$user = User::find($user_id);
		if(!$user) return false;

		$notification = new self;
		$notification->user_id = $user->id;
		$notification->event_id = $event->id;
		$notification->title = $title;
		$notification->message = $message;
		$notification->url = $url;
		$notification->status = "unread";
		$notification->save();

		if(!empty($user->pushover_key) && $user->pushover_status == "activate"){
			$push = new Pushover();
			$push->setToken('aKd8FNuK2gg2bEFidmhqhcsbFk9JTL');
			$push->setTitle($title);
			$push->setMessage($message);
			$push->setPriority(0);
			$push->setTimestamp(time());
			$push->setSound('pushover');
			if(!empty($url)) $push->setURL($url);

			$push->setUser($user->pushover_key);
			$push->send();
		}

		$pushData = array(
			'title' => $title,
			'message' => $message,
			'url' => $url
			);

		Push::trigger(md5('user_'.$user->id), 'notification', $pushData);

		return $notification;
	}

}
