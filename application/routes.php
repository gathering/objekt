<?php

// API

Route::group(array('before' => 'basic-auth|event|can_api'), function(){
	Route::controller('api.profile.person');
	Route::controller('api.profile.contact_persons');
});

Route::group(array('before' => 'auth|event'), function()
{
	Route::get('/', 'home@index');

	Route::get('/pushover', 'users@pushover');
	Route::post('/pushover', 'users@post_pushover');

	Route::post('/pusher_auth', function(){
		$presence_data = array('name' => Auth::user()->username);
		echo Push::presence_auth(Input::get('channel_name'), Input::get('socket_id'), Auth::user()->id, $presence_data);
		exit;
	});

	Route::get('/aid', function(){

		$event = Config::get('application.event');
		$push = new Pushover();
		$push->setToken('aKd8FNuK2gg2bEFidmhqhcsbFk9JTL');
		$push->setTitle(__('user.aid_title'));
		$push->setMessage(sprintf(__('user.aid_message'), Auth::user()->username));
		$push->setPriority(2);
		$push->setRetry(30);
		$push->setExpire(3600);
		$push->setTimestamp(time());
		$push->setSound('siren');

		$aid_users = $event->aid();
		if(empty($aid_users) || count($aid_users) < 0)
			return Redirect::to(Request::referrer())->with('error', __('user.aid_no_aid_users'));
		foreach($aid_users as $user){
			$push->setUser($user->pushover_key);
			$push->send();
		}

		return Redirect::to(Request::referrer())->with('success', __('user.aid_success'));
	});

	Route::get('/logout', function(){
		Auth::logout();
		return Redirect::to('/');
	});

});

/* Admin */
Route::group(array('before' => 'auth|is_superadmin|event'), function()
{
	Route::get('/admin', 'admin@index');
	Route::get('/admin/events', 'admin@events');

	Route::get('/admin/event/(:any)', 'admin@event');
	Route::post('/admin/event/(:any)', 'admin@post_event');

	Route::get('/admin/event/(:any)/delete_file/(:num)', 'admin@delete_file');

	Route::get('/admin/event/(:any)/deactivate', function($profile_slug){
		$event = Events::where("slug", "=", $profile_slug)->first();
		if(!$event) return Redirect::to('/admin/events')->with("error", __('admin.errors.not_found'));

		$event->status = 'deactivated';
		$event->save();

		return Redirect::to('/admin/events')->with("success", __('admin.deactivated'));
	});

	Route::get('/memo', function(){
		return View::make('memo');
	});

	Route::post('/memo', function(){
		$title = Input::get('title');
		$content = Input::get('content');
		$memo = PDF::view('memo')->with("content", $content);
		if(!empty($title))
			$memo->with("title", $title);
		$cloudprint = new GoogleCloudPrint;
		$event = Config::get('application.event');
		$return = $cloudprint->submit(
			$event->deskprinter,
			"MEMO-".time(),
			$memo->string(),
			"application/pdf"
			);

		return Redirect::to('/')->with("success", "Memo sent..");
	});
});

#Route::group(array('before' => 'event'), function(){
	Route::controller('ticket.inbound');
#});

/* Generic Admin */
Route::group(array('before' => 'auth|can_admin|event'), function()
{
	Route::get('/elastisk', function(){
		#$indexParams['index']  = 'mediabank';
		#Elastisk::indices()->create($indexParams);
		$event = Config::get('application.event');
		$event->special()->synchronizeUsers();
	});

	Route::get('/clearUsers', function(){
		foreach(Person::all() as $person){
			if(!$person->profile()){
				$person->delete();
			}
		}
	});
});

/* Mediabank */
Route::group(array('before' => 'auth|can_mediabank|event'), function()
{
	Route::get('/mediabank', 'mediabank@index');
	Route::post('/mediabank', 'mediabank@upload');

	Route::get('/mediabank/tag/(:any)', 'mediabank@tag');
	Route::post('/mediabank/update_file/(:num)', 'mediabank@update_file');
	Route::post('/mediabank/search', 'mediabank@search');
	Route::post('/mediabank/delete_file/(:num)', 'mediabank@delete_file');

	Route::get('/mediabank/repopulate', 'mediabank@repopulate');
});

