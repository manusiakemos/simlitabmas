<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGaleriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('galeri', function(Blueprint $table)
		{
			$table->increments('gal_id');
			$table->string('gal_judul', 190)->nullable();
			$table->text('gal_nama_file', 65535);
			$table->text('gal_deskripsi', 65535)->nullable();
			$table->string('gal_slug', 190)->nullable();
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
		Schema::drop('galeri');
	}

}
