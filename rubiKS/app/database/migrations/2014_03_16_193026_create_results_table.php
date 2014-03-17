<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultsTable extends Migration {

	public function up()
	{
		Schema::create('results', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('competition_id')->unsigned();
			$table->integer('event_id')->unsigned();
			$table->string('round', 10);
			$table->integer('user_id')->unsigned();
			$table->integer('single');
			$table->integer('average');
			$table->string('results');
			$table->string('single_nr', 1);
			$table->string('single_pb', 1);
			$table->string('average_nr', 1);
			$table->string('average_pb', 1);
			$table->string('medal', 1);
			$table->date('date');
			$table->integer('championship_rank');
		});
	}

	public function down()
	{
		Schema::drop('results');
	}
}