<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultsSimpleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('results_simple', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('competition_short_name');
			$table->string('event_readable_id');
			$table->string('round_short_name');
			$table->string('user_club_id');
			$table->integer('single');
			$table->integer('average');
			$table->string('results');
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
		Schema::drop('results_simple');
	}

}
