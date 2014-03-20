<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('club_id', 20)->unique();
			$table->string('password');
			$table->string('name', 40);
			$table->string('last_name', 40);
			$table->string('gender', 1);
			$table->string('nationality', 2);
			$table->date('birth_date');
			$table->string('city', 40);
			$table->string('email', 40);
			$table->text('notes');
			$table->string('status');
			$table->date('joined_date');
			$table->date('banned_date');
			$table->timestamps();
			$table->string('level', 1);
			$table->string('forum_nickname')->nullable();
			$table->string('club_authority');
			$table->integer('membership_year');
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}