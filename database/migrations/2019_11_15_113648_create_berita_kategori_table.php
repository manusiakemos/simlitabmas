<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBeritaKategoriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('berita_kategori', function(Blueprint $table)
		{
			$table->bigInteger('bk_id', true)->unsigned();
			$table->string('bk_nama', 191)->default('');
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
		Schema::drop('berita_kategori');
	}

}
