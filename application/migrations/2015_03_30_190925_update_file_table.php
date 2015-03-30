<?php

class Update_File_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('files', function($table)
		{
			$table->integer('user_id');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('files', function($table)
		{
			$table->drop_column('user_id');
		});
	}

}