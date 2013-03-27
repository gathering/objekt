<?php

// ALTER TABLE  `people` ADD INDEX (  `firstname` ,  `surname` ,  `phone` ,  `email` ) ;
// ALTER TABLE  `sponsors` ADD INDEX (  `name` ,  `website` ,  `email` ) ;

class Search_Controller extends Controller {
	function action_search_accreditation(){


		$search = Input::get('search');

		if(!preg_match('/\*/', $search)){
			$search = '*'.$search.'*';
		}

		$results = array();

		$folks = Person::raw_where("match (`firstname` ,  `surname` ,  `phone` ,  `email`) against (? IN BOOLEAN MODE)", array($search))->get();
		foreach($folks as $dude){
			if($dude->is_current_event()){
				$data = new StdClass;
				$data->name = $dude->firstname." ".$dude->surname;
				$data->url = $dude->url("accreditation");
				array_push($results, $data);
			}
		}

		if(count($results) == 1){
			return Redirect::to($results[0]->url);
		}
		if(count($results) == 0){
			return Redirect::to(Request::referrer())->with('error',  __('common.nothing_found'));
		}

		return View::make('search.results')->with("results", $results);


	}
	function action_index($search=""){
		$search_for_sponsor = false;
		$search_for_person = false;
		switch($search){
			case "person":
				$search = Input::get('search');
				$search_for_person = true;
			break;
			case "sponsor":
				$search = Input::get('search');
				$search_for_sponsor = true;
			break;
			case "": default:
				if(empty($search) && Input::get('search')){
					$search = Input::get('search');
				} elseif(empty($search)) {
					return Event::first(404);
				}
				$search_for_sponsor = true;
				$search_for_person = true;
			break;
		}

		if(!preg_match('/\*/', $search)){
			$search = '*'.$search.'*';
		}

		$results = array();

		if($search_for_person){
			$folks = Person::raw_where("match (`firstname` ,  `surname` ,  `phone` ,  `email`) against (? IN BOOLEAN MODE)", array($search))->get();
			foreach($folks as $dude){
				if($dude->is_current_event()){
					$data = new StdClass;
					$data->name = $dude->firstname." ".$dude->surname;
					$data->url = $dude->url();
					array_push($results, $data);
				}
			}
		}

		if($search_for_sponsor){
			$sponsors = Sponsor::raw_where("match (`name` ,  `website` ,  `email`) against (? IN BOOLEAN MODE)", array($search))->get();
			foreach($sponsors as $sponsor){
				if($sponsor->is_current_event()){
					$data = new StdClass;
					$data->name = $sponsor->name;
					$data->url = $sponsor->url();
					array_push($results, $data);
				}
			}
		}

		if(count($results) == 1){
			return Redirect::to($results[0]->url);
		}
		if(count($results) == 0){
			return Redirect::to(Request::referrer())->with('error',  __('common.nothing_found'));
		}

		return View::make('search.results')->with("results", $results);

	}

}

?>