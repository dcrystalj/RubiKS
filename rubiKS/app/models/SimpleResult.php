<?php

class SimpleResult extends Eloquent {

	protected $table = 'results_simple';
	public $timestamps = true;
	protected $softDelete = false;
	protected $fillable = array('competition_short_name', 'event_readable_id', 'round_short_name', 'user_club_id', 'results');

	public static function getSingleAttribute($value)
	{
		return (int) $value;
	}

	public static function getAverageAttribute($value)
	{
		return (int) $value;
	}

	public static function boot()
	{
		parent::boot();

		SimpleResult::creating(function($SimpleResult)
		{
			// Check whether a result already exists
            $countExisting = SimpleResult::where('user_club_id', $SimpleResult->user_club_id)
                    ->where('event_readable_id', $SimpleResult->event_readable_id)
                    ->where('round_short_name', $SimpleResult->round_short_name)
                    ->where('competition_short_name', $SimpleResult->competition_short_name)
                    ->count();
            if ($countExisting > 0) return false;
		});

		SimpleResult::saving(function($SimpleResult)
		{
			// Calculate single and average

			// Zaenkrat dela samo za avg3/5!

			/*
			 * Vsi merjeni rezultati pod 10 minutami (z merilniki) in sledeča povprečja se merijo v stotinkah sekunde,
			 * s povprečji zaokroženimi na najbližjo stotinko sekunde (x,004 se zaokroži na x,00; x,005 se zaokroži na x,01).
			 * Format zapisa: 1:23,45
			*/

			// 333BLD
			// 333FT
			// 33310MIN
			// 333FM
			// 666, 777

			// Skripta naj pošlje zraven rezultata še events.`attempts` (št. poskusov)
			$attempts = 5;

			// Mean of 3 events
			$meanOf3Events = array(
				'333COPY',
				'333FT',
				'666',
				'777',
			);

			if (empty($SimpleResult->user_club_id)) return false;

			$dnf = Result::dnfNumericalValue();
			$dns = Result::dnsNumericalValue();
			$dsq = Result::dsqNumericalValue();

			if ($SimpleResult->event_readable_id === "333FM") {
				$value = $SimpleResult->results;
				if (strcasecmp($value, "dnf") == 0) {
					$value = $dnf;
				} else if (strcasecmp($value, "dns") == 0) {
					$value = $dns;
				} else if (strcasecmp($value, "dsq") == 0) {
					$value = $dsq;
				}

				$SimpleResult->single = (int) $value;
				$SimpleResult->average = (int) 0;
				$SimpleResult->results = "";
			} else if ($SimpleResult->event_readable_id === "33310MIN") {
				$results = explode("@", $SimpleResult->results);
				if (count($results) != 2) return false;
				$nrCubes = (int) $results[0];
				$value = $results[1]; // Time
				if (strcasecmp($value, "dnf") == 0) {
					$value = $dnf;
				} else if (strcasecmp($value, "dns") == 0) {
					$value = $dns;
				} else if (strcasecmp($value, "dsq") == 0) {
					$value = $dsq;
				} else {
					$value = self::parseString($value);
					if ($value === null) return false;
				}

				$str = Result::format33310MIN($nrCubes, $value);

				$SimpleResult->single = (int) $str;
				$SimpleResult->average = (int) 0;
				$SimpleResult->results = "";
			} else if ($SimpleResult->event_readable_id === "333BLD" || $SimpleResult->event_readable_id === "2345") { // Best of 3
				$attempts = 3;
				if ($SimpleResult->event_readable_id === "2345") $attempts = 2;
				$results = explode("@", $SimpleResult->results);
				if (count($results) < $attempts) return false;

				$allResults = array();
				$min = $dnf;
				if (count($results) < $attempts) return false;
				for ($i = 0; $i < $attempts; $i++) {
					$value = $results[$i];
					if ($value === "" || $value === null) return false;

					if (strcasecmp($value, "dnf") == 0 || $value == $dnf) {
						$cs = $dnf;
					} else if (strcasecmp($value, "dns") == 0 || $value == $dns) {
						$cs = $dns;
					} else if (strcasecmp($value, "dsq") == 0 || $value == $dsq) {
						$cs = $dsq;
					} else {
						$cs = self::parseString($value);
						if ($cs === null) return false;
					}

					$allResults[] = $cs;
					if ($cs < $min) $min = $cs;
				}

				$SimpleResult->single = $min;
				$SimpleResult->average = 0;
				$SimpleResult->results = implode("@", $allResults);
			} else if (in_array($SimpleResult->event_readable_id, $meanOf3Events)) { // Mean of 3
				$attempts = 3;
				$results = explode("@", $SimpleResult->results);
				$allResults = array();
				$min = $dnf;
				$max = 0;
				$failedCount = 0;
				$sum = 0;
				if (count($results) < $attempts) return false;
				for ($i = 0; $i < $attempts; $i++) {
					$value = $results[$i];
					if ($value === "" || $value === null) return false;

					if (strcasecmp($value, "dnf") == 0 || $value == $dnf) {
						$cs = $dnf;
						$failedCount++;
					} else if (strcasecmp($value, "dns") == 0 || $value == $dns) {
						$cs = $dns;
						$failedCount++;
					} else if (strcasecmp($value, "dsq") == 0 || $value == $dsq) {
						$cs = $dsq;
						$failedCount++;
					} else {
						$cs = self::parseString($value);
						if ($cs === null) return false;
					}

					$allResults[] = $cs;
					$sum += $cs;

					if ($cs < $min) $min = $cs;
				}
				$average = $failedCount > 0 ? $dnf : round($sum / 3);

				$SimpleResult->single = $min;
				$SimpleResult->average = $average;
				$SimpleResult->results = implode("@", $allResults);
			} else { // Navadni avg3/5

				$results = explode("@", $SimpleResult->results);
				$allResults = array();
				$min = $dnf;
				$max = 0;
				$failedCount = 0;
				$sum = 0;
				if (count($results) < $attempts) return false;
				for ($i = 0; $i < $attempts; $i++) {
					$value = $results[$i];
					if ($value === "" || $value === null) return false;

					if (strcasecmp($value, "dnf") == 0 || $value == $dnf) {
						$cs = $dnf;
						$failedCount++;
					} else if (strcasecmp($value, "dns") == 0 || $value == $dns) {
						$cs = $dns;
						$failedCount++;
					} else if (strcasecmp($value, "dsq") == 0 || $value == $dsq) {
						$cs = $dsq;
						$failedCount++;
					} else {
						$cs = self::parseString($value);
						if ($cs === null) return false;
					}

					$allResults[] = $cs;

					$value = $value; // Pretvori v stotinke

					if ($cs < $min) $min = $cs;
					if ($cs > $max) $max = $cs;
					$sum += $cs;
				}
				$average = $failedCount > 1 ? $dnf : round(($sum - $min - $max) / 3);

				$SimpleResult->single = $min;
				$SimpleResult->average = $average;
				$SimpleResult->results = implode("@", $allResults);
			}


		});
	}

	public static function parseString($value)
	{
		if (false) {

		} else {
			// Parse 'm:ss.cs', 's.cs', 'cs'
			$pattern1 = "/^([0-9]+)[:.,]{1}([0-9]{2})[:.,]{1}([0-9]{2})$/";
			$pattern2 = "/^([0-9]+)[:.,]{1}([0-9]{2})$/";
			$pattern3 = "/^[0-9]+$/";
			$matches = array();
			if (preg_match($pattern1, $value, $matches) > 0) {
				$cs = ((int) $matches[1]) * 60 * 100 + ((int) $matches[2]) * 100 + (int) $matches[3];
			} else if (preg_match($pattern2, $value, $matches) > 0) {
				$cs = ((int) $matches[1] * 100 + ((int) $matches[2]));
			} else if (preg_match($pattern3, $value, $matches) > 0) {
				$cs = (int) $matches[0];
			} else {
				return null;
			}
			return $cs;
		}
	}

}
