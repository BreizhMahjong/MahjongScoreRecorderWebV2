<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_rcr.php");
require_once ("query_rcr_personal_analyze_config.php");
function getRCRPersonalAnalyze($tournamentId, $playerId, $scoreMode, $periodMode, $year, $trimester, $month, $day) {
	$analyzeData = array ();
	if ($periodMode !== null) {
		switch ($periodMode) {
			case PERIOD_MODE_ALL:
				$isPeriodSet = false;
				break;
			case PERIOD_MODE_YEAR:
				if ($year !== null) {
					$isPeriodSet = true;
					$dateFrom = new DateTime ();
					date_date_set ($dateFrom, $year, 1, 1);
					$dateTo = new DateTime ();
					date_date_set ($dateTo, $year, 1, 1);
					$interval = new DateInterval ("P1Y");
					date_add ($dateTo, $interval);
					$dateFromString = date_format ($dateFrom, "Y-m-d");
					$dateToString = date_format ($dateTo, "Y-m-d");
				} else {
					$isPeriodSet = false;
				}
				break;
			case PERIOD_MODE_SEASON:
				if ($year !== null) {
					$isPeriodSet = true;
					$dateFrom = date_create ();
					date_date_set ($dateFrom, $year, 9, 1);
					date_time_set ($dateFrom, 0, 0, 0);
					$dateTo = date_create ();
					date_date_set ($dateTo, $year, 9, 1);
					date_time_set ($dateTo, 0, 0, 0);
					$interval = new DateInterval ("P1Y");
					date_add ($dateTo, $interval);
					$dateFromString = date_format ($dateFrom, "Y-m-d");
					$dateToString = date_format ($dateTo, "Y-m-d");
					$minGamePlayed = intval (round (getProportionalPeriod ($dateFrom, $dateTo) * MIN_GAME_PLAYED_YEAR));
				} else {
					$isPeriodSet = false;
					$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				}
				break;
			case PERIOD_MODE_TRIMESTER:
				if ($year !== null && $trimester !== null) {
					$isPeriodSet = true;
					$dateFrom = new DateTime ();
					date_date_set ($dateFrom, $year, $trimester * 3 + 1, 1);
					$dateTo = new DateTime ();
					date_date_set ($dateTo, $year, $trimester * 3 + 1, 1);
					$interval = new DateInterval ("P3M");
					date_add ($dateTo, $interval);
					$dateFromString = date_format ($dateFrom, "Y-m-d");
					$dateToString = date_format ($dateTo, "Y-m-d");
				} else {
					$isPeriodSet = false;
				}
				break;
			case PERIOD_MODE_MONTH:
				if ($year !== null && $month !== null) {
					$isPeriodSet = true;
					$dateFrom = new DateTime ();
					date_date_set ($dateFrom, $year, $month + 1, 1);
					$dateTo = new DateTime ();
					date_date_set ($dateTo, $year, $month + 1, 1);
					$interval = new DateInterval ("P1M");
					date_add ($dateTo, $interval);
					$dateFromString = date_format ($dateFrom, "Y-m-d");
					$dateToString = date_format ($dateTo, "Y-m-d");
				} else {
					$isPeriodSet = false;
				}
				break;
			case PERIOD_MODE_DAY:
				if ($year !== null && $month !== null && $day !== null) {
					$isPeriodSet = true;
					$dateFrom = new DateTime ();
					date_date_set ($dateFrom, $year, $month + 1, $day);
					$dateTo = new DateTime ();
					date_date_set ($dateTo, $year, $month + 1, $day);
					$interval = new DateInterval ("P1D");
					date_add ($dateTo, $interval);
					$dateFromString = date_format ($dateFrom, "Y-m-d");
					$dateToString = date_format ($dateTo, "Y-m-d");
				} else {
					$isPeriodSet = false;
				}
				break;
			default:
				$isPeriodSet = false;
				break;
		}
	}

	switch ($scoreMode) {
		case ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_SCORE_MODE_FINAL_SCORE:
			$field = TABLE_RCR_GAME_SCORE_FINAL_SCORE;
			break;
		case ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_SCORE_MODE_ABS_SCORE:
			$field = TABLE_RCR_GAME_SCORE_GAME_SCORE . "-30000";
			break;
		case ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_SCORE_MODE_GAME_SCORE:
			$field = TABLE_RCR_GAME_SCORE_GAME_SCORE;
			break;
	}

	if ($playerId !== null) {
		$querySelect = "SELECT " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . ", " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . ", " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_RANKING . ", " . TABLE_RCR_GAME_SCORE . DOT . $field . " AS " . TABLE_VAR_SCORE_SCORE . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ")-1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY;
		$queryFrom = " FROM " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
		$queryWhere = " WHERE " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID . " AND " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . "=?";
		$queryTournament = " AND " . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
		$queryDate = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
		$queryOrder = " ORDER BY " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . " ASC";
		if ($isPeriodSet) {
			$parameters = array (
				$playerId,
				$tournamentId,
				$dateFromString,
				$dateToString
			);
			$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryDate . $queryOrder, $parameters);
		} else {
			$parameters = array (
				$playerId,
				$tournamentId
			);
			$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryOrder, $parameters);
		}

		$numberOfGames = count ($result);
		$listDate = array ();
		$listScore = array ();

		$numberOfPositiveGames = 0;
		$numberOfNegativeGames = 0;

		$totalScore = 0;
		$meanScore = 0.0;
		$stdev = 0.0;
		$maxScore = 0;
		$minScore = 0;
		$positiveTotal = 0;
		$negativeTotal = 0;

		$numberOfFourPlayerGames = 0;
		$numberOfFivePlayerGames = 0;
		$placeFourPlayers = array (
			0,
			0,
			0,
			0
		);
		$placeFourPlayersPercent = array (
			0,
			0,
			0,
			0
		);
		$placeFivePlayers = array (
			0,
			0,
			0,
			0,
			0
		);
		$placeFivePlayersPercent = array (
			0,
			0,
			0,
			0,
			0
		);

		for ($index = 0; $index < $numberOfGames; $index++) {
			$line = $result [$index];
			$nbPlayers = intval ($line [TABLE_RCR_GAME_ID_NB_PLAYERS]);
			$ranking = intval ($line [TABLE_RCR_GAME_SCORE_RANKING]);
			$score = intval ($line [TABLE_VAR_SCORE_SCORE]);

			$listScore [] = $score;
			if ($score >= 0) {
				$numberOfPositiveGames++;
				$positiveTotal += $score;
			} else {
				$numberOfNegativeGames++;
				$negativeTotal += $score;
			}

			if ($index === 0 or $score > $maxScore) {
				$maxScore = $score;
			}
			if ($index === 0 or $score < $minScore) {
				$minScore = $score;
			}

			if ($nbPlayers === 4) {
				$placeFourPlayers [$ranking - 1]++;
				$numberOfFourPlayerGames++;
			} else if ($nbPlayers === 5) {
				$placeFivePlayers [$ranking - 1]++;
				$numberOfFivePlayerGames++;
			}

			$totalScore += $score;

			$date = array ();
			$date [RCR_GAME_ID] = intval ($line [TABLE_RCR_GAME_ID_ID]);
			$date [RCR_GAME_YEAR] = intval ($line [TABLE_VAR_YEAR]);
			$date [RCR_GAME_MONTH] = intval ($line [TABLE_VAR_MONTH]);
			$date [RCR_GAME_DAY] = intval ($line [TABLE_VAR_DAY]);
			$listDate [] = $date;
		}

		$meanScore = $numberOfGames > 0 ? floatval ($totalScore) / floatval ($numberOfGames) : 0;
		$deviation = 0.0;
		for ($index = 0; $index < $numberOfGames; $index++) {
			$deviation += pow ($listScore [$index] - $meanScore, 2.0);
		}
		$stdev = $numberOfGames > 1 ? intval (round (sqrt ($deviation / $numberOfGames))) : 0;

		if ($numberOfFourPlayerGames > 0) {
			for ($index = 0; $index < 4; $index++) {
				$placeFourPlayersPercent [$index] = intval (round (floatval ($placeFourPlayers [$index]) * 100.0 / $numberOfFourPlayerGames));
			}
		}

		if ($numberOfFivePlayerGames > 0) {
			for ($index = 0; $index < 5; $index++) {
				$placeFivePlayersPercent [$index] = intval (round (floatval ($placeFivePlayers [$index]) * 100.0 / $numberOfFivePlayerGames));
			}
		}

		$analyzeData [RCR_PERSONAL_ANALYZE_NB_GAMES] = $numberOfGames;
		$analyzeData [RCR_PERSONAL_ANALYZE_SCORE_MAX] = $maxScore;
		$analyzeData [RCR_PERSONAL_ANALYZE_SCORE_MIN] = $minScore;
		$analyzeData [RCR_PERSONAL_ANALYZE_POSITIVE] = $numberOfPositiveGames;
		$analyzeData [RCR_PERSONAL_ANALYZE_POSITIVE_PERCENTAGE] = $numberOfGames > 0 ? intval (round (floatval ($numberOfPositiveGames) * 100.0 / $numberOfGames)) : 0;
		$analyzeData [RCR_PERSONAL_ANALYZE_NEGATIVE] = $numberOfNegativeGames;
		$analyzeData [RCR_PERSONAL_ANALYZE_NEGATIVE_PERCENTAGE] = $numberOfGames > 0 ? intval (round (floatval ($numberOfNegativeGames) * 100.0 / $numberOfGames)) : 0;
		$analyzeData [RCR_PERSONAL_ANALYZE_SCORE_TOTAL] = $totalScore;
		$analyzeData [RCR_PERSONAL_ANALYZE_SCORE_MEAN] = intval (round ($meanScore));
		$analyzeData [RCR_PERSONAL_ANALYZE_SCORE_STDEV] = $stdev;
		$analyzeData [RCR_PERSONAL_ANALYZE_POSITIVE_TOTAL] = $positiveTotal;
		$analyzeData [RCR_PERSONAL_ANALYZE_NEGATIVE_TOTAL] = $negativeTotal;
		$analyzeData [RCR_PERSONAL_ANALYZE_FOUR_PLAYERS_GAMES] = $numberOfFourPlayerGames;
		$analyzeData [RCR_PERSONAL_ANALYZE_FOUR_PLAYERS_GAMES_PLACES] = $placeFourPlayers;
		$analyzeData [RCR_PERSONAL_ANALYZE_FOUR_PLAYERS_GAMES_PLACES_PERCENTAGE] = $placeFourPlayersPercent;
		$analyzeData [RCR_PERSONAL_ANALYZE_FIVE_PLAYERS_GAMES] = $numberOfFivePlayerGames;
		$analyzeData [RCR_PERSONAL_ANALYZE_FIVE_PLAYERS_GAMES_PLACES] = $placeFivePlayers;
		$analyzeData [RCR_PERSONAL_ANALYZE_FIVE_PLAYERS_GAMES_PLACES_PERCENTAGE] = $placeFivePlayersPercent;
		$analyzeData [RCR_PERSONAL_ANALYZE_LIST_DATE] = $listDate;
		$analyzeData [RCR_PERSONAL_ANALYZE_LIST_SCORE] = $listScore;
	}
	return json_encode ($analyzeData);
}
?>