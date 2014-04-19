<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration {

	public function up()
	{
		Schema::create('news', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->text('text');
			$table->integer('user_id')->unsigned();
			$table->timestamps();
			$table->string('url_slug', 60)->unique();
		});
	}

	public function down()
	{
		Schema::drop('news');
	}
}