<?php

class RoundModelTest extends TestCase {

	public function testIfDefaultRoundExists()
	{
		$default = Round::findOrFail(Round::DEFAULT_ROUND_ID);
		
		$this->assertEquals($default->short_name, 'default');
		$this->assertEquals($default->name, '');
	}

	public function testIfDefaultFinalRoundExists()
	{
		$final = Round::findOrFail(Round::DEFAULT_FINAL_ROUND_ID);

		$this->assertEquals($final->short_name, 'default_final');
	}

}