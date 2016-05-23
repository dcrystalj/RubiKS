<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateNationalChampionshipPeriods2015 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $periods = array(
            array('2015', '2015-01-01', '2015-03-31'),
            array('2015', '2015-04-01', '2015-06-30'),
            array('2015', '2015-07-01', '2015-09-30'),
            array('2015', '2015-10-01', '2015-12-31'),
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

    }

}
