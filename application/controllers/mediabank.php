<?php

class Mediabank_Controller extends Base_Controller {

	public function action_index()
	{
		$event = Config::get('application.event');
		tplConstructor::set(true);
		return View::make('mediabank.index')->with("event", $event);
	}

	public function action_tag($tag)
	{
		$event = Config::get('application.event');
		$params['index'] = 'mediabank';
		$params['type']  = 'image';
		$params['body']['query']['query_string']['query'] = str_replace(" ", " +", urldecode($tag));

		
		tplConstructor::set(true);
		$elastisk = Elastisk::search($params);
		#die(var_dump($params['body']['query']['query_string']['query'], $elastisk));
		$results = array();

		if($elastisk['hits']['total'] == 0)
			return Redirect::to(Request::referrer())->with('error', __('mediabank.nothing_found'));

		foreach($elastisk['hits']['hits'] as $result){
			$model = Fil3::find($result['_id']);
			if($model) array_push($results, $model);
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
			$fil3->size = $file['filesize'];
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

	public function action_update_file($id){
		$file = Fil3::find($id);
		if(!$file) return Event::first('404');

		$file->tags = Input::get('tags');
		$tag_array = explode(",", $file->tags);
		$file->filename = Input::get('filename');

		if(empty($file->filename)) return Event::first('404');
		$file->save();

		$meta = $file->meta();

		$params = array();
		$params['body']  = array(
			'filename' => $file->filename,
			'event_id' => $file->event_id,
			'url' => $file->url,
			'tags' => $tag_array,
			'hash' => $meta['hash'],
			'uploaded_at' => date("Y-m-d H:i:s")
			);

		$params['index'] = 'mediabank';
		$params['type']  = 'image';
		$params['id']    = $file->id;

		$ret = Elastisk::index($params);

		return "true";
	}

	public function action_delete_file($id){
		$file = Fil3::find($id);
		if(!$file) return Event::first('404');

		$id = $file->id;
		if($file->remove()){
			$params['index'] = 'mediabank';
			$params['type']  = 'image';
			$params['id']    = $id;
			Elastisk::delete($params);
			return true;
		}
		
		return Event::first('505');
	}

	public function action_search(){
		$term = Input::get('search');
		$params['index'] = 'mediabank';
		$params['type']  = 'image';
		$event = Config::get('application.event');
		$params['body']['query']['query_string']['query'] = "*".$term."*";
		$params['body']['filter']['term']['event_id'] = $event->id;

		$elastisk = Elastisk::search($params);
		tplConstructor::set(true);
		$results = array();

		if($elastisk['hits']['total'] == 0)
			return Redirect::to(Request::referrer())->with('error', __('mediabank.nothing_found'));

		foreach($elastisk['hits']['hits'] as $result){
			$model = Fil3::find($result['_id']);
			if($model) array_push($results, $model);
		}
		return View::make('mediabank.tag')->with("results", $results)->with("term", $term);
	}

}