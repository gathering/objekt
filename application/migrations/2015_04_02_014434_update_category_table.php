<?php

class Update_Category_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function($table)
		{
			$table->drop_column('sortSize');
		});
		Schema::table('categories', function($table)
		{
			$table->integer('sortSize');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function($table)
		{
			$table->integer('sortSize');
		});
		Schema::table('categories', function($table)
		{
			$table->drop_column('sortSize');
		});
	}

}