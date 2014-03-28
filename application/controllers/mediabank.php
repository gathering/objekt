<?php

class Mediabank_Controller extends Base_Controller {

	public function action_index()
	{
		$event = Config::get('application.event');
		return View::make('mediabank.index')->with("event", $event);
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

		foreach($files as $file){
			$tags = array();
			if(isset($file['xmp']['Keywords']) && is_array($file['xmp']['Keywords']) && count($file['xmp']['Keywords']))
				foreach($file['xmp']['Keywords'] as $int => $keyword) array_push($tags, strtolower($keyword));
			

			$fil3 = new Fil3;
			$fil3->type = "mediabank";
			$fil3->event_id = $event->id;
			$fil3->s3_path = $file['path'];
			$fil3->url = "http://s3.obj.no/".$file['path'];
			$fil3->tags = implode(",", $tags);
			$fil3->meta = serialize($file);
			$fil3->save();

			$params = array();
			$params['body']  = array(
				'event_id' => $event->id,
				'url' => "http://s3.obj.no/".$file['path'],
				'tags' => $tags
				);

			$params['index'] = 'mediabank';
			$params['type']  = 'image';
			$params['id']    = $file['hash'];

			// Document will be indexed to my_index/my_type/my_id
			$ret = Elastisk::index($params);
		}
		die("true");
	}

}