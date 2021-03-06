<?php

class NationalChampionship {

	/**
	 * Generate national championship results for a given year and
	 * save (update `national_championship_rank` column) results to `results` table
	 *
	 * @param 	integer			Year
	 * @param 	string 			Event's readable ID
	 * @return 	bool
	 */
	public static function generateRanks($year, $event)
	{
		if ($year < NationalChampionshipPeriod::minYear()) return True;

		$currentDate = date('Y-m-d');
		$dnf = Result::dnfNumericalValue();
		$resultType = $event->showAverage() ? 'average' : 'single'; // Result type to be used when comparing results

		// Delete all previous championship ranks for a given year
		Result::whereEventId($event->id)
				->where('championship_rank', '!=', '0')
				->where('results.date', '>=', $year . '-01-01')
				->where('results.date', '<=', $year . '-12-31')
				->update(array('championship_rank' => 0));

		// Fetch periods of the given year
		$periods = NationalChampionshipPeriod::where('year', $year)->get();

		$mergeWithPrevious = False;
		$mergedPeriods = 1;
		$previousPeriodStartDate = null;
		foreach ($periods as $i => $period) {

			$startDate = $mergeWithPrevious ? $previousPeriodStartDate : $period->start_date;

			// Get all users with valid results for a given period
			$periodResults = Result::select('user_id')
				->join('competitions', 'results.competition_id', '=', 'competitions.id')
				->where('competitions.championship', '1')
				->whereEventId($event->id)
				->where('results.date', '>=', $startDate)
				->where('results.date', '<=', $period->end_date)//;
			//if (FALSE && $year < 2015) {
			//	$periodResults = $periodResults->where($resultType, '<', $dnf);
			//}
			//$periodResults = $periodResults
				->groupBy('user_id')
				->get();

			$countValidResults = 0;
			foreach ($periodResults as $r) {
				//if ((int) $r->$resultType < (int) $dnf) $countValidResults += 1; // Weird data coming out of GROUP BY ORDER BY!
				$temp = Result::select('results.*')
					->join('competitions', 'results.competition_id', '=', 'competitions.id')
					->where('competitions.championship', '1')
					->whereUserId($r->user_id)
					->whereEventId($event->id)
					->where('results.date', '>=', $startDate)
					->where('results.date', '<=', $period->end_date)
					->orderBy($resultType, 'asc')
					->orderBy('results.date', 'asc')
					->firstOrFail();
				if ((int) $temp->$resultType < (int) $dnf) $countValidResults += 1;
			}
			//if ($event->readable_id == "333BLD") var_dump($countValidResults);
			// If there are less than N results in a period, then periods get merged!
			// $i + 1 < count($periods), because the last period cannot be merged.
			if ($countValidResults < $period->min_results AND $i + 1 < count($periods)) {

				// If 2011 and 3 periods have already been merged, then we cannot merge any more periods!
				if (!($year == 2011) OR $mergedPeriods < 3) {
					if (!$mergeWithPrevious) $previousPeriodStartDate = $period->start_date;
					$mergeWithPrevious = True;
					$mergedPeriods++;
					continue;
				}
			}

			if ($mergeWithPrevious) {
				$mergeWithPrevious = False;
				$mergedPeriods = 1;
				$previousPeriodStartDate = null;
			}

			// Everything's fine... start calculating!

			// Get best result for each user
			$results = array();
			foreach ($periodResults as $r) {
				$results[] = Result::select('results.*')
					->join('competitions', 'results.competition_id', '=', 'competitions.id')
					->where('competitions.championship', '1')
					->whereUserId($r->user_id)
					->whereEventId($event->id)
					->where('results.date', '>=', $startDate)
					->where('results.date', '<=', $period->end_date)
					->orderBy($resultType, 'asc')
					->orderBy('results.date', 'asc')
					->firstOrFail();
			}

			// Sort results by result type (single/average)
			usort($results, function($a, $b) use ($resultType) {
				if ($a->$resultType == $b->$resultType) return 0;
				return ($a->$resultType > $b->$resultType) ? 1 : -1;
			});

			// Update championship ranks
			$rank = 0;
			$previousResult = NULL;
			$playersWithSameResult = 1;
			foreach ($results as $result) {
				// Calculate rank
				if ($previousResult === $result->$resultType) {
					$playersWithSameResult += 1;
				} else {
					$rank += $playersWithSameResult;
					$playersWithSameResult = 1;
				}
				$previousResult = $result->$resultType;

				// DNF, DNS, ...
				if ((int) $result->$resultType >= (int) Result::dnfNumericalValue()) {
					$rank = -1;
					$playersWithSameResult = 0;
				}

				// Save
				$result->championship_rank = $rank;
				$result->save();
			}
		}

		return True;
	}

