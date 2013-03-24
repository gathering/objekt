<?php

class Users_Controller extends Base_Controller {

	public function action_index()
	{
		return View::make('user.index');
	}

	public function action_add()
	{
		return View::make('user.add');
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

		$contents = View::make("user.verification")->with("user", $user);
		$response = Mandrill::request('/messages/send', array(
		    'message' => array(
		        'html' => $contents->render(),
		        'subject' => Lang::line('user.verification_subject')->get(),
		        'from_email' => Lang::line('user.noreply')->get(),
		        'to' => array(array('email'=>$user->email)),
		    ),
		));

		/*var_dump($response);
		$user->delete(); die();*/

		return Redirect::to('users')->with('success', __('user.user_added'));
	}

}