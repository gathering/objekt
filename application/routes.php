<?php


Route::group(array('before' => 'auth|event'), function()
{
	Route::get('/', function()
	{
		return View::make('home.index');
	});

	Route::get('/logout', function(){
		Auth::logout();
		return Redirect::to('/');
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

	Route::get('/accreditation/badge/(:any)/(:any)', 'accreditation@badge');
	Route::get('/accreditation/save-badge/(:any)/(:any)', 'accreditation@save_badge');
	Route::get('/accreditation/save-badge/(:any)/(:any)/(:any)', 'accreditation@save_badge');
	Route::get('/accreditation/badge/(:any)/(:any)/(:any)', 'accreditation@badge');
	Route::post('/accreditation/badge/(:any)/(:any)', 'accreditation@post_badge');
	Route::post('/accreditation/badge/(:any)/(:any)/(:any)', 'accreditation@post_badge');
	
	Route::get('/accreditation/(:any)/(:any)', 'accreditation@person');
	Route::get('/accreditation/(:any)/(:any)/(:any)', 'accreditation@child');
});
/* Search */
Route::group(array('before' => 'auth|superadmin|event'), function(){
	Route::post('/search', 'search@index');
	Route::get('/search/(:any)', 'search@index');
	Route::post('/search/(:any)', 'search@index');
});
/* Sponsors */
Route::group(array('before' => 'auth|superadmin|event'), function(){
	Route::get('/profiles', 'profiles@index');
	Route::get('/profile/(:any)', 'profiles@profile');
	Route::get('/profile/add-person', 'profiles@add_person');
	Route::post('/profile/add-person', 'profiles@post_add_person');
	Route::get('/profile/(:any)/add-person', 'profiles@add_person_profile');
	Route::post('/profile/(:any)/add-person', 'profiles@post_add_person');
	Route::get('/profile/(:any)/(:any)/add-child', 'profiles@add_child');
	Route::post('/profile/(:any)/(:any)/add-child', 'profiles@post_add_child');
	Route::get('/profile/(:any)/(:any)', 'profiles@person');
	Route::get('/profile/(:any)/(:any)/(:any)', 'profiles@child');
	Route::get('/profile/add', 'profiles@add');
	Route::post('/profile/add', 'profiles@post_add');
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
	if (Auth::guest()) return Redirect::to('login');
});

Route::filter('superadmin', function()
{
	if (!Auth::user()->is('superSponsorAdmin')) return Redirect::to(Request::referrer())->with('error', __('common.access_denied'));
});