<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_rcr.php");
require_once ("query_rcr_tournament_config.php");
function addRCRTournament($name) {
	session_start ();
	$result = array (
		ADD_RCR_TOURNAMENT_RESULT => true,
		ADD_RCR_TOURNAMENT_MESSAGE => ADD_RCR_TOURNAMENT_MESSAGE_OK
	);
	$isAdmin = isset ($_SESSION[SESSION_IS_ADMIN]) ? $_SESSION[SESSION_IS_ADMIN] : false;
	if ($isAdmin) {
		if ($name !== null && strlen ($name) > 0) {
			$query = "SELECT " . TABLE_RCR_TOURNAMENT_ID . " FROM " . TABLE_RCR_TOURNAMENT;
			$queryResult = executeQuery ($query, null);
			$existingIds = array ();
			foreach ($queryResult as $line) {
				$existingIds [] = intval ($line [TABLE_RCR_TOURNAMENT_ID]);
			}
			if (empty ($existingIds)) {
				$id = 1;
			} else {
				$id = min (array_diff (range (1, max ($existingIds) + 1), $existingIds));
			}
			$query = "INSERT INTO " . TABLE_RCR_TOURNAMENT . "(" . TABLE_RCR_TOURNAMENT_ID . ", " . TABLE_RCR_TOURNAMENT_NAME . ") VALUES(?, ?)";
			$parameters = array (
				$id,
				$name
			);
			$added = executeUpdate ($query, $parameters);
			if (! $added) {
				$result [ADD_RCR_TOURNAMENT_RESULT] = false;
				$result [ADD_RCR_TOURNAMENT_MESSAGE] = ADD_RCR_TOURNAMENT_MESSAGE_EXISTING;
			}
		} else {
			$result [ADD_RCR_TOURNAMENT_RESULT] = false;
			$result [ADD_RCR_TOURNAMENT_MESSAGE] = ADD_RCR_TOURNAMENT_MESSAGE_NULL;
		}
	} else {
		$result [ADD_RCR_TOURNAMENT_RESULT] = false;
		$result [ADD_RCR_TOURNAMENT_MESSAGE] = ADD_RCR_TOURNAMENT_MESSAGE_ADMIN;
	}
	return json_encode ($result);
}
function modifyRCRTournament($id, $name) {
	session_start ();
	$result = array (
		MODIFY_RCR_TOURNAMENT_RESULT => true,
		MODIFY_RCR_TOURNAMENT_MESSAGE => MODIFY_RCR_TOURNAMENT_MESSAGE_OK
	);
	$isAdmin = isset ($_SESSION[SESSION_IS_ADMIN]) ? $_SESSION[SESSION_IS_ADMIN] : false;
	if ($isAdmin) {
		if ($id !== null && $name !== null && strlen ($name) > 0) {
			$query = "UPDATE " . TABLE_RCR_TOURNAMENT . " SET " . TABLE_RCR_TOURNAMENT_NAME . "=? WHERE " . TABLE_RCR_TOURNAMENT_ID . "=?";
			$parameters = array (
				$name,
				$id
			);
			$modified = executeUpdate ($query, $parameters);
			if (! $modified) {
				$result [MODIFY_RCR_TOURNAMENT_RESULT] = false;
				$result [MODIFY_RCR_TOURNAMENT_MESSAGE] = MODIFY_RCR_TOURNAMENT_MESSAGE_EXISTING;
			}
		} else {
			$result [MODIFY_RCR_TOURNAMENT_RESULT] = false;
			$result [MODIFY_RCR_TOURNAMENT_MESSAGE] = MODIFY_RCR_TOURNAMENT_MESSAGE_NULL;
		}
	} else {
		$result [MODIFY_RCR_TOURNAMENT_RESULT] = false;
		$result [MODIFY_RCR_TOURNAMENT_MESSAGE] = MODIFY_RCR_TOURNAMENT_MESSAGE_ADMIN;
	}
	return json_encode ($result);
}
function deleteRCRTournament($id) {
	session_start ();
	$result = array (
		DELETE_RCR_TOURNAMENT_RESULT => true,
		DELETE_RCR_TOURNAMENT_MESSAGE => DELETE_RCR_TOURNAMENT_MESSAGE_OK
	);
	$isAdmin = isset ($_SESSION[SESSION_IS_ADMIN]) ? $_SESSION[SESSION_IS_ADMIN] : false;
	if ($isAdmin) {
		if ($id !== null) {
			$query = "DELETE FROM " . TABLE_RCR_TOURNAMENT . " WHERE " . TABLE_RCR_TOURNAMENT_ID . "=?";
			$parameters = array (
				$id
			);
			$deleted = executeUpdate ($query, $parameters);
			if (! $deleted) {
				$result [DELETE_RCR_TOURNAMENT_RESULT] = false;
				$result [DELETE_RCR_TOURNAMENT_MESSAGE] = DELETE_RCR_TOURNAMENT_MESSAGE_DB;
			}
		} else {
			$result [DELETE_RCR_TOURNAMENT_RESULT] = false;
			$result [DELETE_RCR_TOURNAMENT_MESSAGE] = DELETE_RCR_TOURNAMENT_MESSAGE_NULL;
		}
	} else {
		$result [DELETE_RCR_TOURNAMENT_RESULT] = false;
		$result [DELETE_RCR_TOURNAMENT_MESSAGE] = DELETE_RCR_TOURNAMENT_MESSAGE_ADMIN;
	}
	return json_encode ($result);
}
function getRCRTournaments() {
	$query = "SELECT " . TABLE_RCR_TOURNAMENT_ID . ", " . TABLE_RCR_TOURNAMENT_NAME . " FROM " . TABLE_RCR_TOURNAMENT . " ORDER BY " . TABLE_RCR_TOURNAMENT_ID . " DESC";
	$result = executeQuery ($query, null);
	$tournaments = array ();
	if (! empty ($result)) {
		foreach ($result as $line) {
			$tournament = array ();
			$tournament [TOURNAMENT_ID] = $line [TABLE_RCR_TOURNAMENT_ID];
			$tournament [TOURNAMENT_NAME] = $line [TABLE_RCR_TOURNAMENT_NAME];
			$tournaments [] = $tournament;
		}
	}
	return json_encode ($tournaments);
}
?>