/* Notifications */
Route::group(array('before' => 'auth|event'), function()
{
	Route::get('/notifications', function(){
		$user = User::find(Auth::user()->id);
		return View::make('user.notifications')->with("notifications", $user->notifications()->order_by("created_at", "desc")->get());
	});
	Route::get('/notifications/readall', function(){
		$user = User::find(Auth::user()->id);
		$notifications = $user->notifications()->update(array("status" => "read"));
		die("true");
	});
});

/* Users */
Route::group(array('before' => 'auth|can_users|event'), function()
{
	Route::get('/users', 'users@index');
	Route::get('/users/add', array('before' => 'can_add_users', 'uses' => 'users@add'));
	Route::post('/users/add', array('before' => 'can_add_users', 'uses' => 'users@post_add'));

	Route::get('/user/(:num)/reset-password', array('before' => 'can_reset_user_password', 'uses' => 'users@reset_password'));
	Route::post('/user/(:num)/reset-password', array('before' => 'can_reset_user_password', 'uses' => 'users@post_reset_password'));

	Route::get('/user/(:num)/delete-user', array('before' => 'can_delete_user', 'uses' => 'users@delete_user'));
	Route::post('/user/(:num)/delete-user', array('before' => 'can_delete_user', 'uses' => 'users@post_delete_user'));

	Route::get('/user/(:num)/edit', array('before' => 'can_edit_user', 'uses' => 'users@edit'));
	Route::post('/user/(:num)/edit', array('before' => 'can_edit_user', 'uses' => 'users@post_edit'));

	Route::get('/users/roles', array('before' => 'can_manage_roles', 'uses' => 'users@roles'));
	Route::get('/users/roles/add', array('before' => 'can_manage_roles', 'uses' => 'users@add_role'));
	Route::get('/users/role/(:num)', array('before' => 'can_manage_roles', 'uses' => 'users@edit_role'));
	Route::post('/users/role/(:num)', array('before' => 'can_manage_roles', 'uses' => 'users@post_edit_role'));
	Route::post('/users/roles/add', array('before' => 'can_manage_roles', 'uses' => 'users@post_add_role'));
	Route::get('/users/role/(:num)/delete', array('before' => 'can_manage_roles', 'uses' => 'users@delete_role'));
});


/* Accreditation */
Route::get('/accreditation/controll/(:any)/(:any)', array('uses' => 'accreditation@controll', 'before' => 'event'));
Route::group(array('before' => 'auth|can_accreditation|event'), function()
{
	Route::get('/accreditation', 'accreditation@index');
	Route::post('/accreditation', 'search@search_accreditation');

	Route::get('/accreditation/wristband/(:any)/(:any)', 'accreditation@wristband');
	Route::get('/accreditation/wristband/(:any)/(:any)/(:any)', 'accreditation@wristband');

	Route::get('/accreditation/departed/(:any)/(:any)', 'accreditation@departed');
	Route::get('/accreditation/departed/(:any)/(:any)/(:any)', 'accreditation@departed');

	Route::get('/accreditation/print/(:any)/(:any)', array('before' => 'can_accreditation_print_badge', 'uses' => 'accreditation@print'));
	Route::get('/accreditation/print/(:any)/(:any)/(:any)', array('before' => 'can_accreditation_print_badge', 'uses' => 'accreditation@print'));

	Route::get('/accreditation/badge/(:any)/(:any)', array('before' => 'can_accreditation_badge', 'uses' => 'accreditation@badge'));
	Route::get('/accreditation/save-badge/(:any)/(:any)', 'accreditation@save_badge');
	Route::get('/accreditation/save-badge/(:any)/(:any)/(:any)', 'accreditation@save_badge');
	Route::get('/accreditation/badge/(:any)/(:any)/(:any)', array('before' => 'can_accreditation_badge', 'uses' => 'accreditation@badge'));
	Route::post('/accreditation/badge/(:any)/(:any)', array('before' => 'can_accreditation_badge', 'uses' => 'accreditation@post_badge'));
	Route::post('/accreditation/badge/(:any)/(:any)/(:any)', array('before' => 'can_accreditation_badge', 'uses' => 'accreditation@post_badge'));
	
	Route::get('/accreditation/(:any)/(:any)', 'accreditation@person');
	Route::get('/accreditation/(:any)/(:any)/(:any)', 'accreditation@child');

});

