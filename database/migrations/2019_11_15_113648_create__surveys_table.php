<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('_surveys', function(Blueprint $table)
		{
			$table->increments('survey_id');
			$table->integer('ss_id');
			$table->integer('user_id');
			$table->string('survey_nama_lokasi', 190)->nullable();
			$table->string('survey_nama_kegiatan', 190)->nullable();
			$table->date('survey_tanggal')->nullable();
			$table->boolean('checked_helper')->nullable()->default(0);
			$table->boolean('checked_helper2')->nullable()->default(0);
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
		Schema::drop('_surveys');
	}

}
