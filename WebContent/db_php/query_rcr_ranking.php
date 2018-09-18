<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_player.php");
require_once ("query_database_table_rcr.php");
require_once ("query_rcr_ranking_config.php");
function getRCRRanking($tournamentId, $rankingMode, $sortingMode, $periodMode, $year, $trimester, $month) {
	$totalScores = array ();
	if ($periodMode !== null) {
		switch ($periodMode) {
			case PERIOD_MODE_ALL:
				$isPeriodSet = false;
				$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				break;
			case PERIOD_MODE_YEAR:
				if ($year !== null) {
					$isPeriodSet = true;
					$dateFrom = strval ($year) . "-01-01";
					$dateTo = strval ($year + 1) . "-01-01";
					$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				} else {
					$isPeriodSet = false;
					$minGamePlayed = MIN_GAME_PLAYED_YEAR;
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
					$minGamePlayed = MIN_GAME_PLAYED_TRIMESTER;
				} else {
					$isPeriodSet = false;
					$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				}
				break;
			case PERIOD_MODE_MONTH:
				if ($year !== null) {
					$isPeriodSet = true;
					$dateFrom = strval ($year) . "-" . strval ($month + 1) . "-01";
					if ($month === 11) {
						$dateTo = strval ($year + 1) . "-01-01";
					} else {
						$dateTo = strval ($year) . "-" . strval ($month + 2) . "-01";
					}
					$minGamePlayed = MIN_GAME_PLAYED_MONTH;
				} else {
					$isPeriodSet = false;
					$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				}
				break;
			default:
				$isPeriodSet = false;
				$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				break;
		}
	}

	if ($rankingMode !== null) {
		switch ($rankingMode) {
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_TOTAL:
				$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", SUM(" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryTournament = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME;
				if (USING_MIN_GAME) {
					$queryHaving = " HAVING COUNT(*)>=" . strval ($minGamePlayed);
				} else {
					$queryHaving = "";
				}
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_TOTAL . " ASC";
				} else {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_TOTAL . " DESC";
				}
				if ($isPeriodSet) {
					$parameters = array (
						$tournamentId,
						$dateFrom,
						$dateTo
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryDate . $queryGroup . $queryHaving . $queryOrder, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryGroup . $queryHaving . $queryOrder, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [TABLE_PLAYER_NAME];
					$totalScore [SCORE_TOTAL_YEAR] = 0;
					$totalScore [SCORE_TOTAL_MONTH] = 0;
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_TOTAL]);
					$totalScore [SCORE_TOTAL_UMA] = 0;
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_FINAL_SCORE:
				$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_UMA_SCORE;
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryTournament = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE_FINAL_SCORE . " ASC ";
				} else {
					$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE_FINAL_SCORE . " DESC ";
				}
				$queryLimit = " LIMIT " . RANKING_RESULT_LIMIT;
				if ($isPeriodSet) {
					$parameters = array (
						$tournamentId,
						$dateFrom,
						$dateTo
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryDate . $queryOrder . $queryLimit, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryOrder . $queryLimit, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [TABLE_PLAYER_NAME];
					$totalScore [SCORE_TOTAL_YEAR] = intval ($line [TABLE_VAR_YEAR]);
					$totalScore [SCORE_TOTAL_MONTH] = intval ($line [TABLE_VAR_MONTH]);
					$totalScore [SCORE_TOTAL_DAY] = intval ($line [TABLE_VAR_DAY]);
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_RCR_GAME_SCORE_FINAL_SCORE]);
					$totalScore [SCORE_TOTAL_UMA] = intval ($line [TABLE_RCR_GAME_SCORE_UMA_SCORE]);
					$totalScore [SCORE_TOTAL_NB_GAMES] = 0;
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_MEAN_FINAL_SCORE:
				$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", AVG(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_MEAN . ", STDDEV_POP(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_STDDEV . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryTournament = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME;
				if (USING_MIN_GAME) {
					$queryHaving = " HAVING COUNT(*)>=" . strval ($minGamePlayed);
				} else {
					$queryHaving = "";
				}
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_MEAN . " ASC";
				} else {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_MEAN . " DESC";
				}
				if ($isPeriodSet) {
					$parameters = array (
						$tournamentId,
						$dateFrom,
						$dateTo
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryDate . $queryGroup . $queryHaving . $queryOrder, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryGroup . $queryHaving . $queryOrder, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [TABLE_PLAYER_NAME];
					$totalScore [SCORE_TOTAL_YEAR] = 0;
					$totalScore [SCORE_TOTAL_MONTH] = 0;
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_MEAN]);
					$totalScore [SCORE_TOTAL_UMA] = intval ($line [TABLE_VAR_SCORE_STDDEV]);
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_GAME_SCORE:
				$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE;
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryTournament = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE_GAME_SCORE . " ASC";
				} else {
					$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE_GAME_SCORE . " DESC";
				}
				$queryLimit = " LIMIT " . RANKING_RESULT_LIMIT;
				if ($isPeriodSet) {
					$parameters = array (
						$tournamentId,
						$dateFrom,
						$dateTo
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryDate . $queryOrder . $queryLimit, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryOrder . $queryLimit, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [TABLE_PLAYER_NAME];
					$totalScore [SCORE_TOTAL_YEAR] = intval ($line [TABLE_VAR_YEAR]);
					$totalScore [SCORE_TOTAL_MONTH] = intval ($line [TABLE_VAR_MONTH]);
					$totalScore [SCORE_TOTAL_DAY] = intval ($line [TABLE_VAR_DAY]);
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_RCR_GAME_SCORE_GAME_SCORE]);
					$totalScore [SCORE_TOTAL_UMA] = 0;
					$totalScore [SCORE_TOTAL_NB_GAMES] = 0;
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_MEAN_GAME_SCORE:
				$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", AVG(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE . ") AS " . TABLE_VAR_SCORE_MEAN . ", STDDEV_POP(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE . ") AS " . TABLE_VAR_SCORE_STDDEV . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryTournament = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME;
				if (USING_MIN_GAME) {
					$queryHaving = " HAVING COUNT(*)>=" . strval ($minGamePlayed);
				} else {
					$queryHaving = "";
				}
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_MEAN . " ASC";
				} else {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_MEAN . " DESC";
				}
				if ($isPeriodSet) {
					$parameters = array (
						$tournamentId,
						$dateFrom,
						$dateTo
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryDate . $queryGroup . $queryHaving . $queryOrder, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryTournament . $queryGroup . $queryHaving . $queryOrder, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [TABLE_PLAYER_NAME];
					$totalScore [SCORE_TOTAL_YEAR] = 0;
					$totalScore [SCORE_TOTAL_MONTH] = 0;
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_MEAN]);
					$totalScore [SCORE_TOTAL_UMA] = intval ($line [TABLE_VAR_SCORE_STDDEV]);
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_TOTAL_ANNUAL:
				$querySelect = "SELECT " . TABLE_PLAYER_NAME . ", " . TABLE_VAR_YEAR . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				$queryFrom = " FROM (";
				$querySubSelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") as " . TABLE_VAR_YEAR . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE;
				$querySubFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$querySubWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$querySubTournament = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?) AS " . TABLE_VAR_YEAR;
				$queryGroup = " GROUP BY " . TABLE_PLAYER_NAME . ", " . TABLE_VAR_YEAR;
				if (USING_MIN_GAME) {
					$queryHaving = " HAVING COUNT(*)>=" . strval (MIN_GAME_PLAYED_YEAR);
				} else {
					$queryHaving = "";
				}
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_TOTAL . " ASC";
				} else {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_TOTAL . " DESC";
				}
				$queryLimit = " LIMIT " . RANKING_RESULT_LIMIT;
				$parameters = array (
					$tournamentId
				);
				$result = executeQuery ($querySelect . $queryFrom . $querySubSelect . $querySubFrom . $querySubWhere . $querySubTournament . $queryGroup . $queryHaving . $queryOrder . $queryLimit, $parameters);
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [TABLE_PLAYER_NAME];
					$totalScore [SCORE_TOTAL_YEAR] = intval ($line [TABLE_VAR_YEAR]);
					$totalScore [SCORE_TOTAL_MONTH] = 0;
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_TOTAL]);
					$totalScore [SCORE_TOTAL_UMA] = 0;
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_TOTAL_TRIMENSTRIAL:
				$querySelect = "SELECT " . TABLE_PLAYER_NAME . ", " . TABLE_VAR_YEAR . ", " . TABLE_VAR_TRIMESTER . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				$queryFrom = " FROM (";
				$querySubSelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") as " . TABLE_VAR_YEAR . ", (MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1) DIV 3 AS " . TABLE_VAR_TRIMESTER . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE;
				$querySubFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$querySubWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$querySubTournament = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?) AS " . TABLE_RCR_GAME_SCORE_TRIMESTER;
				$queryGroup = " GROUP BY " . TABLE_PLAYER_NAME . ", " . TABLE_VAR_YEAR . ", " . TABLE_VAR_TRIMESTER;
				if (USING_MIN_GAME) {
					$queryHaving = " HAVING COUNT(*)>=" . strval (MIN_GAME_PLAYED_TRIMESTER);
				} else {
					$queryHaving = "";
				}
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_TOTAL . " ASC";
				} else {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_TOTAL . " DESC";
				}
				$queryLimit = " LIMIT " . RANKING_RESULT_LIMIT;
				$parameters = array (
					$tournamentId
				);
				$result = executeQuery ($querySelect . $queryFrom . $querySubSelect . $querySubFrom . $querySubWhere . $querySubTournament . $queryGroup . $queryHaving . $queryOrder . $queryLimit, $parameters);
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [TABLE_PLAYER_NAME];
					$totalScore [SCORE_TOTAL_YEAR] = intval ($line [TABLE_VAR_YEAR]);
					$totalScore [SCORE_TOTAL_MONTH] = intval ($line [TABLE_VAR_TRIMESTER]);
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_TOTAL]);
					$totalScore [SCORE_TOTAL_UMA] = 0;
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_TOTAL_MENSUAL:
				$querySelect = "SELECT " . TABLE_PLAYER_NAME . ", " . TABLE_VAR_YEAR . ", " . TABLE_VAR_MONTH . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				$queryFrom = " FROM (";
				$querySubSelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") as " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_FINAL_SCORE;
				$querySubFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$querySubWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$querySubTournament = " AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?) AS " . TABLE_VAR_MONTH;
				$queryGroup = " GROUP BY " . TABLE_PLAYER_NAME . ", " . TABLE_VAR_YEAR . ", " . TABLE_VAR_MONTH;
				if (USING_MIN_GAME) {
					$queryHaving = " HAVING COUNT(*)>=" . strval (MIN_GAME_PLAYED_TRIMESTER);
				} else {
					$queryHaving = "";
				}
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_TOTAL . " ASC";
				} else {
					$queryOrder = " ORDER BY " . TABLE_VAR_SCORE_TOTAL . " DESC";
				}
				$queryLimit = " LIMIT " . RANKING_RESULT_LIMIT;
				$parameters = array (
					$tournamentId
				);
				$result = executeQuery ($querySelect . $queryFrom . $querySubSelect . $querySubFrom . $querySubWhere . $querySubTournament . $queryGroup . $queryHaving . $queryOrder . $queryLimit, $parameters);
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [TABLE_PLAYER_NAME];
					$totalScore [SCORE_TOTAL_YEAR] = intval ($line [TABLE_VAR_YEAR]);
					$totalScore [SCORE_TOTAL_MONTH] = intval ($line [TABLE_VAR_MONTH]);
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_TOTAL]);
					$totalScore [SCORE_TOTAL_UMA] = 0;
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
		}
	}
	return json_encode ($totalScores);
}
?>