/* SMS */
Route::group(array('before' => 'auth|can_sms|event'), function()
{
	Route::get('/sms/(:any)/(:any)', 'sms@person');
	Route::post('/sms/(:any)/(:any)', 'sms@post_person');
	Route::get('/sms/(:any)/(:any)/(:any)', 'sms@person');
	Route::post('/sms/(:any)/(:any)/(:any)', 'sms@post_person');
});

/* Search */
Route::group(array('before' => 'auth|can_search|event'), function(){
	Route::post('/search', 'search@index');
	Route::get('/search/(:any)', 'search@index');
	Route::post('/search/(:any)', 'search@index');
});

/* Profiles */
Route::group(array('before' => 'auth|can_profiles|event'), function(){
	/* Profile */
	Route::get('/profile/(:any)/edit', array('before' => 'can_edit_profile', 'uses' => 'profiles@edit'));
	Route::post('/profile/(:any)/edit', array('before' => 'can_edit_profile', 'uses' => 'profiles@post_edit'));

	Route::get('/profile/(:any)/messages', array('before' => 'can_profiles', 'uses' => 'profiles@messages'));

	Route::get('/profile/(:any)/delete', array('before' => 'can_delete_profile', 'uses' => 'profiles@delete'));
	Route::post('/profile/(:any)/delete', array('before' => 'can_delete_profile', 'uses' => 'profiles@post_delete'));

	Route::get('/profile/(:any)/delete_comment/(:num)', array('before' => 'can_delete_profile_comment', 'uses' => 'profiles@delete_comment'));

	Route::get('/profiles', 'profiles@index');
	Route::get('/profile/(:any)', 'profiles@profile');
	Route::post('/profile/(:any)', 'profiles@post_comment');

	Route::get('/profile/(:any)/follow', function($profile_slug){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');

		$user = User::find(Auth::user()->id);
		$user->following()->where("type", "=", "profile")->where("belongs_to", "=", $profile->id)->delete();
		$follow = new Following;
		$follow->type = "profile";
		$follow->belongs_to = $profile->id;
		$user->following()->insert($follow);
		die("true");
	});

	Route::get('/profile/(:any)/not_follow', function($profile_slug){
		$profile = profile::find($profile_slug);
		if(!$profile->exists) return Event::first('404');

		$user = User::find(Auth::user()->id);
		$user->following()->where("type", "=", "profile")->where("belongs_to", "=", $profile->id)->delete();
		die("true");
	});

	Route::get('/profile/(:any)/map.jpg', 'profiles@profile_map');

	Route::get('/profile/add-person', array('before' => 'can_add_personell', 'uses' => 'profiles@add_person'));
	Route::post('/profile/add-person', array('before' => 'can_add_personell', 'uses' => 'profiles@post_add_person'));

	Route::get('/profile/(:any)/add-person', array('before' => 'can_add_personell', 'uses' => 'profiles@add_person_profile'));
	Route::post('/profile/(:any)/add-person', array('before' => 'can_add_personell', 'uses' => 'profiles@post_add_person'));

	Route::get('/profile/(:any)/(:any)/add-child', array('before' => 'can_add_personell', 'uses' => 'profiles@add_child'));
	Route::post('/profile/(:any)/(:any)/add-child', array('before' => 'can_add_personell', 'uses' => 'profiles@post_add_child'));
	
	Route::get('/profile/(:any)/(:any)/follow', 'profiles@person_follow');
	Route::get('/profile/(:any)/(:any)/(:any)/follow', 'profiles@person_follow');
	Route::get('/profile/(:any)/(:any)/not_follow', 'profiles@person_not_follow');
	Route::get('/profile/(:any)/(:any)/(:any)/not_follow', 'profiles@person_not_follow');	

	Route::get('/profile/(:any)/(:any)/make_contactperson', array('before' => 'can_edit_personell', 'uses' => 'profiles@make_contactperson'));
	Route::get('/profile/(:any)/(:any)/(:any)/make_contactperson', array('before' => 'can_edit_personell', 'uses' => 'profiles@make_contactperson'));

	Route::get('/person-edit/(:any)/(:any)', array('before' => 'can_edit_personell', 'uses' => 'profiles@person_edit'));
	Route::get('/person-edit/(:any)/(:any)/(:any)', array('before' => 'can_edit_personell', 'uses' => 'profiles@person_edit'));
	Route::post('/person-edit/(:any)/(:any)', array('before' => 'can_edit_personell', 'uses' => 'profiles@post_person_edit'));
	Route::post('/person-edit/(:any)/(:any)/(:any)', array('before' => 'can_edit_personell', 'uses' => 'profiles@post_person_edit'));

	Route::get('/delete-person/(:any)/(:any)', array('before' => 'can_delete_personell', 'uses' => 'profiles@person_delete'));
	Route::get('/delete-person/(:any)/(:any)/(:any)', array('before' => 'can_delete_personell', 'uses' => 'profiles@person_delete'));
	Route::post('/delete-person/(:any)/(:any)', array('before' => 'can_delete_personell', 'uses' => 'profiles@post_person_delete'));
	Route::post('/delete-person/(:any)/(:any)/(:any)', array('before' => 'can_delete_personell', 'uses' => 'profiles@post_person_delete'));


	Route::get('/profile/(:any)/(:any)', 'profiles@person');
	Route::get('/profile/(:any)/(:any)/(:any)', 'profiles@child');
	
	Route::get('/profile/add', array('before' => 'can_add_profile', 'uses' => 'profiles@add'));
	Route::post('/profile/add', array('before' => 'can_add_profile', 'uses' => 'profiles@post_add'));
});

