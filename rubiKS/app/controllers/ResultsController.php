<?php

class ResultsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return '<a href="/results/import">Uvoz novih rezultatov.</a>';
	}

	public function getHelp()
	{
		$text = 'Greš na <a href="/results/import">/results/import</a>, ' .
        'v URL dopišeš id tekme (npr.: www.rubik.si/results/import/SILJUBLJANA100101). <br>' .
        'Klikneš "Ustvari", nato pa "Uvozi", potem še enkrat "Uvozi" za DP. <br>' .
        'Na koncu v administraciji tekmo označiš kot končano (-1)! <br>' .
        '';

        return $text;
	}

	public function getExportSimpleResultsFile($competitionShortName) {
		if ($competitionShortName == "") return "Manjka ime datoteke.";

		$competition = Competition::where('short_name', $competitionShortName)->first();
		if ($competition == null) return "Tekma ni veljavna.";
		if ($competition->isFinished()) return "Tekma je že zaključena.";

		$jsonFile = public_path('results-json/' . $competitionShortName . '');

		$results = SimpleResult::all();
		file_put_contents($jsonFile, $results->toJson());

		return 'Saved to: ' . $jsonFile . '<br>' .
			'<a href="/results/import/'. $competitionShortName . '">Uvozi</a> rezultate v podatkovno bazo.';
	}

	public function getImport($competitionShortName = "") {
		if ($competitionShortName == "") return "Vnesi ime tekme. <br>Primer: /results/import/SILJUBLJANA100101";

		$competition = Competition::where('short_name', $competitionShortName)->first();
		if ($competition == null) return "Tekma ni veljavna.";
		if ($competition->isFinished()) return "Tekma je že zaključena.";

		$jsonFile = public_path('results-json/' . $competitionShortName . '');

		try {
		    $json = File::get($jsonFile);
		} catch (Illuminate\Filesystem\FileNotFoundException $e) {
			return "Datoteka ne obstaja.<br>" .
				"<a href='/results/export-simple-results-file/" . $competitionShortName . "'>Ustvari</a> datoteko.";
		}

		$json = json_decode($json);

		// /*
		// Delete all previous results
		Result::where('competition_id', $competition->id)->delete();

		// Sort results!
		$events = Event::all();
		$eventReadableIdToEvent = array();
		foreach ($events as $event) $eventReadableIdToEvent[$event->readable_id] = $event;
		$rounds = Round::all();
		$roundShortNameToSortKey = array();
		$roundShortNameToId = array();
		foreach ($rounds as $round) $roundShortNameToSortKey[$round->short_name] = $round->sort_key;
		foreach ($rounds as $round) $roundShortNameToId[$round->short_name] = $round->round_id;
		foreach ($json as $k => $r) $r->round_sort_key = $roundShortNameToSortKey[$r->round_short_name];

		//var_dump(shuffle($json)); // Testing
		usort($json, function($a, $b) {
			$e = strcmp($a->event_readable_id, $b->event_readable_id);
			if ($e !== 0) return $e;
			if ($a->round_sort_key < $b->round_sort_key) return -1;
			if ($a->round_sort_key > $b->round_sort_key) return 1;
			return 0;
		});

		$resultsArray = array(); // event_readable_id -> round_short_name -> results
		foreach ($json as $r) {
			if (!array_key_exists($r->event_readable_id, $resultsArray)) {
				$resultsArray[$r->event_readable_id] = array();
			}
			if (!array_key_exists($r->round_short_name, $resultsArray[$r->event_readable_id])) {
				$resultsArray[$r->event_readable_id][$r->round_short_name] = array();
			}
			$resultsArray[$r->event_readable_id][$r->round_short_name][] = $r;
		}

		try {
			foreach ($resultsArray as $eventKey => $roundsArray) {
				$eventResults = array(); // We're gonna need all results when assigning medals

				foreach ($roundsArray as $roundKey => $results) {
					// Save current single/average NR
					$event = $eventReadableIdToEvent[$results[0]->event_readable_id];
					$currentSingleNR = Result::where('event_id', $event->id)->orderBy('single', 'asc')->first();
					$currentAverageNR = Result::where('event_id', $event->id)->orderBy('average', 'asc')->first();
					$currentSingleNR = (int) ($currentSingleNR === null ? Result::dnfNumericalValue() : $currentSingleNR->single);
					$currentAverageNR = (int) ($currentAverageNR === null ? Result::dnfNumericalValue() : $currentAverageNR->average);

					// Store results for this round
					foreach ($results as $r) {
						$event = Event::where('readable_id', $r->event_readable_id)->firstOrFail();
						$round = Round::where('short_name', $r->round_short_name)->firstOrFail();
						$user = User::where('club_id', $r->user_club_id)->firstOrFail();

						// Convert DNF, DNS, DSQ to numerical values in $r->results if the entry script failed to do so
						$times = explode("@", $r->results);
						foreach ($times as $key => $time) {
							if (strcasecmp($time, 'dnf') == 0) $time = Result::dnfNumericalValue();
							if (strcasecmp($time, 'dns') == 0) $time = Result::dnsNumericalValue();
							if (strcasecmp($time, 'dsq') == 0) $time = Result::dsqNumericalValue();
							$times[$key] = $time;
						}
						$r->results = implode("@", $times);

						$result = new Result();
						$result->competition_id = $competition->id;
						$result->event_id = $event->id;
						$result->round_id = $round->id;
						$result->user_id = $user->id;
						$result->single = (int) $r->single;
						$result->average = (int) $r->average;
						$result->results = $r->results;
						$result->date = $competition->date; // Kaj pa večdnevne tekme?

						$result->single_nr = 0;
						$result->average_nr = 0;
						$result->single_pb = 0;
						$result->average_pb = 0;

						// PB
						$result->single_pb = 0;
						$spb = Result::where('user_id', $user->id)->where('event_id', $event->id)->orderBy('single', 'asc')->first();
						$spb = (int) ($spb === null ? Result::dnfNumericalValue() : $spb->single);
						if ($result->single <= $spb && $result->single < (int) Result::dnfNumericalValue()) $result->single_pb = 1;

						if ($event->showAverage()) {
							$result->average_pb = 0;
							$apb = Result::where('user_id', $user->id)->where('event_id', $event->id)->orderBy('average', 'asc')->first();
							$apb = (int) ($apb === null ? Result::dnfNumericalValue() : $apb->average);
							if ($result->average <= $apb && $result->average < (int) Result::dnfNumericalValue()) $result->average_pb = 1;
						}
						// /PB

						$result->medal = 0;
						$result->championship_rank = 0;

						$result->save();

						$eventResults[] = $result;
					}

					// NR
					// POTREBNO JE UPOŠTEVATI SAMO SLOVENCE!
					//echo $currentSingleNR . ' ' . $currentAverageNR . '<br>';
					// Find best single and average of the round
					$bestSingle = (int) Result::dnfNumericalValue();
					$bestAverage = (int) Result::dnfNumericalValue();
					foreach ($results as $r) {
						if ((int) $r->single < (int) $bestSingle) $bestSingle = (int) $r->single;
						if ((int) $r->average < (int) $bestAverage) $bestAverage = (int) $r->average;
					}
					if ((int) $bestSingle < (int) $currentSingleNR && (int) $bestSingle < (int) Result::dnfNumericalValue()) {
						$best = Result::where('competition_id', $competition->id)
							->where('event_id', $result->event_id)
							->where('round_id', $result->round_id)
							->where('single', $bestSingle)
							->update(['single_nr' => 1]);
					}
					if ($event->showAverage() && (int) $bestAverage < (int) $currentAverageNR && (int) $bestAverage < (int) Result::dnfNumericalValue()) {
						$best = Result::where('competition_id', $competition->id)
							->where('event_id', $result->event_id)
							->where('round_id', $result->round_id)
							->where('average', $bestAverage)
							->update(['average_nr' => 1]);
					}
					// /NR
				}

				// Medals
				if ($event->showAverage()) {
					usort($eventResults, function($a, $b) {
						if ($a->average < $b->average) return -1;
						if ($a->average > $b->average) return 1;
						return 0;
					});
				} else {
					usort($eventResults, function($a, $b) {
						if ($a->single < $b->single) return -1;
						if ($a->single > $b->single) return 1;
						return 0;
					});
				}

				$people = array(); // People that already got the medal
				$rank = 1;
				foreach ($eventResults as $r) {
					if (in_array($r->user_id, $people)) continue;
					if ($event->showAverage() && $r->average >= Result::dnfNumericalValue()) break;
					else if (!$event->showAverage() && $r->single >= Result::dnfNumericalValue()) break;

					$r->medal = $rank;
					$r->update();
					$people[] = $r->user_id;

					if ($rank >= 3) break;
					$rank++;
				}

				// /Medals
			}
		} catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			var_dump($r);
			return 'Model not found!';
		}
		// */

		echo 'Rezultati so bili uspešno uvoženi! <br>' . PHP_EOL;

		// Championship rank
		echo '<br><a href="/national-championship/generate-all/' . $competition->year . '">Uvoz rezultatov v državno prvenstvo.</a>. <br>' . PHP_EOL;
		// /Championship rank

		return 'Done!';
	}

}
