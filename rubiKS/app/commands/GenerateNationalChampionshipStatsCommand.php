<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateNationalChampionshipStatsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'rubiks:nc';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate stats for the national championship.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$log = array();
		if ($this->argument('year') !== NULL) {
			$year = (int) $this->argument('year');
			$this->info('Generating national championship stats for ' . $year . '...');
			$log = NationalChampionship::generate($year);
		} else {
			$this->info('Generating ALL national championship stats...');
			$minYear = NationalChampionshipPeriod::minYear();
			for ($year = $minYear; $year <= date('Y'); $year++) {
				$this->info($year);
				$log = array_merge($log, NationalChampionship::generate($year));
			}
		}

		foreach ($log as $l) $this->info($l[0] . ' ' . ($l[2] ? '1' : 'ERROR') . ' ' . $l[1]);

		$this->info('Done!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('year', InputArgument::OPTIONAL, 'Generate stats only for a given year.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
