<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreditsTable extends Migration {

	public function up()
	{
		Schema::create('credits', function(Blueprint $table) {
			$table->increments('id');
			$table->string('organization');
			$table->string('address');
			$table->string('url');
			$table->string('banner');
			$table->string('visible', 1);
		});
	}

	public function down()
	{
		Schema::drop('credits');
	}
}