<?php

// ALTER TABLE  `people` ADD INDEX (  `firstname` ,  `surname` ,  `phone` ,  `email` ) ;
// ALTER TABLE  `profiles` ADD INDEX (  `name` ,  `website` ,  `email` ) ;

class Search_Controller extends Controller {
	function action_search_accreditation(){


		$search = Input::get('search');

		if(!preg_match('/\*/', $search)){
			$search = '*'.$search.'*';
		}

		$results = array();

		$event = Config::get('application.event');
		$folks = Person::raw_where("match (`firstname` ,  `surname` ,  `phone` ,  `email`) against (? IN BOOLEAN MODE)", array($search))
					->where("event_id", "=", $event->id)->get();
		foreach($folks as $dude){
			if($dude->is_current_event()){
				$data = new StdClass;
				$data->name = $dude->firstname." ".$dude->surname;
				$data->url = $dude->url();
				array_push($results, $data);
			}
		}

		if(count($results) == 1){
			return Redirect::to($results[0]->url);
		}
		if(count($results) == 0){
			return Redirect::to(Request::referrer())->with('error',  __('common.nothing_found'));
		}

		return View::make('accreditation.index')->with("results", $results);


	}
	function action_index($search=""){
		$search_for_profile = false;
		$search_for_person = false;
		switch($search){
			case "person":
				$search = Input::get('search');
				$search_for_person = true;
			break;
			case "profile":
				$search = Input::get('search');
				$search_for_profile = true;
			break;
			case "": default:
				if(empty($search) && Input::get('search')){
					$search = Input::get('search');
				} elseif(empty($search)) {
					return Event::first(404);
				}
				$search_for_profile = true;
				$search_for_person = true;
			break;
		}

		if(!preg_match('/\*/', $search)){
			$search = '*'.$search.'*';
		}

		$results = array();
		$event = Config::get('application.event');

		/*if($search_for_person){
			$folks = Person::raw_where("match (`firstname` ,  `surname` ,  `phone` ,  `email`) against (? IN BOOLEAN MODE)", array($search))
						->where("event_id", "=", $event->id)->get();
			foreach($folks as $dude){
				if($dude->is_current_event()){
					$data = new StdClass;
					$data->name = $dude->firstname." ".$dude->surname;
					$data->url = $dude->url();
					array_push($results, $data);
				}
			}
		}*/

		if($search_for_profile){

			/*$profiles = profile::raw_where("match (`name` ,  `website` ,  `email`) against (? IN BOOLEAN MODE)", array($search))
						->where("event_id", "=", $event->id)->get();
			foreach($profiles as $profile){
				if($profile->is_current_event()){
					$data = new StdClass;
					$data->name = $profile->name;
					$data->url = $profile->url();
					array_push($results, $data);
				}
			}*/

			$params['index'] = 'profiles';
			$params['type']  = 'obj';
			$params['body']['query']['wildcard']['name'] = $search;
			$params['body']['filter']['term']['event_id'] = $event->id;

			$results = Elastisk::search($params);
			#die(var_dump($results));
		}

		if($results['hits']['total'] == 1){
			return Redirect::to(url('profile/'.$results['hits']['hits'][0]['_source']['slug']));
		}
		if($results['hits']['total'] == 0){
			return Redirect::to(Request::referrer())->with('error',  __('common.nothing_found'));
		}

		$type = isset($_POST['type']) ? $_POST['type'] : "";

		switch($type){
			case "accreditiation":
				return View::make('accreditation.index')->with("results", $results);
			break;
		}

		return View::make('search.results')->with("results", $results);

	}

}

?>