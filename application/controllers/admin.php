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

		return View::make('admin.event')->with("event", $event)->with("printers", $printers);
	}

	public function action_post_event($profile_slug)
	{
		var_dump(Input::get()); exit;
	}

}