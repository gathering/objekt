<?php


Route::group(array('before' => 'auth|event'), function()
{
	Route::get('/', function()
	{
		return View::make('home.index');
	});

	Route::get('/test', function()
	{
		Notification::send(69, "Hei!", "Hvordan går det?");
	});

	Route::get('/pushover', 'users@pushover');
	Route::post('/pushover', 'users@post_pushover');

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
Route::group(array('before' => 'auth|superadmin|event'), function()
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
});

/* Mediabank */
Route::group(array('before' => 'auth|superadmin|event'), function()
{
	Route::get('/mediabank', 'mediabank@index');
	Route::post('/mediabank', 'mediabank@upload');
});

/* Notifications */
Route::group(array('before' => 'auth|superadmin|event'), function()
{
	Route::get('/notifications', function(){
		$user = User::find(Auth::user()->id);
		return View::make('user.notifications')->with("notifications", $user->notifications()->order_by("created_at", "desc")->get());
	});
	Route::get('/notifications/readall', function(){
		$user = User::find(Auth::user()->id);
		$notifications = $user->notifications()->update(array("status" => "read"));
		die("Done!");
	});
});

/* Users */
Route::group(array('before' => 'auth|superadmin|event'), function()
{
	Route::get('/users', 'users@index');
	Route::get('/users/add', 'users@add');
	Route::post('/users/add', 'users@post_add');

	Route::get('/user/(:num)/reset-password', 'users@reset_password');
	Route::post('/user/(:num)/reset-password', 'users@post_reset_password');

	Route::get('/user/(:num)/delete-user', 'users@delete_user');
	Route::post('/user/(:num)/delete-user', 'users@post_delete_user');
});


/* Accreditation */
Route::get('/accreditation/controll/(:any)/(:any)', array('uses' => 'accreditation@controll', 'before' => 'event'));
Route::group(array('before' => 'auth|superadmin|event'), function()
{
	Route::get('/accreditation', 'accreditation@index');
	Route::post('/accreditation', 'search@search_accreditation');

	Route::get('/accreditation/wristband/(:any)/(:any)', 'accreditation@wristband');
	Route::get('/accreditation/wristband/(:any)/(:any)/(:any)', 'accreditation@wristband');

	Route::get('/accreditation/departed/(:any)/(:any)', 'accreditation@departed');
	Route::get('/accreditation/departed/(:any)/(:any)/(:any)', 'accreditation@departed');

	Route::get('/accreditation/print/(:any)/(:any)', 'accreditation@print');
	Route::get('/accreditation/print/(:any)/(:any)/(:any)', 'accreditation@print');

	Route::get('/accreditation/badge/(:any)/(:any)', 'accreditation@badge');
	Route::get('/accreditation/save-badge/(:any)/(:any)', 'accreditation@save_badge');
	Route::get('/accreditation/save-badge/(:any)/(:any)/(:any)', 'accreditation@save_badge');
	Route::get('/accreditation/badge/(:any)/(:any)/(:any)', 'accreditation@badge');
	Route::post('/accreditation/badge/(:any)/(:any)', 'accreditation@post_badge');
	Route::post('/accreditation/badge/(:any)/(:any)/(:any)', 'accreditation@post_badge');
	
	Route::get('/accreditation/(:any)/(:any)', 'accreditation@person');
	Route::get('/accreditation/(:any)/(:any)/(:any)', 'accreditation@child');

});

/* SMS */
Route::group(array('before' => 'auth|superadmin|event'), function()
{
	Route::get('/sms/(:any)/(:any)', 'sms@person');
	Route::post('/sms/(:any)/(:any)', 'sms@post_person');
	Route::get('/sms/(:any)/(:any)/(:any)', 'sms@person');
	Route::post('/sms/(:any)/(:any)/(:any)', 'sms@post_person');
});

/* Search */
Route::group(array('before' => 'auth|superadmin|event'), function(){
	Route::post('/search', 'search@index');
	Route::get('/search/(:any)', 'search@index');
	Route::post('/search/(:any)', 'search@index');
});

/* Profiles */
Route::group(array('before' => 'auth|superadmin|event'), function(){
	/* Profile */
	Route::get('/profile/(:any)/edit', 'profiles@edit');
	Route::post('/profile/(:any)/edit', 'profiles@post_edit');

	Route::get('/profile/(:any)/delete', 'profiles@delete');
	Route::post('/profile/(:any)/delete', 'profiles@post_delete');

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

	Route::get('/profile/add-person', 'profiles@add_person');
	Route::post('/profile/add-person', 'profiles@post_add_person');

	Route::get('/profile/(:any)/add-person', 'profiles@add_person_profile');
	Route::post('/profile/(:any)/add-person', 'profiles@post_add_person');

	Route::get('/profile/(:any)/(:any)/add-child', 'profiles@add_child');
	Route::post('/profile/(:any)/(:any)/add-child', 'profiles@post_add_child');
	
	Route::get('/profile/(:any)/(:any)/follow', 'profiles@person_follow');
	Route::get('/profile/(:any)/(:any)/(:any)/follow', 'profiles@person_follow');
	Route::get('/profile/(:any)/(:any)/not_follow', 'profiles@person_not_follow');
	Route::get('/profile/(:any)/(:any)/(:any)/not_follow', 'profiles@person_not_follow');	

	Route::get('/profile/(:any)/(:any)/make_contactperson', 'profiles@make_contactperson');
	Route::get('/profile/(:any)/(:any)/(:any)/make_contactperson', 'profiles@make_contactperson');

	Route::get('/profile/(:any)/(:any)', 'profiles@person');
	Route::get('/profile/(:any)/(:any)/(:any)', 'profiles@child');
	
	Route::get('/profile/add', 'profiles@add');
	Route::post('/profile/add', 'profiles@post_add');
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
    if (($response->status() == 200 or (isset($response->layout) and $response->layout === true))
    	&& (@$response->content->view != "common.login"
    	&& @$response->content->view != ""))
    {
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
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('event', function()
{
	$event = Config::get('application.event');
	if(!is_object($event) && $event == 0){
		$user = Auth::user()->id;
		$user = User::find($user);
		$events = $user->events();
		$content = View::make('event.select')->with("events", $events);
		$content = View::make('common.clean', array('content' => $content));
		print($content->__toString());
		exit;
	}
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login')->with("referer", URI::full());
});

Route::filter('superadmin', function()
{
	if (!Auth::user()->is('superSponsorAdmin')) return Redirect::to(Request::referrer())->with('error', __('common.access_denied'));
});