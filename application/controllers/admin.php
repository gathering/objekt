<?php

class Admin_Controller extends Base_Controller {

	public function action_index()
	{
		return View::make('home.index');
	}

	public function action_events()
	{
		return View::make('admin.events');
	}

	public function action_event($profile_slug)
	{
		$event = Events::where("slug", "=", $profile_slug)->first();
		if(!$event) return Redirect::to('/admin/events')->with("error", __('admin.errors.not_found'));

		$cloudPrinter = new GoogleCloudPrint;
		$printers = $cloudPrinter->getPrinters();

		$users = array();
		foreach(User::active($event)->get(DB::Raw('users.*')) as $user) array_push($users, array('id' => $user->id, 'text' => $user->username));

		return View::make('admin.event')
				->with("event", $event)
				->with("printers", $printers)
				->with("users", json_encode($users));
	}

	public function action_post_event($profile_slug)
	{
		$event = Events::where("slug", "=", $profile_slug)->first();
		if(!$event) return Redirect::to('/admin/events')->with("error", __('admin.errors.not_found'));	

		$input = Input::all();
		#die(var_dump($input));
		$rules = array(
		    'name'  => 'required|max:255',
		    'date'  => 'required|date_format:Y-m-d',
		    'to_date'  => 'required|date_format:Y-m-d',
		    'slug' => 'required|alpha_dash|max:255|unique:events,slug,'.$event->id,
		    'tags' => 'max:3000',
		    'aid_users' => 'max:3000',
		    'badgeprinter' => 'max:255',
		    'deskprinter' => 'max:255'
		);

		if(!empty($input['welcomeletter'])){
			$rules['welcomeletter'] = 'mimes:pdf';
		}

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		if(!empty($input['features'])){
			$rules['features'] = "";
			$input['features'] = serialize($input['features']);
		}

		unset($rules['welcomeletter']);

		foreach($rules as $name => $rule){
			$event->{$name} = $input[$name];
		}

		$event->save();

		if(!empty($input['welcomeletter'])){
			// TODO: Handle uploading of welcomeletter.
		}

		return Redirect::to('admin/event/'.$event->slug)->with("success", __('admin.saved_success'));

	}

}