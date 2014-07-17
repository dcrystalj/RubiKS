<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNationalChampionshipStatsEventTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('national_championship_stats_event', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('year');
			$table->integer('event_id');
			$table->integer('user_id')->unsigned();
			$table->integer('rank');
			$table->string('score');
			$table->string('details');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('national_championship_stats_event');
	}

}
