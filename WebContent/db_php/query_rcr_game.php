<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_player.php");
require_once ("query_database_table_rcr.php");
require_once ("query_rcr_game_config.php");
function addRCRGame($game) {
	session_start ();
	$result = array (
		ADD_RCR_GAME_RESULT => true,
		ADD_RCR_GAME_MESSAGE => ""
	);
	if (isset ($_SESSION[SESSION_LOG_IN_ID])) {
		if ($game !== null) {
			$gameArray = json_decode ($game, true);
			$tournamentId = array_key_exists (RCR_GAME_TOURNAMENT_ID, $gameArray) ? intval ($gameArray [RCR_GAME_TOURNAMENT_ID]) : null;
			$nbPlayers = array_key_exists (RCR_GAME_NB_PLAYERS, $gameArray) ? intval ($gameArray [RCR_GAME_NB_PLAYERS]) : null;
			$nbRounds = array_key_exists (RCR_GAME_NB_ROUNDS, $gameArray) ? intval ($gameArray [RCR_GAME_NB_ROUNDS]) : null;
			$year = array_key_exists (RCR_GAME_YEAR, $gameArray) ? intval ($gameArray [RCR_GAME_YEAR]) : null;
			$month = array_key_exists (RCR_GAME_MONTH, $gameArray) ? intval ($gameArray [RCR_GAME_MONTH]) : null;
			$day = array_key_exists (RCR_GAME_MONTH, $gameArray) ? intval ($gameArray [RCR_GAME_DAY]) : null;
			$scores = array_key_exists (RCR_GAME_SCORES, $gameArray) ? $gameArray [RCR_GAME_SCORES] : null;

			if ($tournamentId !== null && $nbPlayers !== null && $nbRounds !== null && $year !== null && $month !== null && $day !== null && $scores !== null && ! empty ($scores)) {
				beginTransaction ();
				$query = "SELECT " . TABLE_RCR_GAME_ID_ID . " FROM " . TABLE_RCR_GAME_ID . " WHERE YEAR(" . TABLE_RCR_GAME_ID_DATE . ")=? AND MONTH(" . TABLE_RCR_GAME_ID_DATE . ")=? AND DAY(" . TABLE_RCR_GAME_ID_DATE . ")=?";
				$parameters = array (
					$year,
					$month + 1,
					$day
				);
				$queryResult = executeQuery ($query, $parameters);
				$existingIds = array ();
				foreach ($queryResult as $line) {
					$existingIds [] = intval ($line [TABLE_RCR_GAME_ID_ID]);
				}
				$minGameId = ((($year % 100) * 100 + ($month + 1)) * 100 + $day) * 100 + 1;
				if (empty ($existingIds)) {
					$gameId = $minGameId;
				} else {
					$gameId = min (array_diff (range ($minGameId, max ($existingIds) + 1), $existingIds));
				}
				$date = strval ($year) . "-" . strval ($month + 1) . "-" . strval ($day);

				$query = "INSERT INTO " . TABLE_RCR_GAME_ID . " (" . TABLE_RCR_GAME_ID_ID . ", " . TABLE_RCR_GAME_ID_DATE . ", " . TABLE_RCR_GAME_ID_TOURNAMENT_ID . ", " . TABLE_RCR_GAME_ID_NB_PLAYERS . ", " . TABLE_RCR_GAME_ID_NB_ROUNDS . ") " . "VALUES(?, ?, ?, ?, ?)";
				$parameters = array (
					$gameId,
					$date,
					$tournamentId,
					$nbPlayers,
					$nbRounds
				);
				$idAdded = executeUpdate ($query, $parameters);

				if ($idAdded) {
					$scoreAddError = false;
					foreach ($scores as $score) {
						if (! empty ($score)) {
							$playerId = array_key_exists (RCR_SCORE_PLAYER_ID, $score) ? intval ($score [RCR_SCORE_PLAYER_ID]) : null;
							$ranking = array_key_exists (RCR_SCORE_RANKING, $score) ? intval ($score [RCR_SCORE_RANKING]) : null;
							$gameScore = array_key_exists (RCR_SCORE_GAME_SCORE, $score) ? intval ($score [RCR_SCORE_GAME_SCORE]) : null;
							$umaScore = array_key_exists (RCR_SCORE_UMA_SCORE, $score) ? intval ($score [RCR_SCORE_UMA_SCORE]) : null;
							$finalScore = array_key_exists (RCR_SCORE_FINAL_SCORE, $score) ? intval ($score [RCR_SCORE_FINAL_SCORE]) : null;
							if ($playerId !== null && $ranking !== null && $gameScore !== null && $umaScore !== null && $finalScore !== null) {
								$query = "INSERT INTO " . TABLE_RCR_GAME_SCORE . " (" . TABLE_RCR_GAME_SCORE_GAME_ID . ", " . TABLE_RCR_GAME_SCORE_PLAYER_ID . ", " . TABLE_RCR_GAME_SCORE_RANKING . ", " . TABLE_RCR_GAME_SCORE_GAME_SCORE . ", " . TABLE_RCR_GAME_SCORE_UMA_SCORE . ", " . TABLE_RCR_GAME_SCORE_FINAL_SCORE . ") VALUES(?, ?, ?, ?, ?, ?)";
								$parameters = array (
									$gameId,
									$playerId,
									$ranking,
									$gameScore,
									$umaScore,
									$finalScore
								);
								$scoreAdded = executeUpdate ($query, $parameters);
								if (! $scoreAdded) {
									$scoreAddError = true;
									break;
								}
							} else {
								$scoreAddError = true;
								break;
							}
						} else {
							$scoreAddError = true;
							break;
						}
					}
					if ($scoreAddError) {
						rollBack ();
						$result [ADD_RCR_GAME_RESULT] = false;
						$result [ADD_RCR_GAME_MESSAGE] = ADD_RCR_GAME_MESSAGE_SCORE_ERROR;
					} else {
						commit ();
						$result [ADD_RCR_GAME_MESSAGE] = ADD_RCR_GAME_MESSAGE_OK . strval($gameId);
					}
				} else {
					rollBack ();
					$result [ADD_RCR_GAME_RESULT] = false;
					$result [ADD_RCR_GAME_MESSAGE] = ADD_RCR_GAME_MESSAGE_GAME_ERROR;
				}
			} else {
				rollBack ();
				$result [ADD_RCR_GAME_RESULT] = false;
				$result [ADD_RCR_GAME_MESSAGE] = ADD_RCR_GAME_MESSAGE_NULL;
			}
		} else {
			$result [ADD_RCR_GAME_RESULT] = false;
			$result [ADD_RCR_GAME_MESSAGE] = ADD_RCR_GAME_MESSAGE_NULL;
		}
	} else {
		$result [ADD_RCR_GAME_RESULT] = false;
		$result [ADD_RCR_GAME_MESSAGE] = ADD_RCR_GAME_MESSAGE_LOGIN;
	}
	return json_encode ($result);
}
function deleteRCRGame($id) {
	session_start ();
	$result = array (
		DELETE_RCR_GAME_RESULT => true,
		DELETE_RCR_GAME_MESSAGE => DELETE_RCR_GAME_MESSAGE_OK
	);
	$isAdmin = isset ($_SESSION[SESSION_IS_ADMIN]) ? $_SESSION[SESSION_IS_ADMIN] : false;
	if ($isAdmin) {
		if ($id !== null) {
			$query = "DELETE FROM " . TABLE_RCR_GAME_SCORE . " WHERE " . TABLE_RCR_GAME_SCORE_GAME_ID . "=?";
			$parameters = array (
				$id
			);
			$deleted = executeUpdate ($query, $parameters);

			if (! $deleted) {
				$result [DELETE_RCR_GAME_RESULT] = false;
				$result [DELETE_RCR_GAME_MESSAGE] = DELETE_RCR_GAME_MESSAGE_DB;
			} else {
				$query = "DELETE FROM " . TABLE_RCR_GAME_ID . " WHERE " . TABLE_RCR_GAME_ID_ID . "=?";
				$parameters = array (
					$id
				);
				$deleted = executeUpdate ($query, $parameters);
				if (! $deleted) {
					$result [DELETE_RCR_GAME_RESULT] = false;
					$result [DELETE_RCR_GAME_MESSAGE] = DELETE_RCR_GAME_MESSAGE_DB;
				}
			}
		} else {
			$result [DELETE_RCR_GAME_RESULT] = false;
			$result [DELETE_RCR_GAME_MESSAGE] = DELETE_RCR_GAME_MESSAGE_NULL;
		}
	} else {
		$result [DELETE_RCR_GAME_RESULT] = false;
		$result [DELETE_RCR_GAME_MESSAGE] = DELETE_PLAYER_MESSAGE_ADMIN;
	}
	return json_encode ($result);
}
function getRCRPlayers() {
	$querySelect = "SELECT DISTINCT " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . " FROM " . TABLE_PLAYER . ", " . TABLE_RCR_GAME_SCORE;
	$queryWhere = " WHERE " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . "=" . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID;
	$queryOrder = " ORDER BY " . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . " ASC";
	$result = executeQuery ($querySelect . $queryWhere . $queryOrder, null);
	$players = array ();
	foreach ($result as $line) {
		$player = array ();
		$player [PLAYER_ID] = $line [TABLE_PLAYER_ID];
		$player [PLAYER_NAME] = $line [TABLE_PLAYER_NAME];
		$players [] = $player;
	}
	return json_encode ($players);
}
function getRCRYears($tournamentId) {
	$query = "SELECT DISTINCT YEAR(" . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . " FROM " . TABLE_RCR_GAME_ID . " WHERE " . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=? ORDER BY " . TABLE_VAR_YEAR . " DESC";
	$parameters = array (
		$tournamentId
	);
	$result = executeQuery ($query, $parameters);
	$years = array ();
	if (! empty ($result)) {
		foreach ($result as $line) {
			$years [] = intval ($line [TABLE_VAR_YEAR]);
		}
	}
	return json_encode ($years);
}
function getRCRDays($tournamentId, $year, $month) {
	$days = array ();
	if ($tournamentId !== null && $year !== null && $month !== null) {
		$query = "SELECT DISTINCT DAY(" . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY . " FROM " . TABLE_RCR_GAME_ID . " WHERE " . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=? AND YEAR(" . TABLE_RCR_GAME_ID_DATE . ")=? AND MONTH(" . TABLE_RCR_GAME_ID_DATE . ")=? ORDER BY " . TABLE_VAR_DAY . " ASC";
		$parameters = array (
			$tournamentId,
			$year,
			$month + 1
		);
		$result = executeQuery ($query, $parameters);
		if (! empty ($result)) {
			foreach ($result as $line) {
				$days [] = intval ($line [TABLE_VAR_DAY]);
			}
		}
	}
	return json_encode ($days);
}
function getRCRGameIds($tournamentId, $year, $month, $day) {
	$ids = array ();
	if ($tournamentId !== null && $year !== null && $month !== null) {
		$query = "SELECT " . TABLE_RCR_GAME_ID_ID . " FROM " . TABLE_RCR_GAME_ID . " WHERE " . TABLE_RCR_GAME_ID_TOURNAMENT_ID . "=? AND YEAR(" . TABLE_RCR_GAME_ID_DATE . ")=? AND MONTH(" . TABLE_RCR_GAME_ID_DATE . ")=? AND DAY(" . TABLE_RCR_GAME_ID_DATE . ")=? ORDER BY " . TABLE_RCR_GAME_ID_ID . " ASC";
		$parameters = array (
			$tournamentId,
			$year,
			$month + 1,
			$day
		);
		$result = executeQuery ($query, $parameters);
		if (! empty ($result)) {
			foreach ($result as $line) {
				$ids [] = intval ($line [TABLE_RCR_GAME_ID_ID]);
			}
		}
	}
	return json_encode ($ids);
}
function getRCRGame($id) {
	if ($id !== null) {
		$query = "SELECT " . TABLE_RCR_GAME_ID_TOURNAMENT_ID . ", " . TABLE_RCR_GAME_ID_NB_PLAYERS . ", " . TABLE_RCR_GAME_ID_NB_ROUNDS . ", YEAR(" . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_YEAR . ", MONTH(" . TABLE_RCR_GAME_ID_DATE . ") - 1 AS " . TABLE_VAR_MONTH . ", DAY(" . TABLE_RCR_GAME_ID_DATE . ") AS " . TABLE_VAR_DAY . " FROM " . TABLE_RCR_GAME_ID . " WHERE " . TABLE_RCR_GAME_ID_ID . "=?";
		$parameters = array (
			$id
		);
		$result = executeQuery ($query, $parameters);
		if (! empty ($result)) {
			$game = array ();
			$game [RCR_GAME_ID] = $id;
			$game [RCR_GAME_TOURNAMENT_ID] = intval ($result [0] [TABLE_RCR_GAME_ID_TOURNAMENT_ID]);
			$game [RCR_GAME_NB_PLAYERS] = intval ($result [0] [TABLE_RCR_GAME_ID_NB_PLAYERS]);
			$game [RCR_GAME_NB_ROUNDS] = intval ($result [0] [TABLE_RCR_GAME_ID_NB_ROUNDS]);
			$game [RCR_GAME_YEAR] = intval ($result [0] [TABLE_VAR_YEAR]);
			$game [RCR_GAME_MONTH] = intval ($result [0] [TABLE_VAR_MONTH]);
			$game [RCR_GAME_DAY] = intval ($result [0] [TABLE_VAR_DAY]);
			$game [RCR_GAME_SCORES] = array ();

			$querySelect = "SELECT " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . ", " . TABLE_PLAYER . DOT . TABLE_PLAYER_NAME . ", " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_RANKING . ", " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_SCORE . ", " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_UMA_SCORE . ", " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_FINAL_SCORE;
			$queryFrom = " FROM " . TABLE_RCR_GAME_SCORE . ", " . TABLE_PLAYER;
			$queryWhere = " WHERE " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_PLAYER_ID . "=" . TABLE_PLAYER . DOT . TABLE_PLAYER_ID . " AND " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_GAME_ID . "=?";
			$queryOrder = " ORDER BY " . TABLE_RCR_GAME_SCORE . DOT . TABLE_RCR_GAME_SCORE_RANKING;
			$result = executeQuery ($querySelect . $queryFrom . $queryWhere . $queryOrder, $parameters);
			if (! empty ($result)) {
				foreach ($result as $line) {
					$score = array ();
					$score [RCR_SCORE_PLAYER_ID] = intval ($line [TABLE_RCR_GAME_SCORE_PLAYER_ID]);
					$score [RCR_SCORE_PLAYER_NAME] = $line [TABLE_PLAYER_NAME];
					$score [RCR_SCORE_RANKING] = intval ($line [TABLE_RCR_GAME_SCORE_RANKING]);
					$score [RCR_SCORE_GAME_SCORE] = intval ($line [TABLE_RCR_GAME_SCORE_GAME_SCORE]);
					$score [RCR_SCORE_UMA_SCORE] = intval ($line [TABLE_RCR_GAME_SCORE_UMA_SCORE]);
					$score [RCR_SCORE_FINAL_SCORE] = intval ($line [TABLE_RCR_GAME_SCORE_FINAL_SCORE]);
					$game [RCR_GAME_SCORES] [] = $score;
				}
			}
			return json_encode ($game);
		} else {
			return null;
		}
	} else {
		return null;
	}
}
?>