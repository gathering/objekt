<?php

class Update_Sms_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::query("ALTER TABLE `sms` ADD `type` ENUM('outbound', 'inbound') NOT NULL");
		Schema::table('sms', function($table)
		{
			$table->timestamp('received_at');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sms', function($table)
		{
			$table->drop_column('type');
			$table->drop_column('received_at');
		});
	}

}