<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_rcr.php");
require_once ("query_rcr_analyze_config.php");
function getRCRAnalyze($tournamentId, $playerId, $scoreMode, $periodMode, $year, $trimester, $month) {
	$analyzeData = array ();
	if ($periodMode !== null) {
		switch ($periodMode) {
			case PERIOD_MODE_ALL:
				$isPeriodSet = false;
				break;
			case PERIOD_MODE_YEAR:
				if ($year !== null) {
					$isPeriodSet = true;
					$dateFrom = strval ($year) . "-01-01";
					$dateTo = strval ($year + 1) . "-01-01";
				} else {
					$isPeriodSet = false;
				}
				break;
			case PERIOD_MODE_TRIMESTER:
				if ($year !== null && $trimester !== null) {
					$isPeriodSet = true;
					$dateFrom = strval ($year) . "-" . strval ($trimester * 3 + 1) . "-01";
					if ($trimester === 3) {
						$dateTo = strval ($year + 1) . "-01-01";
					} else {
						$dateTo = strval ($year) . "-" . strval (($trimester + 1) * 3 + 1) . "-01";
					}
				} else {
					$isPeriodSet = false;
				}
				break;
			case PERIOD_MODE_MONTH:
				if ($year !== null) {
					$isPeriodSet = true;
					$dateFrom = strval ($year) . "-" . strval ($month + 1) . "-01";
					if ($trimester === 11) {
						$dateTo = strval ($year + 1) . "-01-01";
					} else {
						$dateTo = strval ($year) . "-" . strval ($month + 2) . "-01";
					}
				} else {
					$isPeriodSet = false;
				}
				break;
			default:
				$isPeriodSet = false;
				break;
		}
	}
	
	if ($scoreMode === ACTION_GET_RCR_ANALYZE_PARAM_SCORE_MODE_GAME_SCORE) {
		$field = TABLE_RCR_GAME_SCORE_GAME_SCORE;
	} else {
		$field = TABLE_RCR_GAME_SCORE_FINAL_SCORE;
	}
	
	if ($playerId !== null) {
		$querySelect = "SELECT " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . ", " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . ", " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_RANKING . ", " . TABLE_RCR_GAME_SCORE . DOT . $field . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ")-1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY;
		$queryFrom = " FROM " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
		$queryWhere = " WHERE " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID . " AND " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . "=?";
		$queryTournament = " AND " . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
		$queryDate = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
		$queryOrder = " ORDER BY " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . " ASC";
		if ($isPeriodSet) {
			$parameters = array (
				$playerId,
				$tournamentId,
				$dateFrom,
				$dateTo
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
		$listSum = array ();
		
		$numberOfPositiveGames = 0;
		$numberOfNegativeGames = 0;
		
		$totalScore = 0;
		$meanScore = 0.0;
		$stdev = 0.0;
		$maxScore = 0;
		$minScore = 0;
		$maxTotal = 0;
		$minTotal = 0;
		
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
		
		for ($index = 0; $index < $numberOfGames; $index ++) {
			$line = $result [$index];
			$nbPlayers = intval ($line [TABLE_RCR_GAME_ID_NB_PLAYERS]);
			$ranking = intval ($line [TABLE_RCR_GAME_SCORE_RANKING]);
			$score = intval ($line [$field]);
			
			$listScore [] = $score;
			if ($score >= 0) {
				$numberOfPositiveGames ++;
			} else {
				$numberOfNegativeGames ++;
			}
			
			if ($index === 0 or $score > $maxScore) {
				$maxScore = $score;
			}
			if ($index === 0 or $score < $minScore) {
				$minScore = $score;
			}
			
			if ($nbPlayers === 4) {
				$placeFourPlayers [$ranking - 1] ++;
				$numberOfFourPlayerGames ++;
			} else if ($nbPlayers === 5) {
				$placeFivePlayers [$ranking - 1] ++;
				$numberOfFivePlayerGames ++;
			}
			
			$totalScore += $score;
			$listSum [] = $totalScore;
			if ($index === 0 or $totalScore > $maxTotal) {
				$maxTotal = $totalScore;
			}
			if ($index === 0 or $totalScore < $minTotal) {
				$minTotal = $totalScore;
			}
			
			$date = array ();
			$date [RCR_GAME_ID] = intval ($line [TABLE_RCR_GAME_ID_ID]);
			$date [RCR_GAME_YEAR] = intval ($line [TABLE_VAR_YEAR]);
			$date [RCR_GAME_MONTH] = intval ($line [TABLE_VAR_MONTH]);
			$date [RCR_GAME_DAY] = intval ($line [TABLE_VAR_DAY]);
			$listDate [] = $date;
		}
		
		$meanScore = $numberOfGames > 0 ? floatval ($totalScore) / floatval ($numberOfGames) : 0;
		$deviation = 0.0;
		for ($index = 0; $index < $numberOfGames; $index ++) {
			$deviation += pow ($listScore [$index] - $meanScore, 2.0);
		}
		$stdev = $numberOfGames > 1 ? intval (round (sqrt ($deviation / $numberOfGames))) : 0;
		
		if ($numberOfFourPlayerGames > 0) {
			for ($index = 0; $index < 4; $index ++) {
				$placeFourPlayersPercent [$index] = intval (round (floatval ($placeFourPlayers [$index]) * 100.0 / $numberOfFourPlayerGames));
			}
		}
		
		if ($numberOfFivePlayerGames > 0) {
			for ($index = 0; $index < 5; $index ++) {
				$placeFivePlayersPercent [$index] = intval (round (floatval ($placeFivePlayers [$index]) * 100.0 / $numberOfFivePlayerGames));
			}
		}
		
		$analyzeData [RCR_ANALYZE_NB_GAMES] = $numberOfGames;
		$analyzeData [RCR_ANALYZE_SCORE_MAX] = $maxScore;
		$analyzeData [RCR_ANALYZE_SCORE_MIN] = $minScore;
		$analyzeData [RCR_ANALYZE_POSITIVE] = $numberOfPositiveGames;
		$analyzeData [RCR_ANALYZE_POSITIVE_PERCENTAGE] = $numberOfGames > 0 ? intval (round (floatval ($numberOfPositiveGames) * 100.0 / $numberOfGames)) : 0;
		$analyzeData [RCR_ANALYZE_NEGATIVE] = $numberOfNegativeGames;
		$analyzeData [RCR_ANALYZE_NEGATIVE_PERCENTAGE] = $numberOfGames > 0 ? intval (round (floatval ($numberOfNegativeGames) * 100.0 / $numberOfGames)) : 0;
		$analyzeData [RCR_ANALYZE_SCORE_TOTAL] = $totalScore;
		$analyzeData [RCR_ANALYZE_SCORE_MEAN] = intval (round ($meanScore));
		$analyzeData [RCR_ANALYZE_SCORE_STDEV] = $stdev;
		$analyzeData [RCR_ANALYZE_TOTAL_MAX] = $maxTotal;
		$analyzeData [RCR_ANALYZE_TOTAL_MIN] = $minTotal;
		$analyzeData [RCR_ANALYZE_FOUR_PLAYERS_GAMES] = $numberOfFourPlayerGames;
		$analyzeData [RCR_ANALYZE_FOUR_PLAYERS_GAMES_PLACES] = $placeFourPlayers;
		$analyzeData [RCR_ANALYZE_FOUR_PLAYERS_GAMES_PLACES_PERCENTAGE] = $placeFourPlayersPercent;
		$analyzeData [RCR_ANALYZE_FIVE_PLAYERS_GAMES] = $numberOfFivePlayerGames;
		$analyzeData [RCR_ANALYZE_FIVE_PLAYERS_GAMES_PLACES] = $placeFivePlayers;
		$analyzeData [RCR_ANALYZE_FIVE_PLAYERS_GAMES_PLACES_PERCENTAGE] = $placeFivePlayersPercent;
		$analyzeData [RCR_ANALYZE_LIST_DATE] = $listDate;
		$analyzeData [RCR_ANALYZE_LIST_SCORE] = $listScore;
		$analyzeData [RCR_ANALYZE_LIST_SUM] = $listSum;
	}
	return json_encode ($analyzeData);
}
?>