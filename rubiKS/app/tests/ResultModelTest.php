<?php

class ResultModelTest extends TestCase {

	public function testParseFunctionLessThanASecond()
	{
		$this->assertEquals('0.09', Result::parse('9')); // Leading zero
		$this->assertEquals('0.91', Result::parse('91')); // No leading zero
	}

	public function testParseFunctionMoreThanASecond()
	{
		$this->assertEquals('1.03', Result::parse(103)); // Leading zero (seconds)
		$this->assertEquals('1.23', Result::parse(123)); // No leading zero
	}

	public function testParseFunctionMoreThanAMinute()
	{
		$this->assertEquals('1:02.63', Result::parse(6263));
		$this->assertEquals('1:12.63', Result::parse(7263));
		$this->assertEquals('9:12.63', Result::parse(1263 + 9 * 60 * 100));
		$this->assertEquals('10:00.00', Result::parse(10 * 60 * 100));
	}

	public function testParseFunctionNonNumericalValues()
	{
		foreach (Result::$nonNumericalResults as $value => $meaning)
		{
			$this->assertEquals($meaning, Result::parse($value));
		}
	}

	public function testParseFunction333FM()
	{
		$this->assertEquals('32 potez', Result::parse(32, '333FM'));
	}

	public function testParseFunction33310MIN()
	{
		$this->assertEquals('51 kock (9:59.95)', Result::parse('349' . '59995', '33310MIN'));
	}

	public function testParseFunction33310MINLeadingZero()
	{
		$this->assertEquals('5 kock (1:09.95)', Result::parse('395' . '06995', '33310MIN'));
	}

	public function testParseFunction33310MINUpperBounds()
	{
		$this->assertEquals('0 kock (0.00)', Result::parse('400' . '00000', '33310MIN'));
	}

	public function testParseFunction33310MINLowerBounds()
	{
		$this->assertEquals('200 kock (10:00.00)', Result::parse('200' . '60000', '33310MIN'));
	}

	public function testFormat33310MINFunction()
	{
		$this->assertEquals('348' . '59995', Result::format33310MIN(52, 10 * 60 * 100 - 5));
		$this->assertEquals('275' . '59500', Result::format33310MIN(125, 10 * 60 * 100 - 500));
	}

	public function testFormat33310MINFunctionLeadingZero()
	{
		$this->assertEquals('275' . '05569', Result::format33310MIN(125, 5569));
	}

	public function testFormat33310MINFunctionAndParseFunction()
	{
		$this->assertEquals('42 kock (9:54.13)', Result::parse(Result::format33310MIN(42, 9 * 60 * 100 + 54 * 100 + 13), '33310MIN'));
	}

	public function testDNFMustBeTheSmallestNonNumericalResult()
	{
		$nonNumericalResults = array_keys(Result::$nonNumericalResults);
		$nonNumericalResults = array_map(function ($x) { return (int) $x; }, $nonNumericalResults);
		$min = (string) min($nonNumericalResults);
		
		$this->assertEquals('DNF', Result::$nonNumericalResults[$min]);
	}

}