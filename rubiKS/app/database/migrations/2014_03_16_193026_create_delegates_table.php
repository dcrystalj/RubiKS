<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDelegatesTable extends Migration {

	public function up()
	{
		Schema::create('delegates', function(Blueprint $table) {
			$table->increments('delegate_id');
			$table->integer('user_id')->unsigned();
			$table->string('degree');
			$table->string('contact');
			$table->string('region');
			$table->string('activity', 1);
		});
	}

	public function down()
	{
		Schema::drop('delegates');
	}
}