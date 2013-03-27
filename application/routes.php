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
});
/* Accreditation */
Route::group(array('before' => 'auth|superadmin|event'), function()
{
	Route::get('/accreditation', 'accreditation@index');
	Route::post('/accreditation', 'search@search_accreditation');

	Route::get('/accreditation/wristband/(:any)/(:any)', 'accreditation@wristband');
	Route::get('/accreditation/wristband/(:any)/(:any)/(:any)', 'accreditation@wristband');

	Route::get('/accreditation/departed/(:any)/(:any)', 'accreditation@departed');
	Route::get('/accreditation/departed/(:any)/(:any)/(:any)', 'accreditation@departed');

	Route::get('/accreditation/badge/(:any)/(:any)', 'accreditation@badge');
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
	Route::get('/sponsors', 'sponsors@index');
	Route::get('/sponsor/(:any)', 'sponsors@profile');
	Route::get('/sponsor/add-person', 'sponsors@add_person');
	Route::post('/sponsor/add-person', 'sponsors@post_add_person');
	Route::get('/sponsor/(:any)/add-person', 'sponsors@add_person_sponsor');
	Route::post('/sponsor/(:any)/add-person', 'sponsors@post_add_person');
	Route::get('/sponsor/(:any)/(:any)/add-child', 'sponsors@add_child');
	Route::post('/sponsor/(:any)/(:any)/add-child', 'sponsors@post_add_child');
	Route::get('/sponsor/(:any)/(:any)', 'sponsors@person');
	Route::get('/sponsor/(:any)/(:any)/(:any)', 'sponsors@child');
	Route::get('/sponsor/add', 'sponsors@add');
	Route::post('/sponsor/add', 'sponsors@post_add');
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
		$content = "<h1>Velg arrangement</h1>";
		foreach (Events::where("status", "=", "activated")->get() as $event)
		{
			$content .= "<a href='/".$event->slug."'>".$event->name."</a>";
		}

		$content = View::make('common.clean', array('content' => $content));
		print($content);
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