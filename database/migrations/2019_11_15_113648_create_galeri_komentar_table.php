<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGaleriKomentarTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('galeri_komentar', function(Blueprint $table)
		{
			$table->increments('gk_id');
			$table->integer('user_id')->nullable();
			$table->integer('gal_id')->nullable();
			$table->text('gk_text', 65535)->nullable();
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
		Schema::drop('galeri_komentar');
	}

}