Route::get('/not_done_yet', function(){
	return View::make('not_done_yet');
});

/* Logistics */
/* Started: 8. april 2014 kl. 11:11 */

Route::filter('logistics', function()
{
	$slug = URI::segment(3);
	if(empty($slug))
		return Redirect::to('/logistics');

	$location = Storage::findBySlug($slug);
	if(empty($location)){
		return Redirect::to('/logistics')->with('error', __('logistics.storage_not_found'));
	}

	Config::set('logistics.storage', $location);

	View::composer('all', function($view) use($location)
	{
	    $view->with('storage', $location);
	});

	tplConstructor::set(true);
});

Route::group(array('before' => 'auth|can_logistics|event'), function()
{
	Route::get('/logistics', function(){
		return View::make('logistics.select_storage');
	});

	Route::get('/logistics/add', 'logistics@add');
	Route::post('/logistics/add', 'logistics@post_add');
	Route::get('/logistics/owners/(:any).json', 'logistics@owners');
	Route::get('/logistics/owners.json', 'logistics@owners');

	Route::any('/logistics/search', 'logistics@search');
});

Route::group(array('before' => 'auth|can_logistics|event|logistics'), function()
{
	$baseURL = '/logistics/(:any)';

	Route::get($baseURL, 'logistics@view_storage');
	Route::get($baseURL . '/add_parcel', 'logistics@add_parcel');
	Route::get($baseURL . '/duplicates', function(){
		foreach (Parcel::duplicates()->get() as $parcel) {
			foreach($parcel->ids() as $dup){
				if(Parcellog::where("parcel_id", "=", $dup)->count() == 0){
					$par = Parcel::find($dup);
					if($par) $par->delete();
				}
			}
		}
	});

	Route::post($baseURL . '/add_parcel/single', 'logistics@post_parcel_single');
	Route::post($baseURL . '/add_parcel/bulk', 'logistics@post_parcel_bulk');
	Route::post($baseURL . '/add_parcel/bulkline', 'logistics@bulkline');
	Route::post($baseURL . '/edit_bulkline', 'logistics@edit_bulkline');

	Route::get($baseURL . '/(:num)', 'logistics@parcel');
	Route::get($baseURL . '/(:num)/action', 'logistics@parcel_action');
	Route::get($baseURL . '/(:num)/handout', 'logistics@handout');
	Route::post($baseURL . '/(:num)/handout', 'logistics@post_handout');
	Route::get($baseURL . '/(:num)/receive', 'logistics@receive');
	Route::get($baseURL . '/(:num)/print', 'logistics@parcel_print');
});


/* General */

Route::get('/change_event', function(){
	$user = Auth::user()->id;
	$user = User::find($user);
	$events = $user->events();
	return View::make('event.select')->with("events", $events);
});

Route::get('/verification/(:any)', function($salt){
	#if (!Auth::guest()) return Redirect::to('/');

	$user = User::where("salt", "=", $salt)->where("verified", "=", "0");
	if(!$user->count()) {
		return Redirect::to('login')->with('error', __('common.login_errors.usernotfound'));
	}

	$user = $user->first();
	$user->verified = "1";
	$user->save();


	return Redirect::to('login');
});

