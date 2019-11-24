<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBeritaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('berita', function(Blueprint $table)
		{
			$table->increments('berita_id');
			$table->integer('user_id');
			$table->integer('bk_id');
			$table->boolean('berita_aktif');
			$table->string('berita_judul', 191)->default('');
			$table->boolean('custom_url')->nullable();
			$table->string('berita_url', 191)->default('');
			$table->string('berita_gambar', 191)->default('');
			$table->text('berita_isi', 65535)->nullable();
			$table->integer('berita_hit');
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
		Schema::drop('berita');
	}

}
