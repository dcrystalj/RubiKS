<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateNationalChampionshipPeriods2016 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $periods = array(
            array('2016', '2016-01-01', '2016-03-31'),
            array('2016', '2016-04-01', '2016-06-30'),
            array('2016', '2016-07-01', '2016-09-30'),
            array('2016', '2016-10-01', '2016-12-31'),
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
