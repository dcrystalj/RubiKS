<?php

class HelperMethodsTest extends TestCase {

	public function testParseMethodLeadingZeros()
	{
		$actual = Date::parse('2014-01-02');
		$expected = '02. 01. 2014';
		$this->assertEquals($expected, $actual);
	}

	public function testDateTimeMethod()
	{
		$datetime = '2014-12-01 13:37:00';
		$this->assertEquals('01. 12. 2014 13:37', Date::dateTime($datetime));
	}

	public function testDateTimeMethodDateOnly()
	{
		$this->assertEquals('01. 12. 2014', Date::dateTime('2014-12-01 13:37:00', TRUE));
	}

	public function testValidDateMethod()
	{
		$this->assertTrue(Date::validDate(2014, 5, 3));
	}

	public function testValidDateMethodLowerBoundary()
	{
		$this->assertFalse(Date::validDate(999, 12, 31));
	}

	public function testValidDateMethodUpperBoundary()
	{
		$this->assertFalse(Date::validDate(10000, 1, 1));
	}

	public function testYmdToDateMethodLeapYear()
	{
		$this->assertEquals('2012-02-29', Date::ymdToDate('2012', '2', '29'));
		$this->assertFalse(Date::ymdToDate('2011', '2', '29'));
	}

	public function testYmdToDateMethodLeadingZeroMonth()
	{
		$this->assertEquals('2014-05-03', Date::ymdToDate('2014', '05', '3'));
	}

	public function testYmdToDateMethodLeadingZeroDay()
	{
		$this->assertEquals('2014-05-03', Date::ymdToDate('2014', '5', '03'));
	}

}