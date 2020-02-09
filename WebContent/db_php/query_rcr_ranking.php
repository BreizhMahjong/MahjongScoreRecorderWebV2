<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_player.php");
require_once ("query_database_table_rcr.php");
require_once ("query_rcr_ranking_config.php");
function getRCRRanking($tournamentId, $rankingMode, $sortingMode, $periodMode, $year, $trimester, $month, $day, $useMinGames) {
	session_start ();
	$isAdmin = isset ($_SESSION [SESSION_IS_ADMIN]) ? $_SESSION [SESSION_IS_ADMIN] : false;

	$totalScores = array ();
	if ($periodMode !== null) {
		switch ($periodMode) {
			case PERIOD_MODE_ALL:
				$isPeriodSet = false;
				if ($useMinGames) {
					$minGamePlayed = intval (round (getNumberOfYearOfAllGamePeriod ($tournamentId) * MIN_GAME_PLAYED_YEAR));
				} else {
					$minGamePlayed = 0;
				}
				break;
			case PERIOD_MODE_YEAR:
				if ($year !== null) {
					$isPeriodSet = true;
					$dateFrom = date_create ();
					date_date_set ($dateFrom, $year, 1, 1);
					date_time_set ($dateFrom, 0, 0, 0);
					$dateTo = date_create ();
					date_date_set ($dateTo, $year, 1, 1);
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
					$dateFrom = date_create ();
					date_date_set ($dateFrom, $year, $trimester * 3 + 1, 1);
					date_time_set ($dateFrom, 0, 0, 0);
					$dateTo = date_create ();
					date_date_set ($dateTo, $year, $trimester * 3 + 1, 1);
					date_time_set ($dateTo, 0, 0, 0);
					$interval = new DateInterval ("P3M");
					date_add ($dateTo, $interval);
					$dateFromString = date_format ($dateFrom, "Y-m-d");
					$dateToString = date_format ($dateTo, "Y-m-d");
					$minGamePlayed = intval (round (getProportionalPeriod ($dateFrom, $dateTo) * MIN_GAME_PLAYED_TRIMESTER));
				} else {
					$isPeriodSet = false;
					$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				}
				break;
			case PERIOD_MODE_MONTH:
				if ($year !== null && $month !== null) {
					$isPeriodSet = true;
					$dateFrom = date_create ();
					date_date_set ($dateFrom, $year, $month + 1, 1);
					date_time_set ($dateFrom, 0, 0, 0);
					$dateTo = date_create ();
					date_date_set ($dateTo, $year, $month + 1, 1);
					date_time_set ($dateTo, 0, 0, 0);
					$interval = new DateInterval ("P1M");
					date_add ($dateTo, $interval);
					$dateFromString = date_format ($dateFrom, "Y-m-d");
					$dateToString = date_format ($dateTo, "Y-m-d");
					$minGamePlayed = intval (round (getProportionalPeriod ($dateFrom, $dateTo) * MIN_GAME_PLAYED_MONTH));
				} else {
					$isPeriodSet = false;
					$minGamePlayed = MIN_GAME_PLAYED_YEAR;
				}
				break;
			case PERIOD_MODE_DAY:
				if ($year !== null && $month !== null && $day !== null) {
					$isPeriodSet = true;
					$dateFrom = date_create ();
					date_date_set ($dateFrom, $year, $month + 1, $day);
					date_time_set ($dateFrom, 0, 0, 0);
					$dateTo = date_create ();
					date_date_set ($dateTo, $year, $month + 1, $day);
					date_time_set ($dateTo, 0, 0, 0);
					$interval = new DateInterval ("P1D");
					date_add ($dateTo, $interval);
					$dateFromString = date_format ($dateFrom, "Y-m-d");
					$dateToString = date_format ($dateTo, "Y-m-d");
					$minGamePlayed = MIN_GAME_PLAYED_DAY;
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
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_TOTAL_FINAL_SCORE:
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", SUM(" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", SUM(" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . $nameField;
				if ($useMinGames) {
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
						$dateFromString,
						$dateToString
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryDate . $queryGroup . $queryHaving . $queryOrder, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryGroup . $queryHaving . $queryOrder, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
					$totalScore [SCORE_TOTAL_YEAR] = 0;
					$totalScore [SCORE_TOTAL_MONTH] = 0;
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_TOTAL]);
					$totalScore [SCORE_TOTAL_UMA] = 0;
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_MEAN_FINAL_SCORE:
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", AVG(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_MEAN . ", STDDEV_POP(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_STDDEV . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", AVG(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_MEAN . ", STDDEV_POP(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_STDDEV . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . $nameField;
				if ($useMinGames) {
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
						$dateFromString,
						$dateToString
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryDate . $queryGroup . $queryHaving . $queryOrder, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryGroup . $queryHaving . $queryOrder, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
					$totalScore [SCORE_TOTAL_YEAR] = 0;
					$totalScore [SCORE_TOTAL_MONTH] = 0;
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_MEAN]);
					$totalScore [SCORE_TOTAL_UMA] = intval ($line [TABLE_VAR_SCORE_STDDEV]);
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_BEST_FINAL_SCORE:
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_UMA_SCORE;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_UMA_SCORE;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE_FINAL_SCORE . " ASC ";
				} else {
					$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE_FINAL_SCORE . " DESC ";
				}
				$queryLimit = " LIMIT " . RANKING_RESULT_LIMIT;
				if ($isPeriodSet) {
					$parameters = array (
						$tournamentId,
						$dateFromString,
						$dateToString
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryTournament . $queryDate . $queryOrder . $queryLimit, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryTournament . $queryOrder . $queryLimit, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
					$totalScore [SCORE_TOTAL_YEAR] = intval ($line [TABLE_VAR_YEAR]);
					$totalScore [SCORE_TOTAL_MONTH] = intval ($line [TABLE_VAR_MONTH]);
					$totalScore [SCORE_TOTAL_DAY] = intval ($line [TABLE_VAR_DAY]);
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_RCR_GAME_SCORE_FINAL_SCORE]);
					$totalScore [SCORE_TOTAL_UMA] = intval ($line [TABLE_RCR_GAME_SCORE_UMA_SCORE]);
					$totalScore [SCORE_TOTAL_NB_GAMES] = 0;
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_TOTAL_GAME_SCORE:
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", SUM(" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", SUM(" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . $nameField;
				if ($useMinGames) {
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
						$dateFromString,
						$dateToString
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryDate . $queryGroup . $queryHaving . $queryOrder, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryGroup . $queryHaving . $queryOrder, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
					$totalScore [SCORE_TOTAL_YEAR] = 0;
					$totalScore [SCORE_TOTAL_MONTH] = 0;
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_TOTAL]);
					$totalScore [SCORE_TOTAL_UMA] = 0;
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_MEAN_GAME_SCORE:
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", AVG(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE . ") AS " . TABLE_VAR_SCORE_MEAN . ", STDDEV_POP(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE . ") AS " . TABLE_VAR_SCORE_STDDEV . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", AVG(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE . ") AS " . TABLE_VAR_SCORE_MEAN . ", STDDEV_POP(" . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE . ") AS " . TABLE_VAR_SCORE_STDDEV . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . $nameField;
				if ($useMinGames) {
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
						$dateFromString,
						$dateToString
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryDate . $queryGroup . $queryHaving . $queryOrder, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryGroup . $queryHaving . $queryOrder, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
					$totalScore [SCORE_TOTAL_YEAR] = 0;
					$totalScore [SCORE_TOTAL_MONTH] = 0;
					$totalScore [SCORE_TOTAL_DAY] = 0;
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_VAR_SCORE_MEAN]);
					$totalScore [SCORE_TOTAL_UMA] = intval ($line [TABLE_VAR_SCORE_STDDEV]);
					$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_BEST_GAME_SCORE:
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY . ", " . TABLE_RCR_GAME_SCORE . "." . TABLE_RCR_GAME_SCORE_GAME_SCORE;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
				$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE_GAME_SCORE . " ASC";
				} else {
					$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE_GAME_SCORE . " DESC";
				}
				$queryLimit = " LIMIT " . RANKING_RESULT_LIMIT;
				if ($isPeriodSet) {
					$parameters = array (
						$tournamentId,
						$dateFromString,
						$dateToString
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryTournament . $queryDate . $queryOrder . $queryLimit, $parameters);
				} else {
					$parameters = array (
						$tournamentId
					);
					$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryTournament . $queryOrder . $queryLimit, $parameters);
				}
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
					$totalScore [SCORE_TOTAL_YEAR] = intval ($line [TABLE_VAR_YEAR]);
					$totalScore [SCORE_TOTAL_MONTH] = intval ($line [TABLE_VAR_MONTH]);
					$totalScore [SCORE_TOTAL_DAY] = intval ($line [TABLE_VAR_DAY]);
					$totalScore [SCORE_TOTAL_SCORE] = intval ($line [TABLE_RCR_GAME_SCORE_GAME_SCORE]);
					$totalScore [SCORE_TOTAL_UMA] = 0;
					$totalScore [SCORE_TOTAL_NB_GAMES] = 0;
					$totalScores [] = $totalScore;
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_WIN_RATE_4:
				$playerIndexMap = array ();
				{
					if ($isAdmin) {
						$nameField = TABLE_PLAYER_REAL_NAME;
						$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					} else {
						$nameField = TABLE_PLAYER_NAME;
						$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					}
					$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
					$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
					$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
					$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
					$queryNbPlayers = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . "=4";
					$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
					$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID;
					if ($useMinGames) {
						$queryHaving = " HAVING COUNT(*)>=" . strval ($minGamePlayed);
					} else {
						$queryHaving = "";
					}
					if ($isPeriodSet) {
						$parameters = array (
							$tournamentId,
							$dateFromString,
							$dateToString
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryNbPlayers . $queryDate . $queryGroup . $queryHaving, $parameters);
					} else {
						$parameters = array (
							$tournamentId
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryNbPlayers . $queryGroup . $queryHaving, $parameters);
					}
					foreach ($result as $line) {
						$totalScore = array ();
						$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
						$totalScore [SCORE_TOTAL_YEAR] = 0;
						$totalScore [SCORE_TOTAL_MONTH] = 0;
						$totalScore [SCORE_TOTAL_DAY] = 0;
						$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
						$totalScore [SCORE_TOTAL_SCORE] = 0;
						$totalScore [SCORE_TOTAL_UMA] = 0.0;

						$playerIndexMap [$line [TABLE_PLAYER_ID]] = count ($totalScores);
						$totalScores [] = $totalScore;
					}
				}
				{
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
					$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
					$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
					$queryScore = SQL_AND . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_RANKING . "=1";
					$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
					$queryNbPlayers = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . "=4";
					$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
					$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID;
					if ($isPeriodSet) {
						$parameters = array (
							$tournamentId,
							$dateFromString,
							$dateToString
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryScore . $queryTournament . $queryNbPlayers . $queryDate . $queryGroup, $parameters);
					} else {
						$parameters = array (
							$tournamentId
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryScore . $queryTournament . $queryNbPlayers . $queryGroup, $parameters);
					}
					foreach ($result as $line) {
						$id = $line [TABLE_PLAYER_ID];
						if (array_key_exists ($id, $playerIndexMap)) {
							$index = $playerIndexMap [$id];
							$totalScores [$index] [SCORE_TOTAL_UMA] = $line [TABLE_VAR_NB_GAMES];
							$totalScores [$index] [SCORE_TOTAL_SCORE] = $totalScores [$index] [SCORE_TOTAL_UMA] * 100.0 / $totalScores [$index] [SCORE_TOTAL_NB_GAMES];
						}
					}
				}

				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					usort ($totalScores, function ($score1, $score2) {
						if ($score1 [SCORE_TOTAL_SCORE] === $score2 [SCORE_TOTAL_SCORE]) {
							if ($score1 [SCORE_TOTAL_NB_GAMES] === $score2 [SCORE_TOTAL_NB_GAMES]) {
								return 0;
							} else {
								return $score1 [SCORE_TOTAL_NB_GAMES] > $score2 [SCORE_TOTAL_NB_GAMES] ? 1 : -1;
							}
						} else {
							return $score1 [SCORE_TOTAL_SCORE] > $score2 [SCORE_TOTAL_SCORE] ? 1 : -1;
						}
					});
				} else {
					usort ($totalScores, function ($score1, $score2) {
						if ($score1 [SCORE_TOTAL_SCORE] === $score2 [SCORE_TOTAL_SCORE]) {
							if ($score1 [SCORE_TOTAL_NB_GAMES] === $score2 [SCORE_TOTAL_NB_GAMES]) {
								return 0;
							} else {
								return $score1 [SCORE_TOTAL_NB_GAMES] > $score2 [SCORE_TOTAL_NB_GAMES] ? -1 : 1;
							}
						} else {
							return $score1 [SCORE_TOTAL_SCORE] > $score2 [SCORE_TOTAL_SCORE] ? -1 : 1;
						}
					});
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_WIN_RATE_5:
				$playerIndexMap = array ();
				{
					if ($isAdmin) {
						$nameField = TABLE_PLAYER_REAL_NAME;
						$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					} else {
						$nameField = TABLE_PLAYER_NAME;
						$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					}
					$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
					$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
					$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
					$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
					$queryNbPlayers = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . "=5";
					$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
					$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID;
					if ($useMinGames) {
						$queryHaving = " HAVING COUNT(*)>=" . strval ($minGamePlayed / 4);
					} else {
						$queryHaving = "";
					}
					if ($isPeriodSet) {
						$parameters = array (
							$tournamentId,
							$dateFromString,
							$dateToString
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryNbPlayers . $queryDate . $queryGroup . $queryHaving, $parameters);
					} else {
						$parameters = array (
							$tournamentId
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryNbPlayers . $queryGroup . $queryHaving, $parameters);
					}
					foreach ($result as $line) {
						$totalScore = array ();
						$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
						$totalScore [SCORE_TOTAL_YEAR] = 0;
						$totalScore [SCORE_TOTAL_MONTH] = 0;
						$totalScore [SCORE_TOTAL_DAY] = 0;
						$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
						$totalScore [SCORE_TOTAL_SCORE] = 0;
						$totalScore [SCORE_TOTAL_UMA] = 0.0;

						$playerIndexMap [$line [TABLE_PLAYER_ID]] = count ($totalScores);
						$totalScores [] = $totalScore;
					}
				}
				{
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
					$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
					$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
					$queryScore = SQL_AND . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_RANKING . "=1";
					$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
					$queryNbPlayers = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . "=5";
					$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
					$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID;
					if ($isPeriodSet) {
						$parameters = array (
							$tournamentId,
							$dateFromString,
							$dateToString
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryScore . $queryTournament . $queryNbPlayers . $queryDate . $queryGroup, $parameters);
					} else {
						$parameters = array (
							$tournamentId
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryScore . $queryTournament . $queryNbPlayers . $queryGroup, $parameters);
					}
					foreach ($result as $line) {
						$id = $line [TABLE_PLAYER_ID];
						if (array_key_exists ($id, $playerIndexMap)) {
							$index = $playerIndexMap [$id];
							$totalScores [$index] [SCORE_TOTAL_UMA] = $line [TABLE_VAR_NB_GAMES];
							$totalScores [$index] [SCORE_TOTAL_SCORE] = $totalScores [$index] [SCORE_TOTAL_UMA] * 100.0 / $totalScores [$index] [SCORE_TOTAL_NB_GAMES];
						}
					}
				}

				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					usort ($totalScores, function ($score1, $score2) {
						if ($score1 [SCORE_TOTAL_SCORE] === $score2 [SCORE_TOTAL_SCORE]) {
							if ($score1 [SCORE_TOTAL_NB_GAMES] === $score2 [SCORE_TOTAL_NB_GAMES]) {
								return 0;
							} else {
								return $score1 [SCORE_TOTAL_NB_GAMES] > $score2 [SCORE_TOTAL_NB_GAMES] ? 1 : -1;
							}
						} else {
							return $score1 [SCORE_TOTAL_SCORE] > $score2 [SCORE_TOTAL_SCORE] ? 1 : -1;
						}
					});
				} else {
					usort ($totalScores, function ($score1, $score2) {
						if ($score1 [SCORE_TOTAL_SCORE] === $score2 [SCORE_TOTAL_SCORE]) {
							if ($score1 [SCORE_TOTAL_NB_GAMES] === $score2 [SCORE_TOTAL_NB_GAMES]) {
								return 0;
							} else {
								return $score1 [SCORE_TOTAL_NB_GAMES] > $score2 [SCORE_TOTAL_NB_GAMES] ? -1 : 1;
							}
						} else {
							return $score1 [SCORE_TOTAL_SCORE] > $score2 [SCORE_TOTAL_SCORE] ? -1 : 1;
						}
					});
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_POSITIVE_RATE_4:
				$playerIndexMap = array ();
				{
					if ($isAdmin) {
						$nameField = TABLE_PLAYER_REAL_NAME;
						$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					} else {
						$nameField = TABLE_PLAYER_NAME;
						$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					}
					$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
					$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
					$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
					$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
					$queryNbPlayers = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . "=4";
					$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
					$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID;
					if ($useMinGames) {
						$queryHaving = " HAVING COUNT(*)>=" . strval ($minGamePlayed);
					} else {
						$queryHaving = "";
					}
					if ($isPeriodSet) {
						$parameters = array (
							$tournamentId,
							$dateFromString,
							$dateToString
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryNbPlayers . $queryDate . $queryGroup . $queryHaving, $parameters);
					} else {
						$parameters = array (
							$tournamentId
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryNbPlayers . $queryGroup . $queryHaving, $parameters);
					}
					foreach ($result as $line) {
						$totalScore = array ();
						$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
						$totalScore [SCORE_TOTAL_YEAR] = 0;
						$totalScore [SCORE_TOTAL_MONTH] = 0;
						$totalScore [SCORE_TOTAL_DAY] = 0;
						$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
						$totalScore [SCORE_TOTAL_SCORE] = 0;
						$totalScore [SCORE_TOTAL_UMA] = 0.0;

						$playerIndexMap [$line [TABLE_PLAYER_ID]] = count ($totalScores);
						$totalScores [] = $totalScore;
					}
				}
				{
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
					$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
					$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
					$queryScore = SQL_AND . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ">0";
					$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
					$queryNbPlayers = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . "=4";
					$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
					$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID;
					if ($isPeriodSet) {
						$parameters = array (
							$tournamentId,
							$dateFromString,
							$dateToString
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryScore . $queryTournament . $queryNbPlayers . $queryDate . $queryGroup, $parameters);
					} else {
						$parameters = array (
							$tournamentId
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryScore . $queryTournament . $queryNbPlayers . $queryGroup, $parameters);
					}
					foreach ($result as $line) {
						$id = $line [TABLE_PLAYER_ID];
						if (array_key_exists ($id, $playerIndexMap)) {
							$index = $playerIndexMap [$id];
							$totalScores [$index] [SCORE_TOTAL_UMA] = $line [TABLE_VAR_NB_GAMES];
							$totalScores [$index] [SCORE_TOTAL_SCORE] = $totalScores [$index] [SCORE_TOTAL_UMA] * 100.0 / $totalScores [$index] [SCORE_TOTAL_NB_GAMES];
						}
					}
				}

				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					usort ($totalScores, function ($score1, $score2) {
						if ($score1 [SCORE_TOTAL_SCORE] === $score2 [SCORE_TOTAL_SCORE]) {
							if ($score1 [SCORE_TOTAL_NB_GAMES] === $score2 [SCORE_TOTAL_NB_GAMES]) {
								return 0;
							} else {
								return $score1 [SCORE_TOTAL_NB_GAMES] > $score2 [SCORE_TOTAL_NB_GAMES] ? 1 : -1;
							}
						} else {
							return $score1 [SCORE_TOTAL_SCORE] > $score2 [SCORE_TOTAL_SCORE] ? 1 : -1;
						}
					});
				} else {
					usort ($totalScores, function ($score1, $score2) {
						if ($score1 [SCORE_TOTAL_SCORE] === $score2 [SCORE_TOTAL_SCORE]) {
							if ($score1 [SCORE_TOTAL_NB_GAMES] === $score2 [SCORE_TOTAL_NB_GAMES]) {
								return 0;
							} else {
								return $score1 [SCORE_TOTAL_NB_GAMES] > $score2 [SCORE_TOTAL_NB_GAMES] ? -1 : 1;
							}
						} else {
							return $score1 [SCORE_TOTAL_SCORE] > $score2 [SCORE_TOTAL_SCORE] ? -1 : 1;
						}
					});
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_POSITIVE_RATE_5:
				$playerIndexMap = array ();
				{
					if ($isAdmin) {
						$nameField = TABLE_PLAYER_REAL_NAME;
						$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					} else {
						$nameField = TABLE_PLAYER_NAME;
						$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					}
					$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
					$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
					$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
					$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
					$queryNbPlayers = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . "=5";
					$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
					$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID;
					if ($useMinGames) {
						$queryHaving = " HAVING COUNT(*)>=" . strval ($minGamePlayed / 4);
					} else {
						$queryHaving = "";
					}
					if ($isPeriodSet) {
						$parameters = array (
							$tournamentId,
							$dateFromString,
							$dateToString
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryNbPlayers . $queryDate . $queryGroup . $queryHaving, $parameters);
					} else {
						$parameters = array (
							$tournamentId
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryNbPlayers . $queryGroup . $queryHaving, $parameters);
					}
					foreach ($result as $line) {
						$totalScore = array ();
						$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
						$totalScore [SCORE_TOTAL_YEAR] = 0;
						$totalScore [SCORE_TOTAL_MONTH] = 0;
						$totalScore [SCORE_TOTAL_DAY] = 0;
						$totalScore [SCORE_TOTAL_NB_GAMES] = intval ($line [TABLE_VAR_NB_GAMES]);
						$totalScore [SCORE_TOTAL_SCORE] = 0;
						$totalScore [SCORE_TOTAL_UMA] = 0.0;

						$playerIndexMap [$line [TABLE_PLAYER_ID]] = count ($totalScores);
						$totalScores [] = $totalScore;
					}
				}
				{
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
					$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
					$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
					$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
					$queryScore = SQL_AND . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ">0";
					$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
					$queryNbPlayers = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_NB_PLAYERS . "=5";
					$queryDate = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ">=? AND " . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . "<?";
					$queryGroup = " GROUP BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID;
					if ($isPeriodSet) {
						$parameters = array (
							$tournamentId,
							$dateFromString,
							$dateToString
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryScore . $queryTournament . $queryNbPlayers . $queryDate . $queryGroup, $parameters);
					} else {
						$parameters = array (
							$tournamentId
						);
						$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryScore . $queryTournament . $queryNbPlayers . $queryGroup, $parameters);
					}
					foreach ($result as $line) {
						$id = $line [TABLE_PLAYER_ID];
						if (array_key_exists ($id, $playerIndexMap)) {
							$index = $playerIndexMap [$id];
							$totalScores [$index] [SCORE_TOTAL_UMA] = $line [TABLE_VAR_NB_GAMES];
							$totalScores [$index] [SCORE_TOTAL_SCORE] = $totalScores [$index] [SCORE_TOTAL_UMA] * 100.0 / $totalScores [$index] [SCORE_TOTAL_NB_GAMES];
						}
					}
				}

				if ($sortingMode === ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE_LOWEREST) {
					usort ($totalScores, function ($score1, $score2) {
						if ($score1 [SCORE_TOTAL_SCORE] === $score2 [SCORE_TOTAL_SCORE]) {
							if ($score1 [SCORE_TOTAL_NB_GAMES] === $score2 [SCORE_TOTAL_NB_GAMES]) {
								return 0;
							} else {
								return $score1 [SCORE_TOTAL_NB_GAMES] > $score2 [SCORE_TOTAL_NB_GAMES] ? 1 : -1;
							}
						} else {
							return $score1 [SCORE_TOTAL_SCORE] > $score2 [SCORE_TOTAL_SCORE] ? 1 : -1;
						}
					});
				} else {
					usort ($totalScores, function ($score1, $score2) {
						if ($score1 [SCORE_TOTAL_SCORE] === $score2 [SCORE_TOTAL_SCORE]) {
							if ($score1 [SCORE_TOTAL_NB_GAMES] === $score2 [SCORE_TOTAL_NB_GAMES]) {
								return 0;
							} else {
								return $score1 [SCORE_TOTAL_NB_GAMES] > $score2 [SCORE_TOTAL_NB_GAMES] ? -1 : 1;
							}
						} else {
							return $score1 [SCORE_TOTAL_SCORE] > $score2 [SCORE_TOTAL_SCORE] ? -1 : 1;
						}
					});
				}
				break;
			case ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE_TOTAL_ANNUAL:
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=? ";
				$queryGroup = " GROUP BY " . $nameField . ", " . TABLE_VAR_YEAR;
				if ($useMinGames) {
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
				$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryGroup . $queryHaving . $queryOrder . $queryLimit, $parameters);
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
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
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") as " . TABLE_VAR_YEAR . ", (MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1) DIV 3 AS " . TABLE_VAR_TRIMESTER . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") as " . TABLE_VAR_YEAR . ", (MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1) DIV 3 AS " . TABLE_VAR_TRIMESTER . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=? ";
				$queryGroup = " GROUP BY " . $nameField . ", " . TABLE_VAR_YEAR . ", " . TABLE_VAR_TRIMESTER;
				if ($useMinGames) {
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
				$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryGroup . $queryHaving . $queryOrder . $queryLimit, $parameters);
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
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
				if ($isAdmin) {
					$nameField = TABLE_PLAYER_REAL_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_REAL_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") as " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				} else {
					$nameField = TABLE_PLAYER_NAME;
					$querySelect = "SELECT " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", YEAR(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") as " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", SUM(" . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") AS " . TABLE_VAR_SCORE_TOTAL . ", COUNT(*) AS " . TABLE_VAR_NB_GAMES;
				}
				$queryFrom = " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_ID . ", " . TABLE_RCR_GAME_SCORE;
				$queryWhereJoin = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID;
				$queryPlayer = SQL_AND . TABLE_PLAYER . DOT . TABLE_PLAYER_REGULAR . "=1";
				$queryTournament = SQL_AND . TABLE_RCR_GAME_ID . DOT . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=? ";
				$queryGroup = " GROUP BY " . $nameField . ", " . TABLE_VAR_YEAR . ", " . TABLE_VAR_MONTH;
				if ($useMinGames) {
					$queryHaving = " HAVING COUNT(*)>=" . strval (MIN_GAME_PLAYED_MONTH);
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
				$result = executeQuery ($querySelect . $queryFrom . $queryWhereJoin . $queryPlayer . $queryTournament . $queryGroup . $queryHaving . $queryOrder . $queryLimit, $parameters);
				foreach ($result as $line) {
					$totalScore = array ();
					$totalScore [SCORE_TOTAL_NAME] = $line [$nameField];
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
function getNumberOfYearOfAllGamePeriod($tournamentId) {
	$firstDate = null;
	$lastDate = null;
	$query = "SELECT MIN(" . TABLE_RCR_GAME_ID_DATE . ") AS minDate, MAX(" . TABLE_RCR_GAME_ID_DATE . ") AS maxDate FROM " . TABLE_RCR_GAME_ID . " WHERE " . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=?";
	$parameters = array (
		$tournamentId
	);
	$result = executeQuery ($query, $parameters);
	if (count ($result) > 0) {
		$firstDate = date_create ($result [0] ["minDate"]);
		$lastDate = date_create ($result [0] ["maxDate"]);
	}
	if ($firstDate !== null && $lastDate !== null) {
		$interval = date_diff ($firstDate, $lastDate);
		if ($interval->days !== FALSE) {
			return $interval->days / 365.25;
		} else {
			return 0.0;
		}
	} else {
		return 0.0;
	}
}
function getProportionalPeriod($dateFrom, $dateTo) {
	if ($dateFrom !== null && $dateTo !== null) {
		$today = date_create ();
		date_time_set ($today, 0, 0, 0);
		$intervalFromToday = date_diff ($dateFrom, $today);
		$intervalTodayTo = date_diff ($today, $dateTo);
		if ($intervalFromToday->invert === 0 && $intervalTodayTo->invert === 0) {
			return floatval ($intervalFromToday->days) / floatval ($intervalFromToday->days + $intervalTodayTo->days);
		} else {
			return 1.0;
		}
	} else {
		return 0.0;
	}
}
?>