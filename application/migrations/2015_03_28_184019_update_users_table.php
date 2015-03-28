<?php

class Update_Users_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::query('ALTER TABLE `users` MODIFY `phone` VARCHAR(50) NOT NULL');
		DB::query('ALTER TABLE `people` MODIFY `phone` VARCHAR(50) NOT NULL');
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}