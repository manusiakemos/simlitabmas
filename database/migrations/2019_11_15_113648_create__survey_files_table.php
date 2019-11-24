<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('_survey_files', function(Blueprint $table)
		{
			$table->increments('file_id');
			$table->integer('survey_id')->nullable();
			$table->string('file_name', 190)->nullable();
			$table->string('file_title', 190)->nullable();
			$table->string('file_ext', 190)->nullable();
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
		Schema::drop('_survey_files');
	}

}
