<?php

class Create_Messages {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('messages', function($table)
		{
			$table->create();
			$table->increments('id');
			$table->string('thread');
			$table->integer('event_id');
			$table->integer('person_id');
			$table->integer('profile_id');
			$table->integer('user_id');
			$table->string('from_email');
			$table->string('to_email');
			$table->string('subject');
			$table->text('tags');
			$table->text('raw_message');
			$table->text('html');
			$table->text('text');
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
		Schema::drop('messages');
	}

}