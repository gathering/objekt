<?php

class Create_Categories_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		#$this->down(); 
		Schema::table('categories', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->integer('event_id');
			$table->string('name');
			$table->timestamps();

			$table->foreign('event_id')->references('id')->on('events');
		});

		Schema::table('products', function($table)
		{
			$table->integer('category_id');
			#$table->foreign('category_id')->references('id')->on('categories');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');

		Schema::table('products', function($table)
		{
			$table->drop_column('category_id');
		});
	}

}