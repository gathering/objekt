<?php

class Mediabank_Controller extends Base_Controller {

	public function action_index()
	{
		return View::make('mediabank.index');
	}

	public function action_upload()
	{
		header('Pragma: no-cache');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Content-Disposition: inline; filename="files.json"');
		header('X-Content-Type-Options: nosniff');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
		header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

		$event = Config::get('application.event');
		$filepath = $event->s3_slug."/mediabank";
		$files = S3::UploadS3($filepath);
		var_dump($files); exit;
	}

}