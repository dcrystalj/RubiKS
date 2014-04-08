<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegistrationsTable extends Migration {

	public function up()
	{
		Schema::create('registrations', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('competition_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('events');
			$table->string('confirmed', 1)->default(0);
			$table->text('notes');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('registrations');
	}
}