	/**
	 * Fetch results and periods for a given year.
	 * This method returns actual periods rather than periods from the database,
	 * because sometimes periods need to be merged if there are not enough results in each period.
	 */
	public static function allResultsAndActualPeriods($year, $eventId, $periods, $withUsers)
	{
		$allResults = array();
		$actualPeriods = array();

		$dnf = Result::dnfNumericalValue();
		$event = Event::findOrFail($eventId);
		$resultType = $event->showAverage() ? 'average' : 'single'; // Result type to be used when comparing results

		$mergeWithPrevious = False;
		$mergedPeriods = 1;
		$previousPeriodStartDate = null;
		foreach ($periods as $i => $period) {
			$startDate = $mergeWithPrevious ? $previousPeriodStartDate : $period->start_date;

			$results = Result::whereEventId($eventId)
				->where('date', '>=', $startDate)
				->where('date', '<=', $period->end_date)
				->where('championship_rank', '!=', 0)
				->orderBy('championship_rank', 'asc');
			if ($withUsers) $results = $results->with('user');
			$results = $results->get();

			// Sort
			$results->sort(function($a, $b) {
				if ($a->championship_rank == $b->championship_rank) return 0;
				if ($a->championship_rank < 0) return 1;
				if ($b->championship_rank < 0) return -1;
				return $a->championship_rank < $b->championship_rank ? -1 : 1;
			});


			$countValidResults = 0;
			foreach ($results as $r) { if ((int) $r->$resultType < (int) $dnf) $countValidResults += 1; }
			//if ($event->readable_id == "333BLD") var_dump($countValidResults);
			if ($countValidResults < $period->min_results AND $i + 1 < count($periods)) {

				// In 2011, maximum number of merged periods was 3!
				// In 2012+ there was no maximum number of merged periods.
				if (!($year == 2011) OR $mergedPeriods < 3) {
					if (!$mergeWithPrevious) $previousPeriodStartDate = $period->start_date;
					$mergeWithPrevious = True;
					$mergedPeriods++;
					continue;
				}
			}

			$allResults[] = $results;
			$actualPeriods[] = array(
				'start_date' => $startDate,
				'end_date' => $period->end_date,
			);

			$mergeWithPrevious = False;
			$mergedPeriods = 1;
			$previousPeriodStartDate = null;
		}

		return [ $allResults, $actualPeriods ];
	}

	/**
	 * Generate final stats based on a fromula (for an event for a given year)
	 *
	 * @param 	integer 	Year
	 * @param 	string 		Event's readable ID
	 * @return 	bool
	 */
	public static function generateStatsEvent($year, $event)
	{
		if ($year < NationalChampionshipPeriod::minYear()) return False;

		// Init
		$resultType = $event->showAverage() ? 'average' : 'single';

		$periods = NationalChampionshipPeriod::where('year', $year)->get();
		list($allResults, $actualPeriods) = NationalChampionship::allResultsAndActualPeriods($year, $event->id, $periods, TRUE);

		// Generate
		$finalRanks = array();
		foreach ($allResults as $i => $results) {

			// Calculate final (yearly) rank for a given event
			foreach ($results as $result) {
				if (!array_key_exists($result->user_id, $finalRanks)) {
					$finalRanks[$result->user_id] = array(
						'score' => 0,
						'best' => $result->$resultType,
						'periods' => array(),
					);

					// Prepare periods array
					$finalRanks[$result->user_id]['periods'] = array();
					for ($j = 0; $j < count($allResults); $j++)
						$finalRanks[$result->user_id]['periods'][] = '-';
				}

				if ($result->championship_rank > 0) {
					$finalRanks[$result->user_id]['score'] += self::nationalChampionshipRankFormula($result->championship_rank, $year);
					$finalRanks[$result->user_id]['periods'][$i] = $result->championship_rank;
				}

				if ($result->$resultType < $finalRanks[$result->user_id]['best'])
					$finalRanks[$result->user_id]['best'] = $result->$resultType;
			}
		}

		// Scores must be rounded to avoid the "1/4 + 1/3 + 1/4 + 1/6 != 1/1" problem!
  		// http://stackoverflow.com/a/3726761
		if ($year != 2011) array_walk($finalRanks, function(&$item, $key) { $item['score'] = round($item['score'], 2); });

		// Sort final ranks - score DESC, best ASC
		$sort = function($a, $b) {
			if ($a['score'] == $b['score']) {
				if ($a['best'] == $b['best']) return 0;
				return $a['best'] > $b['best'] ? 1 : -1;
			}
			return ($a['score'] < $b['score']) ? 1 : -1;
		};
		uasort($finalRanks, $sort);

		// Calculate ranks
		$rank = 0;
		$previousScore = null;
		$previousSameScore = 1;
		foreach ($finalRanks as $userId => $entry) {
			$thisScore = array($entry['score'], $entry['best']);
			if ($thisScore == $previousScore) {
				$previousSameScore += 1;
			} else {
				$rank += $previousSameScore;
				$previousSameScore = 1;
			}
			$previousScore = $thisScore;

			$finalRanks[$userId]['rank'] = $rank;
		}

		// Add details
		foreach ($finalRanks as $userId => $entry)
			$finalRanks[$userId]['details'] = $entry['best'] . '|' . implode(',', $entry['periods']);

		// Delete score for 2011
		if ($year == 2011) array_walk($finalRanks, function(&$item, $key) { $item['score'] = ''; });

		// Delete old and insert new
		NationalChampionshipStatsEvent::updateRanks($year, $event->id, $finalRanks);

		return True;
	}

