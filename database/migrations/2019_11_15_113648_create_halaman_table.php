<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHalamanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('halaman', function(Blueprint $table)
		{
			$table->increments('hal_id');
			$table->string('hal_url', 191)->default('');
			$table->string('hal_judul', 191)->default('');
			$table->text('hal_isi', 65535)->nullable();
			$table->boolean('hal_aktif')->default(0);
			$table->boolean('hal_custom')->default(0);
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
		Schema::drop('halaman');
	}

}