Route::get('/dataTableLang.txt', function(){
	$file = path('app')."language/".Config::get('application.language')."/datatable.php";
	$lines = require $file;

	return Response::json($lines);
});

Route::get('/login', function(){
	return View::make('common.login');
});

/* Partner Login */
Route::get('/partner/login', function(){
	
	$event = Config::get('application.event');
	if(!$event) return Redirect::to('/');
	#die(var_dump(partnerAuth::check()));
	if(partnerAuth::check()) return Redirect::to('/partner');

	// Find random image from mediabank.
	$file = Fil3::order_by(DB::raw('RAND()'))->where('type', '=', 'mediabank')->first();

	return View::make('common.partnerLogin')->with('file', $file)->with("event", $event);
});
Route::post('/partner/login', function(){

	$credentials = array('username' => Input::get('username'), 'password' => Input::get('password'));
	if (partnerAuth::attempt($credentials))
	{
	    return Redirect::to('/partner/');
	} else {
		return Redirect::to('/partner/login')->with('error', "Brukernavn og passord stemmer ikke.");
	}
});
Route::get('/partner/forgot', function(){
	$event = Config::get('application.event');
	if(!$event) return Redirect::to('/');

	return View::make('partner.forgot_password')->with("event", $event);
});
Route::post('/partner/forgot', function(){
	$phone = Input::get('phone');
	if(empty($phone))
		return Redirect::to('/partner/new')->with("error", 'Mangler telefonnummer');

	$event = Config::get('application.event');
	$person = $event->people()->where("password", "!=", "")->where("contact_person", "=", "1")->where("phone", "=", Input::get('phone'))->first();
	if(!$person)
		return Redirect::to('/partner/new')->with("error", 'Fant ikke brukeren. Dette kan være fordi du ikke er registrert som kontaktperson, eller ikke har vært å laget deg bruker under «Ny bruker».');
	
	$password = strtolower(Str::random(6, 'alpha'));

	$content = "Vi har opprettet et nytt passord til deg: ".$password;
	$from = "OBJEKT";
	$message = array( 'to' => '47'.$person->phone, 'message' => $content, 'from' => $from );
	$result = Clockwork::message($message);

	$sms = new SMS;
	$sms->event_id = $event->id;
	$sms->success = $result['success'];
	$sms->person_id = $person->id;
	$sms->to = $result['sms']['to'];
	$sms->from = $result['sms']['from'];
	$sms->message = $result['sms']['message'];
	$sms->message_id = $result['id'];

	$user = User::find(1);
	$user->sms()->insert($sms);

	$person->password = Hash::make($password);
	$person->_save();

	return Redirect::to('/partner/login');

});
Route::get('/partner/new', function(){
	
	$event = Config::get('application.event');
	if(!$event) return Redirect::to('/');

	return View::make('partner.new')->with("event", $event);
});
Route::post('/partner/new', function(){
	$phone = Input::get('phone');
	if(empty($phone))
		return Redirect::to('/partner/new')->with("error", 'Mangler telefonnummer');

	$event = Config::get('application.event');
	$person = $event->people()->where("password", "=", "")->where("contact_person", "=", "1")->where("phone", "=", Input::get('phone'))->first();
	if(!$person)
		return Redirect::to('/partner/new')->with("error", 'Fant ikke brukeren. Minner om at denne funksjonen kun er tilgjengelig for de som er registrert som kontaktperson for partneren, og kan kun gjennomføres én gang.');
	
	$password = strtolower(Str::random(6, 'alpha'));

	$content = "Vi har registrert en ny partnerbruker i systemet, og ditt passord er: ".$password;
	$from = "OBJEKT";
	$message = array( 'to' => '47'.$person->phone, 'message' => $content, 'from' => $from );
	$result = Clockwork::message($message);

	$sms = new SMS;
	$sms->event_id = $event->id;
	$sms->success = $result['success'];
	$sms->person_id = $person->id;
	$sms->to = $result['sms']['to'];
	$sms->from = $result['sms']['from'];
	$sms->message = $result['sms']['message'];
	$sms->message_id = $result['id'];

	$user = User::find(1);
	$user->sms()->insert($sms);

	$person->password = Hash::make($password);
	$person->_save();

	return Redirect::to('/partner/login');

});
Route::group(array('before' => 'partner_auth'), function()
{
	Route::get('/partner', function(){
		tplConstructor::set(true);
		return View::make('partner.dashboard');
	});
	
	Route::controller('partner.shop');
	Route::controller('partner.accreditation');

	Route::get('/partner/logout', function(){
		partnerAuth::logout();
		return Redirect::to('/partner/login');
	});
});

