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

	function action_owners($term){

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

		$params['index'] = 'people';
		$params['type']  = 'obj';
		$params['body']['query']['query_string']['query'] = "*".$term."*";
		$params['body']['filter']['term']['event_id'] = $event->id;

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

		$params['index'] = 'profiles';
		$params['type']  = 'obj';
		$params['body']['query']['query_string']['query'] = "*".$term."*";
		$params['body']['filter']['term']['event_id'] = $event->id;

		$elastisk = Elastisk::search($params);
		foreach($elastisk['hits']['hits'] as $result){
			$person = array();
			$profile['name'] = "Profile: ".$result['_source']['name'];
			$profile['value'] = "profile:".$result['_id'];
			array_push($results, $profile);
		}

		return Response::json($results);
		exit;
	}

	function action_view_storage(){
		return View::make('logistics.view_storage');
	}

	function action_add_parcel(){
		return View::make('logistics.add_parcel');
	}
}

?>