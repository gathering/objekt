<?php

class Create_Discount_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{

		DB::query('ALTER TABLE profiles ENGINE=InnoDB;');

		Schema::table('discount_types', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->string('name');
			$table->timestamps();
		});

		Schema::table('discount_value_types', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->string('name');
			$table->string('symbol');
			$table->timestamps();
		});

		Schema::table('discounts', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->boolean('active');

			$table->integer('value');
			$table->integer('value_type_id')->unsigned(); // Percent or direct price.
			$table->integer('type_id')->unsigned(); // product or category?
			$table->integer('object_id');
			$table->integer('profile_id');

			$table->timestamps();

			$table->foreign('value_type_id')->references('id')->on('discount_value_types');
			$table->foreign('type_id')->references('id')->on('discount_types');
			$table->foreign('profile_id')->references('id')->on('profiles');
		});

		// Set up types
		DB::table('discount_types')->insert(
			[
				['name' => 'Category'],
				['name' => 'Product'],
				['name' => 'Profile']
			]);

		// Set up value types
		DB::table('discount_value_types')->insert(
			[
				['name' => 'percent', 'symbol' => '%'],
				['name' => 'price', 'symbol' => ',-']
			]);
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('discounts');
		Schema::drop('discount_value_types');
		Schema::drop('discount_types');
	}

}