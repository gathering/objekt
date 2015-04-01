<?php

class API_Accreditation_Controller extends Controller {
	
	public $restful = true;

	function get_check($cardId = '') {
		return $this->post_check($cardId);
	}
	
	function post_check($cardId = '') {

		$event = Config::get('application.event');
		if(!$event)
			return Response::error('404');

		// Check if the card-id is supplied in URL
		if(empty($cardId))
			// Check if the card-id is supplied with GET or POST.
			$cardId = Input::get('card_id');
		
		if(empty($cardId)){
			// If neither is available, check raw POST.
			if(!$input = file_get_contents("php://input"))
				return Response::json(['status' => 0]);

			if(!$input = json_decode($input))
				return Response::json(['status' => 0]);

			$cardId = $input->card_id;			
		}
		
		$entry = Entry::where('ident', '=', $cardId)
					  ->where('status', '=', 'valid');

		if($entry->count())
			return Response::json(['status' => 1]);

		return Response::json(['status' => 0]);
	}

}