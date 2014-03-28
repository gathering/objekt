<?php

class Mediabank_Controller extends Base_Controller {

	public function action_index()
	{
		$event = Config::get('application.event');
		return View::make('mediabank.index')->with("event", $event);
	}

	public function action_tag($tag)
	{
		$event = Config::get('application.event');
		$params['index'] = 'mediabank';
		$params['type']  = 'image';
		$params['body']['query']['wildcard']['tags'] = $tag;
		
		tplConstructor::set(true);
		$elastisk = Elastisk::search($params);
		$results = array();

		if($elastisk['hits']['total'] == 0)
			return Redirect::to(Request::referrer())->with('error', __('mediabank.nothing_found'));

		foreach($elastisk['hits']['hits'] as $result){
			$model = Fil3::find($result['_id']);
			array_push($results, $model);
		}
		return View::make('mediabank.tag')->with("results", $results);
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
				
			$tags = array_merge($tags, $event->tags()); // Add event-tags.

			foreach($tags as $int => $tag) if(empty($tag)) unset($tags[$int]); // Clean empty tags.
			$tags = array_values($tags);

			$fil3 = new Fil3;
			$fil3->type = "mediabank";
			$fil3->event_id = $event->id;
			$fil3->filename = $file['filename'];
			$fil3->s3_path = $file['path'];
			$fil3->url = "http://s3.obj.no/".$file['path'];
			$fil3->tags = implode(",", $tags);
			$fil3->meta = serialize($file);
			$fil3->save();

			$params = array();
			$params['body']  = array(
				'filename' => $file['filename'],
				'event_id' => $event->id,
				'url' => "http://s3.obj.no/".$file['path'],
				'tags' => $tags,
				'hash' => $file['hash'],
				'uploaded_at' => date("Y-m-d H:i:s")
				);

			$params['index'] = 'mediabank';
			$params['type']  = 'image';
			$params['id']    = $fil3->id;

			$ret = Elastisk::index($params);
		}
		die("true");
	}

}