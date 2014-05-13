<?php

class NationalChampionship {

	public static $dirtyResultType = null;
	
	/**
	 * Generate national championship results for a given year and 
	 * save (update `national_championship_rank` column) results to `results` table
	 *
	 * @param 	integer			Year
	 * @param 	string 			Event's readable ID
	 * @return 	bool
	 */
	public static function generateRanks($year, $eventId)
	{
		if ($year < NationalChampionshipPeriod::minYear()) return False;

		$currentDate = date('Y-m-d');
		$dnf = Result::dnfNumericalValue();
		$event = Event::whereReadableId($eventId);
		$resultType = $event->showAverage() ? 'average' : 'single';

		// Delete all previous championship ranks for a given year
		Result::whereEventId($event->id)
				->where('championship_rank', '>', '0')
				->where('results.date', '>=', $year . '-01-01')
				->where('results.date', '<=', $year . '-12-31')
				->update(array('championship_rank' => 0));

		// Fetch given year's periods
		$periods = NationalChampionshipPeriod::where('year', $year)->get();

		$mergeWithPrevious = False;
		$previousPeriodStartDate = null;
		foreach ($periods as $i => $period) {

			$startDate = $mergeWithPrevious ? $previousPeriodStartDate : $period->start_date;

			// Get all users with valid results for a given period
			$periodResults = Result::select('user_id')
				->join('competitions', 'results.competition_id', '=', 'competitions.id')
				->where('competitions.championship', '1')
				->whereEventId($event->id)
				->where('results.date', '>=', $startDate)
				->where('results.date', '<=', $period->end_date)
				->where($resultType, '<', $dnf)
				->groupBy('user_id')
				->get();

			// If there are less than N results in a period, then periods get merged!
			// $i + 1 < count($periods), because the last period cannot be merged.
			if (count($periodResults) < $period->min_results AND $i + 1 < count($periods)) {
				if (!$mergeWithPrevious) $previousPeriodStartDate = $period->start_date;
				$mergeWithPrevious = True;
				continue;
			}

			if ($mergeWithPrevious) {
				$mergeWithPrevious = False;
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

			// Sort results by result type
			self::$dirtyResultType = $resultType;
			usort($results, function($a, $b) {
				$resultType = self::$dirtyResultType;

				if ($a->$resultType == $b->$resultType) return 0;
				return ($a->$resultType > $b->$resultType) ? 1 : -1;
			});
			self::$dirtyResultType = null;

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

				// Save
				$result->championship_rank = $rank;
				$result->save();
			}
		}

		return True;
	}

	/**
	 * ?
	 */
	public static function allResultsAndActualPeriods($year, $event, $periods, $withUsers)
	{
		$allResults = array();
		$actualPeriods = array();

		$mergeWithPrevious = False;
		$previousPeriodStartDate = null;
		foreach ($periods as $i => $period) {
			$startDate = $mergeWithPrevious ? $previousPeriodStartDate : $period->start_date;
			
			$results = Result::whereEventId($event->id)
				->where('date', '>=', $startDate)
				->where('date', '<=', $period->end_date)
				->where('championship_rank', '>', 0)
				->orderBy('championship_rank', 'asc');
			if ($withUsers) $results = $results->with('user');
			$results = $results->get();

			if (count($results) < $period->min_results AND $i + 1 < count($periods)) {
				if (!$mergeWithPrevious) $previousPeriodStartDate = $period->start_date;
				$mergeWithPrevious = True;
				continue;
			}
			
			$allResults[] = $results;
			$actualPeriods[] = array(
				'start_date' => $startDate,
				'end_date' => $period->end_date,
			);

			$mergeWithPrevious = False;
			$previousPeriodStartDate = null;
		}

		return [ $allResults, $actualPeriods ];
	}

	/**
	 * ???
	 *
	 * @param 	integer 	Year
	 * @param 	string 		Event's readable ID
	 * @return 	bool
	 */
	public static function generateStatsEvent($year, $eventId)
	{
		if ($year < NationalChampionshipPeriod::minYear()) return False;
		if ($year == 2011) return self::generateStatsEvent2011($eventId); // 2011 uses different sorting algorithm!
		
		// Init
		$event = Event::where('readable_id', $eventId)->firstOrFail();
		$resultType = $event->showAverage() ? 'average' : 'single';
		
		$periods = NationalChampionshipPeriod::where('year', $year)->get();
		list($allResults, $actualPeriods) = NationalChampionship::allResultsAndActualPeriods($year, $event,$periods, TRUE);

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

				$finalRanks[$result->user_id]['score'] += Result::nationalChampionshipRankFormula($result->championship_rank);
				$finalRanks[$result->user_id]['periods'][$i] = $result->championship_rank;

				if ($result->$resultType < $finalRanks[$result->user_id]['best'])
					$finalRanks[$result->user_id]['best'] = $result->$resultType;
			}

