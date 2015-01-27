<?php

class Create_Product_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('productTypes', function($table)
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
			$table->create();
			$table->increments('id');
			$table->integer('event_id');
			$table->integer('producttype_id');
			$table->string('name');
			$table->text('description');
			$table->integer('stock');
			$table->integer('price');
			$table->timestamps();
			$table->foreign('event_id')->references('id')->on('events');
			$table->foreign('producttype_id')->references('id')->on('productTypes');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
		Schema::drop('productTypes');
	}

}