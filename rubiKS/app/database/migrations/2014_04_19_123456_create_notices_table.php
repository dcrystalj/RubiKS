<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNoticesTable extends Migration {

	public function up()
	{
		Schema::create('notices', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->text('text');
			$table->timestamps();
			$table->date('visible_until');
		});
	}

	public function down()
	{
		Schema::drop('notices');
	}
}