			// Scores must be rounded to avoid the "1/4 + 1/3 + 1/4 + 1/6 != 1/1" problem!
	  		// http://stackoverflow.com/a/3726761
			array_walk($finalRanks, function(&$item, $key) { $item['score'] = round($item['score'], 2); });

			// Sort final ranks - score DESC, best ASC
			uasort($finalRanks, function($a, $b) {
				if ($a['score'] == $b['score']) {
					if ($a['best'] == $b['best']) return 0;
					return $a['best'] > $b['best'] ? 1 : -1;
				}
				return ($a['score'] < $b['score']) ? 1 : -1;
			});
		}

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

		// Delete old and insert new
		NationalChampionshipStatsEvent::updateRanks($year, $event->id, $finalRanks);

		return True;
	}

	/**
	 * 2011 ONLY!
	 * Sorted by best rank ASC, best score ASC.
	 */
	public static function generateStatsEvent2011($eventId) {

		// Init
		$year = 2011;
		$event = Event::where('readable_id', $eventId)->firstOrFail();
		$resultType = $event->showAverage() ? 'average' : 'single';
		
		$periods = NationalChampionshipPeriod::where('year', $year)->get();
		list($allResults, $actualPeriods) = NationalChampionship::allResultsAndActualPeriods($year, $event,$periods, TRUE);

		// Generate
		$finalRanks = array();
		foreach ($allResults as $i => $results) {

			// Calculate final (yearly) rank for a given event
			foreach ($results as $result) {
				if (!array_key_exists($result->user_id, $finalRanks)) {
					$finalRanks[$result->user_id] = array(
						'bestRank' => $result->championship_rank, 
						'bestResult' => $result->$resultType, 
						'periods' => array(),
					);

					// Prepare periods array
					$finalRanks[$result->user_id]['periods'] = array();
					for ($j = 0; $j < count($allResults); $j++)
						$finalRanks[$result->user_id]['periods'][] = '-';
				}

				$finalRanks[$result->user_id]['bestRank'] = min($finalRanks[$result->user_id]['bestRank'], $result->championship_rank);
				$finalRanks[$result->user_id]['periods'][$i] = $result->championship_rank;

				if ($result->$resultType < $finalRanks[$result->user_id]['bestResult'])
					$finalRanks[$result->user_id]['bestResult'] = $result->$resultType;
			}

			// Sort final ranks - bestRank ASC, best ASC
			uasort($finalRanks, function($a, $b) {
				if ($a['bestRank'] == $b['bestRank']) {
					if ($a['bestResult'] == $b['bestResult']) return 0;
					return $a['bestResult'] > $b['bestResult'] ? 1 : -1;
				}
				return ($a['bestRank'] > $b['bestRank']) ? 1 : -1;
			});
		}

		// Calculate ranks
		$rank = 0; 
		$previousScore = null; 
		$previousSameScore = 1;
		foreach ($finalRanks as $userId => $entry) {
			$thisScore = array($entry['bestRank'], $entry['bestResult']);
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
		foreach ($finalRanks as $userId => $entry) {
			$finalRanks[$userId]['details'] = $entry['bestResult'] . '|' . implode(',', $entry['periods']);
			$finalRanks[$userId]['score'] = '';
		}

		// Delete old and insert new
		NationalChampionshipStatsEvent::updateRanks($year, $event->id, $finalRanks);

		return True;
	}

	/**
	 * Calculate a cumulative total of points from all events
	 *
	 * @param 	integer 	Year
	 * @return 	bool
	 */
	public static function generateStatsFinal($year)
	{
		if ($year < NationalChampionshipPeriod::minYear()) return False;
		if ($year == 2011) return False; // This did not exist in 2011!

		// Get all results that count towards the national championship
		$results = Result::where('championship_rank', '>', 0)
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

			$scores[$result->user_id] += Result::nationalChampionshipRankFormula($result->championship_rank, $year);
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
}