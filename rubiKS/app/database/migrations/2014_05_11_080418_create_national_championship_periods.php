<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNationalChampionshipPeriods extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('national_championship_periods', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('year');
			$table->date('start_date');
			$table->date('end_date');
			$table->integer('min_results');
		});

		$periods = array(
			array('2011', '2011-01-01', '2011-02-28'),
			array('2011', '2011-03-01', '2011-04-30'),
			array('2011', '2011-05-01', '2011-06-30'),
			array('2011', '2011-07-01', '2011-08-31'),
			array('2011', '2011-09-01', '2011-10-31'),
			array('2011', '2011-11-01', '2011-12-31'),

			array('2012', '2012-01-01', '2012-03-31'),
			array('2012', '2012-04-01', '2012-06-30'),
			array('2012', '2012-07-01', '2012-09-30'),
			array('2012', '2012-10-01', '2012-12-31'),

			array('2013', '2013-01-01', '2013-03-31'),
			array('2013', '2013-04-01', '2013-06-30'),
			array('2013', '2013-07-01', '2013-09-30'),
			array('2013', '2013-10-01', '2013-12-31'),

			array('2014', '2014-01-01', '2014-03-31'),
			array('2014', '2014-04-01', '2014-06-30'),
			array('2014', '2014-07-01', '2014-09-30'),
			array('2014', '2014-10-01', '2014-12-31'),
		);

		foreach ($periods as $period) {
			NationalChampionshipPeriod::create(array(
				'year' => $period[0],
				'start_date' => $period[1],
				'end_date' => $period[2],
				'min_results' => 4
			))->save();
		}
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('national_championship_periods');
	}

}
