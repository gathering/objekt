<?php

class Update_Permissions {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		$permission = new Permission;
		$permission->name = "api";
		$permission->description = "Access to API.";
		$permission->save();
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		$permission = Permission::where('name', '=', 'api')->delete();
	}

}