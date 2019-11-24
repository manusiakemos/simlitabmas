<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSlideTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('slide', function(Blueprint $table)
		{
			$table->increments('slide_id');
			$table->string('slide_nama', 191)->default('');
			$table->text('slide_gambar');
			$table->text('slide_isi')->nullable();
			$table->boolean('slide_aktif');
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
		Schema::drop('slide');
	}

}
