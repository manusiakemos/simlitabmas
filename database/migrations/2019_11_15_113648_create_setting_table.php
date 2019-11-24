<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting', function(Blueprint $table)
		{
			$table->increments('setting_id');
			$table->string('setting_input', 190)->nullable();
			$table->string('setting_group', 190)->nullable();
			$table->string('setting_icon', 190)->nullable();
			$table->string('setting_name', 190)->nullable();
			$table->boolean('is_public')->nullable();
			$table->text('setting_value', 65535)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('setting');
	}

}
