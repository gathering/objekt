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
		    'pushover_key' => 'required',
		   	);


		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$user->pushover_key = $input['pushover_key'];
		$user->pushover_status = isset($input['pushover_status']) ? "activate" : "deactivate";
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

	public function action_edit($id)
	{
		$user = User::find($id);
		if($user == NULL)
			return Redirect::to('users/roles')->with('error', __('user.not_found'));

		$event = Config::get('application.event');
		$role = $user->roles()->where("event_id", "=", $event->id)->first();

		if($role == NULL && !Auth::user()->is('superAdmin'))
			return Redirect::to('users/roles')->with('error', __('user.role_not_found'));

		return View::make('user.edit_user')->with("user", $user);
	}

	public function action_post_edit($user_id)
	{		

		$user = Auth::retrieve($user_id);
		if($user == NULL)
			return Redirect::to('users/roles')->with('error', __('user.not_found'));

		$event = Config::get('application.event');
		$role = $user->roles()->where("event_id", "=", $event->id)->first();

		if($role == NULL && !Auth::user()->is('superAdmin'))
			return Redirect::to('users/roles')->with('error', __('user.role_not_found'));
		
		$input = Input::all();
		$rules = array(
		    'username'  => 'required|max:50|unique:users,username,'.$user->id,
		    'password' => 'same:password2',
		    'email' => 'required|email|unique:users,email,'.$user->id
		);

		if(!$user->is('superAdmin')){
			$rules['role'] = 'required|exists:roles,id';
		}

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}

		$username = Input::get('username');
		$password = Input::get('password');
		$password2 = Input::get('password2');
		$email = Input::get('email');

		$user->username = $username;
		$user->name = Input::get('name');
		if(!empty($password))
			$user->password = $password; // This is automatically salted and encrypted

		if($user->email != $email){
			$contents = View::make("user.verification")->with("user", $user)->with("password", $password);
			$response = Mandr::messages()->send(array(
	            'html' => $contents->render(),
	            'subject' => Lang::line('user.verification_subject')->get(),
	            'from_email' => Lang::line('user.noreply')->get(),
			    'from_name' => Lang::line('user.noreply_name')->get(),
	            'to' => array(array('email'=>$user->email))
	        ), false);
			$user->email = $email;
		}

		unset($user->to_check_cache);
		$user->save();

		// TODO: Make sure old event-role stick.
		if(!$user->is('superAdmin'))
			$user->roles()->sync(array(Input::get('role')));

		return Redirect::to('users')->with('success', __('user.user_edited'));
	}

	public function action_reset_password($user_id)
	{
		return View::make('user.reset-password');
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
		$response = Mandr::messages()->send(array(
	            'html' => $contents->render(),
	            'subject' => Lang::line('user.verification_subject')->get(),
	            'from_email' => Lang::line('user.noreply')->get(),
			    'from_name' => Lang::line('user.noreply_name')->get(),
	            'to' => array(array('email'=>$user->email))
	        ), false);

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
		$email = Input::get('email');

		$user = new \Verify\Models\User;
		$user->username = $username;
		$user->email = $email;
		$user->name = Input::get('name');
		$user->password = $password; // This is automatically salted and encrypted
		$user->save();

		$user->roles()->sync(array(Input::get('role')));

		$contents = View::make("user.verification")->with("user", $user)->with("password", $password);
		$response = Mandr::messages()->send(array(
	            'html' => $contents->render(),
	            'subject' => Lang::line('user.verification_subject')->get(),
	            'from_email' => Lang::line('user.noreply')->get(),
			    'from_name' => Lang::line('user.noreply_name')->get(),
	            'to' => array(array('email'=>$user->email))
	        ), false);

		/*var_dump($response);
		$user->delete(); die();*/

		return Redirect::to('users')->with('success', __('user.user_added'));
	}

	public function action_roles(){
		return View::make('user.roles');
	}

	public function action_edit_role($role_id){
		$event = Config::get('application.event');
		$role = Role::where("id", "=", $role_id)->where("event_id", "=", $event->id)->first();
		if($role == NULL)
			return Redirect::to('users/roles')->with('error', __('user.role_not_found'));

		return View::make('user.edit_role')->with("role", $role);
	}

	public function action_post_edit_role($role_id){
		$event = Config::get('application.event');
		$role = Verify\Models\Role::where("id", "=", $role_id)->where("event_id", "=", $event->id)->first();
		if($role == NULL)
			return Redirect::to('users/roles')->with('error', __('user.role_not_found'));

		$input = Input::all();
		if(!empty($input['permission'])){
			$input['permission'] = array_keys($input['permission']);
			$role->permissions()->sync($input['permission']);
		}

		if(isset($input['name'])){
			$role->name = $input['name'];
			$role->save();
		}

		return Redirect::to('users/roles')->with('success', __('user.role_saved'));
	}

	public function action_add_role(){
		return View::make('user.add_role');
	}

	public function action_post_add_role(){
		$input = Input::all();
		$rules = array(
		    'name'  => 'required',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
		    return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
		}
		$role = new Role;
		$role->name = $input['name'];
		$event = Config::get('application.event');
		$role = $event->roles()->insert($role);

		if(!empty($input['permission'])){
			$input['permission'] = array_keys($input['permission']);
			$role->permissions()->sync($input['permission']);
		}

		return Redirect::to('users/roles')->with('success', __('user.role_saved'));
	}

	public function action_delete_role($role_id){
		$event = Config::get('application.event');
		$role = Verify\Models\Role::where("id", "=", $role_id)->where("event_id", "=", $event->id)->first();
		if($role == NULL)
			return Redirect::to('users/roles')->with('error', __('user.role_not_found'));

		if($role->users()->count() > 0)
			return Redirect::to('users/roles')->with('error', __('user.role_has_users'));

		if($role->static == '1')
			return Redirect::to('users/roles')->with('error', __('user.role_is_static'));

		$role->permissions()->sync(array());
		$role->delete();

		return Redirect::to('users/roles')->with('success', __('user.role_deleted'));
	}

}