	/**
	 * Calculate a cumulative total of points from all events for each user (for a given year)
	 *
	 * @param 	integer 	Year
	 * @return 	bool
	 */
	public static function generateStatsFinal($year)
	{
		if ($year < NationalChampionshipPeriod::minYear()) return False;
		if ($year == 2011) return False; // This did not exist in 2011!

		// Get all results that count towards the national championship
		$results = Result::where('championship_rank', '!=', 0)
				->where('date', '>=', $year . '-01-01')
				->where('date', '<=', $year . '-12-31')
				->orderBy('championship_rank', 'asc')
				->get();

		// Calculate each person's score
		$scores = array();
		$additional = array();
		$users = array();
		foreach ($results as $result) {
			if (!array_key_exists($result->user_id, $scores)) {
				$scores[$result->user_id] = 0;
				$additional[$result->user_id] = array();
			}

			if ($result->championship_rank < 1) continue;
			$scores[$result->user_id] += self::nationalChampionshipRankFormula($result->championship_rank, $year);
			$additional[$result->user_id][] = $result->championship_rank;
		}

		// Scores must be rounded to avoid the "1/4 + 1/3 + 1/4 + 1/6 != 1/1" problem!
		array_walk($scores, function (&$item, $key) { $item = round($item, 2); });

		// Sort scores - score DESC
		uasort($scores, function($a, $b) {
			return $a < $b ? 1 : -1;
		});

		// Calculate rank for each competitor
		$ranks = array();
		$rank = 0;
		$previousScore = null;
		$previousSameResults = 1;
		foreach ($scores as $userId => $score) {
			if ($score == $previousScore) {
				$previousSameResults += 1;
			} else {
				$rank += $previousSameResults;
				$previousSameResults = 1;
			}

			$previousScore = $score;
			$ranks[$userId] = $rank;
		}

		// Delete old data
		NationalChampionshipStatsFinal::where('year', $year)->delete();

		foreach ($scores as $userId => $score) {
			NationalChampionshipStatsFinal::create(array(
				'year' => $year,
				'user_id' => $userId,
				'rank' => $ranks[$userId],
				'score' => $score,
				'details' => implode(',', $additional[$userId]),
			))->save();
		}

		return True;
	}

	/**
	 * Generate all stats (related to national championship) for a given year
	 */
	public static function generate($year)
	{
		$year = (int) $year;
		$events = Event::all();
		$status = array();

		foreach ($events as $event) {
			// Generate ranks (for all periods) for an event
			if (NationalChampionship::generateRanks($year, $event) === True) {
				$success = True;
			} else {
				$success = False;
			}
			$status[] = array($year, $event->readable_id, $success);

			// Generate yearly stats for an event
			if (NationalChampionship::generateStatsEvent($year, $event) === True) {
				$success = True;
			} else {
				$success = False;
			}
			$status[] = array($year, $event->readable_id . ' FINAL', $success);
		}

		// Generate final ranks
		if (NationalChampionship::generateStatsFinal($year) === True) {
			$success = True;
		} else {
			$success = False;
		}
		$status[] = array($year, 'FINAL', $success);

		return $status;
	}

	/**
	 * Calculate a rank-based score
	 */
	public static function nationalChampionshipRankFormula($rank, $year = null)
	{
		if ($rank <= 0) return 0;

		if (2011 == $year) return 1 / pow(10, $rank);

		return 100 / $rank;
	}
}
