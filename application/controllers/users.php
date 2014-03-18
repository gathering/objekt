<?php

class Users_Controller extends Base_Controller {

	public function action_pushover()
	{
		return View::make('user.pushover');
	}

	public function action_post_pushover()
	{
		$user = Auth::user();
		$user = User::find($user->id);

		$input = Input::all();
		$rules = array(
		    'pushover_key' => 'required'
		   	);


		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$user->pushover_key = $input['pushover_key'];
		$user->save();

		return Redirect::to(Request::referrer())->with('success', __('user.pushover_success'));
	}

	public function action_index()
	{
		return View::make('user.index');
	}

	public function action_delete_user($user_id)
	{
		$user = Auth::retrieve($user_id);
		if(!$user) return Redirect::to(Request::referrer())->with('error', 'User not found.');

		return View::make('user.delete_user')->with("user", $user);
	}

	public function action_post_delete_user($user_id)
	{
		$user = Auth::retrieve($user_id);
		if(!$user) return Redirect::to(Request::referrer())->with('error', 'User not found.');

		$user->disabled = 1;
		$user->deleted = 1;
		$user->save();

		return Redirect::to('/users')->with("success", __('user.user_deleted'));
	}

	public function action_add()
	{
		return View::make('user.add');
	}

	public function action_reset_password($user_id)
	{
		return View::make('user.reset-password');
	}

	public function action_activate_user($user_id)
	{
		
	}

	public function action_post_reset_password($user_id)
	{
		$input = Input::all();
		$rules = array(
		    'password' => 'required|same:password2'
		   	);


		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}
		$user = Auth::retrieve($user_id);
		if(!$user) return Redirect::to(Request::referrer())->with('error', 'User not found.');
		
		$password = Input::get('password');

		$user->password = $password;

		$contents = View::make("user.verification")->with("user", $user)->with("password", $password);
		$response = Mandrill::request('/messages/send', array(
		    'message' => array(
		        'html' => $contents->render(),
		        'subject' => Lang::line('user.verification_subject')->get(),
		        'from_email' => Lang::line('user.noreply')->get(),
		        'from_name' => Lang::line('user.noreply_name')->get(),
		        'to' => array(array('email'=>$user->email)),
		    ),
		));

		$user->save();
		return Redirect::to('users')->with('success', __('user.user_added'));
	}

	public function action_post_add()
	{		
		$input = Input::all();

		$rules = array(
		    'username'  => 'required|max:50|unique:users',
		    'password' => 'required|same:password2',
		    'role' => 'required|exists:roles,id',
		    'email' => 'required|email|unique:users'
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$username = Input::get('username');
		$password = Input::get('password');
		$password2 = Input::get('password2');
		$role = Role::find(Input::get('role'));
		$email = Input::get('email');

		$user = new \Verify\Models\User;
		$user->username = $username;
		$user->email = $email;
		$user->password = $password; // This is automatically salted and encrypted
		$user->role_id = $role->id;
		$user->save();

		$contents = View::make("user.verification")->with("user", $user)->with("password", $password);
		$response = Mandrill::request('/messages/send', array(
		    'message' => array(
		        'html' => $contents->render(),
		        'subject' => Lang::line('user.verification_subject')->get(),
		        'from_email' => Lang::line('user.noreply')->get(),
		        'from_name' => Lang::line('user.noreply_name')->get(),
		        'to' => array(array('email'=>$user->email)),
		    ),
		));

		/*var_dump($response);
		$user->delete(); die();*/

		return Redirect::to('users')->with('success', __('user.user_added'));
	}

}