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
			$table->string('name', 20);
			$table->string('attempts', 40);
			$table->string('type');
			$table->integer('time_limit');
			$table->text('description');
			$table->text('help');
		});
	}

	public function down()
	{
		Schema::drop('events');
	}
}