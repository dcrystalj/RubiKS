<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	public function up()
	{
		Schema::create('events', function(Blueprint $table) {
			$table->increments('id');
			$table->string('readable_id', 10);
			$table->string('short_name', 10);
			$table->string('name', 30);
			$table->string('attempts', 20);
			$table->string('type', 50);
			$table->string('show_average', '1');
			$table->integer('time_limit');
			$table->text('description');
			$table->string('help', 50);
		});
	}

	public function down()
	{
		Schema::drop('events');
	}
}