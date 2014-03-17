<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompetitionsTable extends Migration {

	public function up()
	{
		Schema::create('competitions', function(Blueprint $table) {
			$table->increments('id');
			$table->string('short_name', 25)->unique();
			$table->string('name', 50);
			$table->date('date');
			$table->string('time');
			$table->string('max_competitors');
			$table->string('events', 250);
			$table->string('city');
			$table->string('venue');
			$table->string('organiser');
			$table->integer('delegate1')->unsigned()->nullable();
			$table->integer('delegate2')->unsigned()->nullable();
			$table->integer('delegate3')->unsigned()->nullable();
			$table->string('algorithms_url');
			$table->string('description');
			$table->string('registration_fee');
			$table->string('country');
			$table->integer('status');
		});
	}

	public function down()
	{
		Schema::drop('competitions');
	}
}