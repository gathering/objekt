<?php

class Logistics_Controller extends Base_Controller {

	function action_add(){
		return View::make('logistics.add');
	}

	function action_post_add(){

		$input = Input::all();
		$input['slug'] = Str::slug($input['name']);
		$rules = array(
		    'name'  => 'required|max:255|unique:storages,name',
		    'slug'  => 'required|max:255|unique:storages,slug'
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$storage = new Storage;
		$storage->name = $input['name'];
		$storage->slug = $input['slug'];

		$event = Config::get('application.event');
		$event->storages()->insert($storage);

		return Redirect::to('/logistics/'.$storage->slug)->with('success', __('logistics.storage_added'));
	}

	function action_owners($term="*", $wildcard=true, $arrayReturn=false){


		$results = array();

		/*
			array(
				'name' => 'Simen A. W. Olsen (1337)',
				'value' => 'wb:1337'
			)
		*/

		$event = Config::get('application.event');
		if(method_exists($event->special(), "searchUsers")){
			$results = array_merge($results, $event->special()->searchUsers($term));
		}

		if($wildcard) $term = "*".$term."*";

		$params['index'] = 'people';
		$params['type']  = 'obj';
		$params['body']['query']['query_string']['query'] = $term;
		$params['body']['filter']['term']['event_id'] = $event->id;
		if($term == "*") $params['body']['size'] = 10000;

		$elastisk = Elastisk::search($params);
		foreach($elastisk['hits']['hits'] as $result){
			$model = Person::find($result['_id']);
			if($model != NULL){
				$person = array();
				$person['name'] = "Person: ".$model->firstname." ".$model->surname;
				$person['value'] = "person:".$model->id;
				array_push($results, $person);
			}
		}

		unset($params);

		$params['index'] = 'profiles';
		$params['type']  = 'obj';
		$params['body']['query']['query_string']['query'] = $term;
		$params['body']['filter']['term']['event_id'] = $event->id;
		if($term == "*") $params['body']['size'] = 10000;

		$elastisk = Elastisk::search($params);
		foreach($elastisk['hits']['hits'] as $result){
			$person = array();
			$profile['name'] = "Profile: ".$result['_source']['name'];
			$profile['value'] = "profile:".$result['_id'];
			array_push($results, $profile);
		}

		if($arrayReturn) return $results;

		return Response::json($results);
		exit;
	}

	function action_view_storage(){
		return View::make('logistics.view_storage');
	}

	function action_add_parcel(){
		return View::make('logistics.add_parcel');
	}

	/*
		- Meldingssystem på kolli (Meld hendelse på kolli)
		Eks. Skjerm som har knekt fot. Sende melding om at hendenlsen har skjedd
	*/

	function action_post_parcel_single(){

		Validator::register('owner', function($attribute, $value, $parameters)
		{
			$value = explode(":", str_replace(" ", "", $value));
			$value = @$value[1];
			if(count($this->action_owners(strval($value), false, true)) > 0 && !empty($value)) return true;
			return false;
		});

		$input = Input::all();
		$rules = array(
		    'name'  => 'required|max:255',
		    'description' => 'max:3000',
		    'comment' => 'max:3000',
		    'owner' => 'required|owner'
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$parcel = new Parcel;
		foreach($rules as $rule => $validation){
			$parcel->{$rule} = $input[$rule];
		}
		$parcel->tags = $input['tags'];
		$parcel->user_id = Auth::user()->id;
		$parcel->serialnumber = $input['serialnumber'];
		$storage = Config::get('logistics.storage');
		$parcel = $storage->parcels()->insert($parcel);

		$params['body']  = $parcel->to_array();
		unset($params['body']['updated_at']); // Not needed.
		unset($params['body']['created_at']); // Not needed.

		$tags = array_unique(array_merge(explode(",", $parcel->tags), array_map(function($value){
			$value = strtolower($value);
			return Str::slug($value);
		}, explode(" ", $input['name']))));

		$params['body']['tags'] = $tags;

		$params['index'] = 'logistics';
		$params['type']  = 'parcel';
		$params['id']    = $parcel->id;
		Elastisk::index($params);


		return Redirect::to('logistics/'.$storage->slug.'/'.$parcel->id.'/action');
	}

	function action_parcel_action($storage, $parcel_id){
		$parcel = Parcel::find($parcel_id);
		if(!$parcel) return Redirect::to('logistics/'.$storage)->with('error', __('logistics.parcel_not_found'));
		return View::make('logistics.parcel_action')->with("parcel", $parcel);
	}

	function action_parcel($storage, $parcel_id){
		$parcel = Parcel::find($parcel_id);
		if(!$parcel) return Redirect::to('logistics/'.$storage)->with('error', __('logistics.parcel_not_found'));
		return View::make('logistics.parcel_view')->with("parcel", $parcel);
	}

	function action_handout($storage, $parcel_id){
		$parcel = Parcel::find($parcel_id);
		if(!$parcel) return Redirect::to('logistics/'.$storage)->with('error', __('logistics.parcel_not_found'));

		if($parcel->current_status()->status == "handout")
			return Redirect::to(Request::referrer())->with('error', __('logistics.parcel_already_handedout'));

		return View::make('logistics.parcel_handout')->with("parcel", $parcel);
	}

	function action_post_handout($storage, $parcel_id){
		$parcel = Parcel::find($parcel_id);
		if(!$parcel) return Redirect::to('logistics/'.$storage)->with('error', __('logistics.parcel_not_found'));

		Validator::register('owner', function($attribute, $value, $parameters)
		{
			$value = explode(":", str_replace(" ", "", $value));
			$value = @$value[1];
			if(count($this->action_owners(strval($value), false, true)) > 0 && !empty($value)) return true;
			return false;
		});

		$input = Input::all();
		$rules = array(
		    'receiver' => 'required|owner'
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$log = new Parcellog;
		$log->status = "handout";
		$log->user_id = Auth::user()->id;
		$log->receiver_id = $input['receiver'];
		$log->expected_back = date("Y-m-d H:i:s", strtotime($input['expected_back'])); 
		$parcel->logs()->insert($log);

		$storage = Config::get('logistics.storage');
		return Redirect::to('/logistics/'.$storage->slug.'/'.$parcel->id)->with('success', __('logistics.parcel_handedout'));
	}

	function action_receive($storage, $parcel_id){
		$parcel = Parcel::find($parcel_id);
		if(!$parcel) return Redirect::to(Request::referrer())->with('error', __('logistics.parcel_not_found'));

		$log = new Parcellog;
		$log->status = "receive";
		$log->user_id = Auth::user()->id;
		$log->expected_back = date("Y-m-d H:i:s"); 
		$parcel->logs()->insert($log);

		$storage = Config::get('logistics.storage');
		return Redirect::to('/logistics/'.$storage->slug.'/'.$parcel->id)->with('success', __('logistics.parcel_received'));
	}

	function action_search(){

		tplConstructor::set(true);

		$search = Input::get('search');
		$event = Config::get('application.event');

		$show = Input::get('show') ? Input::get('show') : 10;
		$page = Input::get('page') ? Input::get('page') : 1;

		$params['index'] = 'logistics';
		$params['type']  = 'parcel';
		$params['body']['query']['query_string']['query'] = $search;
		$params['body']['from'] = $page == 1 ? 0 : $show*$page;
		$params['body']['size'] = $show;

		#die(var_dump($params));

		$results = array();
		$cleanup = false;
		$elastisk = Elastisk::search($params);

		foreach($elastisk['hits']['hits'] as $result){
			$parcel = new stdClass;
			$parcel->name = $result['_source']['name'];
			$storage = Storage::find($result['_source']['storage_id']);
			$parcel->url = url('logistics/'.$storage->slug.'/'.$result['_id']);

			if($cleanup){
				if(!$parcel = Parcel::find($result['_id'])){
					$delete['index'] = 'logistics';
					$delete['type']  = 'parcel';
					$delete['id']    = $result['_id'];
					Elastisk::delete($delete);
					continue;
				}
			}

			array_push($results, $parcel);
		}

		if(count($results) == 1){
			return Redirect::to($results[0]->url);
		}

		if(count($results) == 0){
			return Redirect::to(Request::referrer())->with('error',  __('common.nothing_found'));
		}

		$pagination = new stdClass;
		$pagination->num_pages = 1;
		$pagination->total_hits = $elastisk['hits']['total'];
		$pagination->current = Input::get('page') ? Input::get('page') : 1;
		if($elastisk['hits']['total'] > $show){
			$pagination->num_pages = $elastisk['hits']['total']/$show;
			$pagination->prev = ($page-1) > 0 ? ($page-1) : 0;
			$pagination->next = ($page+1) < $pagination->num_pages ? ($page+1) : 0;			
		}

		return View::make('logistics.search')->with("results", $results)->with("pagination", $pagination);
	}

	function action_post_parcel_bulk(){
		Validator::register('owner', function($attribute, $value, $parameters)
		{
			$value = explode(":", str_replace(" ", "", $value));
			$value = @$value[1];
			if(count($this->action_owners(strval($value), false, true)) > 0 && !empty($value)) return true;
			return false;
		});

		$input = Input::all();
		$rules = array(
		    'name'  => 'required|max:255',
		    'description' => 'max:3000',
		    'comment' => 'max:3000',
		    'owner' => 'required|owner'
		);

		foreach($rules as $rule => $validation){
			if(isset($input['unique'][$rule]))
				unset($rules[$rule]);
		}

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		if(!is_array($input['unique']))
			return Redirect::to(Request::referrer())->with('error', __('logistics.no_unique_fields'))->with('post', $input);

		$fields = array();
		foreach($input['unique'] as $name => $field){
			array_push($fields, $name);
		}

		$rules['tags'] = "";

		$staticFields = array();
		foreach($rules as $rule => $validation) $staticFields[$rule] = $input[$rule];

		return View::make('logistics.bulk')->with("fields", $fields)->with("staticFields", $staticFields);
	}

	function action_bulkline(){
		Validator::register('owner', function($attribute, $value, $parameters)
		{
			$value = explode(":", str_replace(" ", "", $value));
			$value = @$value[1];
			if(count($this->action_owners(strval($value), false, true)) > 0 && !empty($value)) return true;
			return false;
		});

		$input = Input::all();
		$rules = array(
		    'name'  => 'required|max:255',
		    'description' => 'max:3000',
		    'comment' => 'max:3000',
		    'owner' => 'required|owner'
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
			return Response::json(array('status' => 'failed', 'message' => $validation->errors), 404);
		}

		$parcel = new Parcel;
		foreach($rules as $rule => $validation){
			$parcel->{$rule} = $input[$rule];
		}

		$parcel->tags = $input['tags'];
		$parcel->user_id = Auth::user()->id;
		$parcel->serialnumber = $input['serialnumber'];

		$storage = Config::get('logistics.storage');
		$parcel = $storage->parcels()->insert($parcel);

		$params['body']  = $parcel->to_array();
		unset($params['body']['updated_at']); // Not needed.
		unset($params['body']['created_at']); // Not needed.

		$tags = array_unique(array_merge(explode(",", $parcel->tags), array_map(function($value){
			$value = strtolower($value);
			return Str::slug($value);
		}, explode(" ", $input['name']))));

		$params['body']['tags'] = $tags;

		$params['index'] = 'logistics';
		$params['type']  = 'parcel';
		$params['id']    = $parcel->id;
		Elastisk::index($params);

		return Response::json(array('status' => 'created', 'id' => $parcel->id));
	}

	function action_edit_bulkline(){
		Validator::register('owner', function($attribute, $value, $parameters)
		{
			$value = explode(":", str_replace(" ", "", $value));
			$value = @$value[1];
			if(count($this->action_owners(strval($value), false, true)) > 0 && !empty($value)) return true;
			return false;
		});

		$input = Input::all();
		$parcel = Parcel::find($input['id']);
		if(!$parcel){
			return Response::json(array('status' => 'failed', 'message' => "Parcel not found"), 404);
		}

		$rules = array(
		    'name'  => 'required|max:255',
		    'description' => 'max:3000',
		    'comment' => 'max:3000',
		    'owner' => 'required|owner'
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
			return Response::json(array('status' => 'failed', 'message' => $validation->errors), 404);
		}

		foreach($rules as $rule => $validation){
			$parcel->{$rule} = $input[$rule];
		}

		$parcel->tags = $input['tags'];
		$parcel->user_id = Auth::user()->id;

		$storage = Config::get('logistics.storage');
		$parcel->save();

		$params['body']  = $parcel->to_array();
		unset($params['body']['updated_at']); // Not needed.
		unset($params['body']['created_at']); // Not needed.

		$tags = array_unique(array_merge(explode(",", $parcel->tags), array_map(function($value){
			$value = strtolower($value);
			return Str::slug($value);
		}, explode(" ", $input['name']))));

		$params['body']['tags'] = $tags;

		$params['index'] = 'logistics';
		$params['type']  = 'parcel';
		$params['id']    = $parcel->id;
		Elastisk::index($params);

		return Response::json(array('status' => 'edited', 'id' => $parcel->id));
	}

	function action_parcel_print($storage, $parcel_id){
		$parcel = Parcel::find($parcel_id);
		if(!$parcel) return Redirect::to(Request::referrer())->with('error', __('logistics.parcel_not_found'));

		$barcode = PDF::view('barcode.parcel')->with("parcel", $parcel);

		#$barcode->get();

		$cloudprint = new GoogleCloudPrint;
		$event = Config::get('application.event');
		$return = $cloudprint->submit(
			$event->barcodeprinter,
			"BARCODe-".time(),
			$barcode->string(),
			"application/pdf"
			);

		$storage = Config::get('logistics.storage');

		return Redirect::to('/logistics/'.$storage->slug.'/'.$parcel->id)->with('success', __('logistics.barcode_printed'));
	}
}

?>