Route::any('/invite', function()
{
	$event = Config::get('application.event');
	if(!is_object($event)) return Redirect::to('/');
	if(!$event->special()->hasInvite()){
		return Redirect::to('/')->with("error", __('common.invite_non_existant'));
	}

	return $event->special()->invite();
});

Route::post('/login', function(){

	$credentials = array('username' => Input::get('username'), 'password' => Input::get('password'));
	try {
		if (Auth::attempt($credentials))
		{
			if(Input::get('referer')) return Redirect::to(Input::get('referer'));
		    return Redirect::to('/');
		}
	} catch (Exception $e) {
		$exception = strtolower(str_replace("Exception", "", get_class($e)));
		$message = Lang::line('common.login_errors.'.$exception)->get();
		return Redirect::to('login')->with('error', $message);
	}
});

View::composer('all', function($view)
{
	$event = Config::get('application.event');
    $view->with('current_event', $event);
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{

});

Route::filter('after', function($response)
{
	// Redirects have no content and errors handle their own layout.
    if (($response->status() == 200 or (isset($response->layout) and $response->layout === true)))
    {
    	$break = false;

    	switch(@$response->content->view){
    		case 'common.login':
    		case 'common.partnerLogin':
    		case 'partner.new':
    		case 'partner.forgot_password':
    		case '':
    			$break = true;
    		break;
    		default:
    			// Needs clean-up!
    			if(defined('EVENT_SPECIALITY')){ $break = true; }
    			if(defined('MODALVIEW')){ $break = true; }
    			elseif (defined('PARTNER'))
			    {
			        list($type) = explode(';', array_get($response->headers(), 'Content-Type', 'text/html'), 2);
			        switch ($type)
			        {
			            case 'text/html':
			            	if (Auth::check())
							{
								$notifications = array();

							} else $notifications = array();

			                $response->content = View::make('common.partner', array(
			                    'content' => $response->content
			                ))->with("title", Lang::line('views.'.$response->content->view)->get());
			            break;
			        }

			        $break = true;
			    }
    		break;
    	}


    	if(!$break){
	        list($type) = explode(';', array_get($response->headers(), 'Content-Type', 'text/html'), 2);
	        switch ($type)
	        {
	            case 'text/html':
	            	if (Auth::check())
					{
						$notifications = array();

					} else $notifications = array();

	                $response->content = View::make('common.default', array(
	                    'content' => $response->content
	                ))->with("title", Lang::line('views.'.$response->content->view)->get())->with("notifications", $notifications);
	            break;
	        }
	    }
    }
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('event', function()
{
	$event = Config::get('application.event');
	$user = Auth::user()->id;
	$user = User::find($user);
	$events = $user->events();

	if(!is_object($event) && $event == 0){
		$content = View::make('event.select')->with("events", $events);
		$content = View::make('common.clean', array('content' => $content));
		print($content->__toString());
		exit;
	} elseif($events->where("events.id", "=", $event->id)->count() < 1){
		$events = $user->events();
		$content = View::make('event.select')->with("events", $events);
		$content = View::make('common.clean', array('content' => $content));
		print($content->__toString());
		exit;
	}
});

Route::filter('partner_auth', function()
{
	if (partnerAuth::guest()) return Redirect::to('partner/login')->with("referer", URI::full());
	define('PARTNER', true);
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login')->with("referer", URI::full());
});

Route::filter('basic-auth', function()
{
	if (!Auth::basic()) return Redirect::json(array('status' => 'failed'), 401);
});

Route::filter('is_superadmin', function(){
	if (!Auth::user()->is('superAdmin'))
			return Redirect::to(Request::referrer())->with('error', __('common.access_denied'));
});

foreach(Permission::all() as $permission){
	Route::filter('can_'.$permission->name, function() use($permission)
	{
		if (Auth::guest()) return Redirect::to('login')->with("referer", URI::full());
		if (!Auth::user()->can($permission->name))
			return Redirect::to(Request::referrer())->with('error', sprintf(__('common.access_denied'), __('user.permission.'.$permission->name)));
	});
}