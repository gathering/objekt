<?php

return array(

	// The db column to authenticate against
	'username'				=> array('email', 'username'),

	// The User mode to use
	'user_model'			=> 'User',

	// The Super Admin role
	// (returns true for all permissions)
	'super_admin'			=> 'superAdmin',

	// DB prefix for tables
	// NO '_' NECESSARY, e.g. use 'verify' for 'verify_users'
	'prefix'				=> ''

);