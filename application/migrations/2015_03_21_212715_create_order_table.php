<?php

class Create_Order_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->integer('event_id');
			$table->integer('person_id');
			$table->integer('profile_id');
			$table->blob('order_details');
			$table->text('note');
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}