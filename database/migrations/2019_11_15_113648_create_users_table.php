<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name', 191)->nullable()->default('');
			$table->string('email', 191)->nullable()->unique();
			$table->string('username', 190)->unique();
			$table->string('api_token', 191)->unique();
			$table->string('avatar', 100)->nullable();
			$table->string('phone', 12)->nullable();
			$table->enum('role', array('super-admin','admin','user'))->default('admin');
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password', 191);
			$table->string('remember_token', 100)->nullable();
			$table->softDeletes();
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
		Schema::drop('users');
	}

}
