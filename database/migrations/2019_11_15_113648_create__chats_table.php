<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('_chats', function(Blueprint $table)
		{
			$table->increments('chat_id');
			$table->integer('user_id')->nullable();
			$table->integer('survey_id')->nullable();
			$table->text('chat', 65535)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('_chats');
	}

}
