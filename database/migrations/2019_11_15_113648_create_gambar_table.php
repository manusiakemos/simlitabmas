<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGambarTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gambar', function(Blueprint $table)
		{
			$table->increments('gb_id');
			$table->string('gb_nama', 191)->default('');
			$table->string('gb_folder', 191)->default('');
			$table->string('gb_tipe', 191)->default('');
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
		Schema::drop('gambar');
	}

}
