<?php

class Update_Entries_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entries', function($table)
		{
			$table->string('ident', 255);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('entries', function($table)
		{
			$table->drop_column('ident');
		});
	}

}