<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoundsTable extends Migration {

	public function up()
	{
		Schema::create('rounds', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('short_name')->unique();
			$table->integer('sort_key');
		});


		/* Insert data */

		(new Round(array(
			'name' => '',
			'short_name' => 'default',
			'sort_key' => 0,
		)))->save();

		(new Round(array(
			'name' => 'Skupna lestvica',
			'short_name' => 'default_final',
			'sort_key' => 999,
		)))->save();


		(new Round(array(
			'name' => '1. krog',
			'short_name' => 'r1',
			'sort_key' => 1,
		)))->save();

		(new Round(array(
			'name' => '2. krog',
			'short_name' => 'r2',
			'sort_key' => 2,
		)))->save();

		(new Round(array(
			'name' => '3. krog',
			'short_name' => 'r3',
			'sort_key' => 3,
		)))->save();

		(new Round(array(
			'name' => '4. krog',
			'short_name' => 'r4',
			'sort_key' => 4,
		)))->save();

		(new Round(array(
			'name' => '5. krog',
			'short_name' => 'r5',
			'sort_key' => 5,
		)))->save();


		(new Round(array(
			'name' => 'Finale',
			'short_name' => 'c1',
			'sort_key' => 200,
		)))->save();

		(new Round(array(
			'name' => 'Polfinale',
			'short_name' => 'c2',
			'sort_key' => 199,
		)))->save();

		(new Round(array(
			'name' => 'Četrtfinale',
			'short_name' => 'c3',
			'sort_key' => 198,
		)))->save();
	}

	public function down()
	{
		Schema::drop('rounds');
	}
}