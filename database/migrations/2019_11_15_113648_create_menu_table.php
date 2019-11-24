<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order')->nullable();
			$table->integer('hal_id')->nullable();
			$table->integer('parent_id')->nullable();
			$table->string('type', 190)->nullable();
			$table->string('url', 190)->nullable();
			$table->boolean('custom_url')->default(0);
			$table->string('name', 190)->nullable();
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
		Schema::drop('menu');
	}

}
