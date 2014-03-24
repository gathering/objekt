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

		if(!empty($input['welcomeletter']['name'])){
			$rules['welcomeletter'] = 'mimes:pdf';
		}

		if(!empty($input['map']['name'])){
			$rules['map'] = 'mimes:pdf';
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
		unset($rules['map']);

		foreach($rules as $name => $rule){
			$event->{$name} = $input[$name];
		}

		$event->save();

		if(!empty($input['welcomeletter']['name'])){
			
			$ext = substr(strrchr($input['welcomeletter']['name'],'.'),1);
			$filename = $input['welcomeletter']['name'];

			$event = Config::get('application.event');
			$filepath = $event->s3_slug."/welcomeletters/".$filename;

			// Upload to S3
			S3::putObject(S3::inputFile($input['welcomeletter']['tmp_name'], false), "s3.obj.no", $filepath, S3::ACL_PUBLIC_READ);
			
			$file = $event->files()
						->where("type", "=", "welcomeletter")
						->where("filename", "=", $filename)->first();
			if(!$file) $file = new Fil3;

			$file->type = "welcomeletter";
			$file->event_id = $event->id;
			$file->filename = $filename;
			$file->s3_path = $filepath;
			$file->url = "http://s3.obj.no/".$filepath;

			$file->save();
		}

		if(!empty($input['map']['name'])){
			
			$ext = substr(strrchr($input['map']['name'],'.'),1);
			$filename = "map.".$ext;

			$event = Config::get('application.event');
			$filepath = $event->s3_slug."/".$filename;
			$filepath_jpeg = $event->s3_slug."/map.jpg";

			$fp_pdf = fopen($input['map']['tmp_name'], 'rb');

		    $img = new imagick(); // [0] can be used to set page number
		    $img->readImageFile($fp_pdf);
		    $img->setImageFormat( "jpg" );
		    $img->setImageCompression(imagick::COMPRESSION_JPEG); 
		    $img->setImageCompressionQuality(90);
		    $img->setResolution(1200,1200);
		    $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);

			// Upload to S3
			S3::putObject(S3::inputResource(imagecreatefromstring($img->getImageBlob()), $img->getSize()), "s3.obj.no", $filepath_jpeg, S3::ACL_PUBLIC_READ);
			S3::putObject(S3::inputFile($input['map']['tmp_name'], false), "s3.obj.no", $filepath, S3::ACL_PUBLIC_READ);

			$file = $event->files()
						->where("type", "=", "map")
						->where("filename", "=", $filename)->first();
			if(!$file) $file = new Fil3;

			$file->type = "map";
			$file->event_id = $event->id;
			$file->filename = $filename;
			$file->s3_path = $filepath;
			$file->url = "http://s3.obj.no/".$filepath;

			$file->save();
		}

		return Redirect::to('admin/event/'.$event->slug)->with("success", __('admin.saved_success'));

	}

	public function action_delete_file($event_slug, $file_id="")
	{
		if(!empty($file_id)){
			$event = Events::where("slug", "=", $event_slug)->first();
			if(!$event) return Redirect::to('/admin/events')->with("error", __('admin.errors.not_found'));	

			$file = $event->files()->find($file_id);
		} else {
			$file = Fil3::find($event_slug);
		}

		if(!$file) return Redirect::to(Request::referrer())->with('error',__('admin.errors.file_not_found'));

		S3::deleteObject("s3.obj.no", $file->s3_path);
		$file->delete();

		return Redirect::to(Request::referrer())->with('success', __('admin.file_deleted'));
	}

}