<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_rcr.php");
require_once ("query_rcr_score_analyze_config.php");
function getRCRScoreAnalyze($tournamentId, $periodMode, $year, $trimester, $month, $day) {
	session_start();
	$isAdmin = isset($_SESSION[SESSION_IS_ADMIN]) ? $_SESSION[SESSION_IS_ADMIN] : false;

	$analyzeData = array ();
	if($periodMode !== null) {
		switch($periodMode) {
			case PERIOD_MODE_ALL:
				$isPeriodSet = false;
				break;
			case PERIOD_MODE_YEAR:
				if($year !== null) {
					$isPeriodSet = true;
					$dateFrom = new DateTime();
					date_date_set($dateFrom, $year, 1, 1);
					$dateTo = new DateTime();
					date_date_set($dateTo, $year, 1, 1);
					$interval = new DateInterval("P1Y");
					date_add($dateTo, $interval);
					$dateFromString = date_format($dateFrom, "Y-m-d");
					$dateToString = date_format($dateTo, "Y-m-d");
				} else {
					$isPeriodSet = false;
				}
				break;
			case PERIOD_MODE_SEASON:
				if($year !== null) {
					$isPeriodSet = true;
					$dateFrom = date_create();
					date_date_set($dateFrom, $year, 9, 1);
					date_time_set($dateFrom, 0, 0, 0);
					$dateTo = date_create();
					date_date_set($dateTo, $year, 9, 1);
					date_time_set($dateTo, 0, 0, 0);
					$interval = new DateInterval("P1Y");
					date_add($dateTo, $interval);
					$dateFromString = date_format($dateFrom, "Y-m-d");
					$dateToString = date_format($dateTo, "Y-m-d");
					$minGamePlayed = intval(round(getProportionalPeriod($dateFrom, $dateTo) * MIN_GAME_PLAYED_YEAR));
				} else {
					$isPeriodSet = false;
					$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				}
				break;
			case PERIOD_MODE_TRIMESTER:
				if($year !== null && $trimester !== null) {
					$isPeriodSet = true;
					$dateFrom = new DateTime();
					date_date_set($dateFrom, $year, $trimester * 3 + 1, 1);
					$dateTo = new DateTime();
					date_date_set($dateTo, $year, $trimester * 3 + 1, 1);
					$interval = new DateInterval("P3M");
					date_add($dateTo, $interval);
					$dateFromString = date_format($dateFrom, "Y-m-d");
					$dateToString = date_format($dateTo, "Y-m-d");
				} else {
					$isPeriodSet = false;
				}
				break;
			case PERIOD_MODE_MONTH:
				if($year !== null && $month !== null) {
					$isPeriodSet = true;
					$dateFrom = new DateTime();
					date_date_set($dateFrom, $year, $month + 1, 1);
					$dateTo = new DateTime();
					date_date_set($dateTo, $year, $month + 1, 1);
					$interval = new DateInterval("P1M");
					date_add($dateTo, $interval);
					$dateFromString = date_format($dateFrom, "Y-m-d");
					$dateToString = date_format($dateTo, "Y-m-d");
				} else {
					$isPeriodSet = false;
				}
				break;
			case PERIOD_MODE_DAY:
				if($year !== null && $month !== null && $day !== null) {
					$isPeriodSet = true;
					$dateFrom = new DateTime();
					date_date_set($dateFrom, $year, $month + 1, $day);
					$dateTo = new DateTime();
					date_date_set($dateTo, $year, $month + 1, $day);
					$interval = new DateInterval("P1D");
					date_add($dateTo, $interval);
					$dateFromString = date_format($dateFrom, "Y-m-d");
					$dateToString = date_format($dateTo, "Y-m-d");
				} else {
					$isPeriodSet = false;
				}
				break;
			default:
				$isPeriodSet = false;
				break;
		}
	}

	$playerNames = array ();
	$mapPlayerIdToIndex = array ();
	{
		if($isAdmin) {
			$nameField = TABLE_PLAYER_REAL_NAME;
			$querySelect = "SELECT DISTINCT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME;
		} else {
			$nameField = TABLE_PLAYER_NAME;
			$querySelect = "SELECT DISTINCT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME;
		}
		$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
		$queryWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
		$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
		$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
		$queryOrder = " ORDER BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . " ASC";
		if($isPeriodSet) {
			$parameters = array (
				$tournamentId,
				$dateFromString,
				$dateToString
			);
			$result = executeQuery($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryDate . $queryOrder, $parameters);
		} else {
			$parameters = array (
				$tournamentId
			);
			$result = executeQuery($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryOrder, $parameters);
		}

		foreach($result as $line) {
			$mapPlayerIdToIndex[$line[TABLE_PLAYER_ID]] = count($playerNames);
			$playerNames[] = $line[$nameField];
		}
	}
	$scores = array ();
	$sums = array ();
	$positiveSums = array();
	$negativeSums = array();
	$nbPlayers = count($playerNames);
	for($x = 0; $x < $nbPlayers; $x++) {
		$sums[$x] = 0;
		$positiveSums[$x] = 0;
		$negativeSums[$x] = 0;
		for($y = 0; $y < $nbPlayers; $y++) {
			$scores[$x][$y] = 0;
		}
	}

	$gameIDs = array ();
	{
		$querySelect = "SELECT " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID;
		$queryFrom = " FROM " . TABLE_RCR_GAME_ID;
		$queryWhere = " WHERE ";
		$queryTournament = TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
		$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
		if($isPeriodSet) {
			$parameters = array (
				$tournamentId,
				$dateFromString,
				$dateToString
			);
			$result = executeQuery($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryDate, $parameters);
		} else {
			$parameters = array (
				$tournamentId
			);
			$result = executeQuery($querySelect . $queryFrom . $queryWhere . $queryTournament, $parameters);
		}

		foreach($result as $line) {
			$gameIDs[] = $line[TABLE_RCR_GAME_ID_ID];
		}
	}

	{
		$querySelect = "SELECT " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . ", " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_SCORE . " - 30000 AS " . TABLE_VAR_SCORE_SCORE;
		$queryFrom = " FROM " . TABLE_RCR_GAME_SCORE;
		$queryWhere = " WHERE " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID . "=?";
		$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_SCORE . " DESC";

		foreach($gameIDs as $gameID) {
			$playerIDGame = array ();
			$playerScoreGame = array ();

			$parameters = array (
				$gameID
			);
			$result = executeQuery($querySelect . $queryFrom . $queryWhere . $queryOrder, $parameters);
			foreach($result as $line) {
				$playerIDGame[] = $line[TABLE_RCR_GAME_SCORE_PLAYER_ID];
				$playerScoreGame[] = intval($line[TABLE_VAR_SCORE_SCORE]);
			}

			$nbPlayers = count($playerIDGame);
			$scoreIndex = 0;
			$totalPositive = 0.0;
			while($scoreIndex < $nbPlayers && $playerScoreGame[$scoreIndex] > 0) {
				$totalPositive += $playerScoreGame[$scoreIndex];
				$scoreIndex++;
			}

			if($totalPositive > 0) {
				$nbPositives = $scoreIndex;
				while($scoreIndex < $nbPlayers && $playerScoreGame[$scoreIndex] == 0) {
					$scoreIndex++;
				}
				while($scoreIndex < $nbPlayers) {
					$playerNegativeIndex = $mapPlayerIdToIndex[$playerIDGame[$scoreIndex]];
					for($positiveIndex = 0; $positiveIndex < $nbPositives; $positiveIndex++) {
						$scorePart = -$playerScoreGame[$scoreIndex] * $playerScoreGame[$positiveIndex] / $totalPositive;
						$playerPositiveIndex = $mapPlayerIdToIndex[$playerIDGame[$positiveIndex]];
						$scores[$playerPositiveIndex][$playerNegativeIndex] += $scorePart;
						$scores[$playerNegativeIndex][$playerPositiveIndex] -= $scorePart;
						$sums[$playerPositiveIndex] += $scorePart;
						$positiveSums[$playerPositiveIndex] += $scorePart;
						$sums[$playerNegativeIndex] -= $scorePart;
						$negativeSums[$playerNegativeIndex] -= $scorePart;
					}
					$scoreIndex++;
				}
			}
		}

		$nbPlayers = count($playerNames);
		for($x = 0; $x < $nbPlayers; $x++) {
			for($y = 0; $y < $nbPlayers; $y++) {
				$scores[$x][$y] = intval(round($scores[$x][$y]));
			}
			$sums[$x] = intval(round($sums[$x]));
			$positiveSums[$x] = intval(round($positiveSums[$x]));
			$negativeSums[$x] = intval(round($negativeSums[$x]));
		}
	}

	$analyzeData[RCR_SCORE_ANALYZE_PLAYERS] = $playerNames;
	$analyzeData[RCR_SCORE_ANALYZE_SCORES] = $scores;
	$analyzeData[RCR_SCORE_ANALYZE_SUMS] = $sums;
	$analyzeData[RCR_SCORE_ANALYZE_POSITIVE_SUMS] = $positiveSums;
	$analyzeData[RCR_SCORE_ANALYZE_NEGATIVE_SUMS] = $negativeSums;

	return json_encode($analyzeData);
}
?>