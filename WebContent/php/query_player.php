<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_player.php");
require_once ("query_player_config.php");
function addPlayer($name) {
	session_start ();
	$result = array (
			ADD_PLAYER_RESULT => true,
			ADD_PLAYER_MESSAGE => ADD_PLAYER_MESSAGE_OK 
	);
	$isAdmin = isset ( $_SESSION [SESSION_IS_ADMIN] ) ? boolval ( $_SESSION [SESSION_IS_ADMIN] ) : false;
	if ($isAdmin) {
		if ($name !== null && strlen($name) > 0) {
			$query = "SELECT " . TABLE_PLAYER_ID . " FROM " . TABLE_PLAYER;
			$queryResult = executeQuery ( $query, null );
			$existingIds = array ();
			foreach ( $queryResult as $line ) {
				$existingIds [] = intval ( $line [TABLE_PLAYER_ID] );
			}
			if (empty ( $existingIds )) {
				$id = 1;
			} else {
				$id = min ( array_diff ( range ( 1, max ( $existingIds ) + 1 ), $existingIds ) );
			}
			$query = "INSERT INTO " . TABLE_PLAYER . "(" . TABLE_PLAYER_ID . ", " . TABLE_PLAYER_NAME . ") VALUES(?, ?)";
			$parameters = array (
					$id,
					$name 
			);
			$added = executeUpdate ( $query, $parameters );
			if (! $added) {
				$result [ADD_PLAYER_RESULT] = false;
				$result [ADD_PLAYER_MESSAGE] = ADD_PLAYER_MESSAGE_EXISTING;
			}
		} else {
			$result [ADD_PLAYER_RESULT] = false;
			$result [ADD_PLAYER_MESSAGE] = ADD_PLAYER_MESSAGE_NULL;
		}
	} else {
		$result [ADD_PLAYER_RESULT] = false;
		$result [ADD_PLAYER_MESSAGE] = ADD_PLAYER_MESSAGE_ADMIN;
	}
	return json_encode ( $result );
}
function modifyPlayer($id, $name) {
	session_start ();
	$result = array (
			MODIFY_PLAYER_RESULT => true,
			MODIFY_PLAYER_MESSAGE => MODIFY_PLAYER_MESSAGE_OK 
	);
	$isAdmin = isset ( $_SESSION [SESSION_IS_ADMIN] ) ? boolval ( $_SESSION [SESSION_IS_ADMIN] ) : false;
	if ($isAdmin) {
		if ($id !== null && $name !== null && strlen($name) > 0) {
			$query = "UPDATE " . TABLE_PLAYER . " SET " . TABLE_PLAYER_NAME . "=? WHERE " . TABLE_PLAYER_ID . "=?";
			$parameters = array (
					$name,
					$id 
			);
			$modified = executeUpdate ( $query, $parameters );
			if (! $modified) {
				$result [MODIFY_PLAYER_RESULT] = false;
				$result [MODIFY_PLAYER_MESSAGE] = ADD_PLAYER_MESSAGE_EXISTING;
			}
		} else {
			$result [MODIFY_PLAYER_RESULT] = false;
			$result [MODIFY_PLAYER_MESSAGE] = MODIFY_PLAYER_MESSAGE_NULL;
		}
	} else {
		$result [MODIFY_PLAYER_RESULT] = false;
		$result [MODIFY_PLAYER_MESSAGE] = MODIFY_PLAYER_MESSAGE_ADMIN;
	}
	return json_encode ( $result );
}
function deletePlayer($id) {
	session_start ();
	$result = array (
			DELETE_PLAYER_RESULT => true,
			DELETE_PLAYER_MESSAGE => DELETE_PLAYER_MESSAGE_OK 
	);
	$isAdmin = isset ( $_SESSION [SESSION_IS_ADMIN] ) ? boolval ( $_SESSION [SESSION_IS_ADMIN] ) : false;
	if ($isAdmin) {
		if ($id !== null) {
			$query = "DELETE FROM " . TABLE_PLAYER . " WHERE " . TABLE_PLAYER_ID . "=?";
			$parameters = array (
					$id 
			);
			$deleted = executeUpdate ( $query, $parameters );
			if (! $deleted) {
				$result [DELETE_PLAYER_RESULT] = false;
				$result [DELETE_PLAYER_MESSAGE] = DELETE_PLAYER_MESSAGE_DB;
			}
		} else {
			$result [DELETE_PLAYER_RESULT] = false;
			$result [DELETE_PLAYER_MESSAGE] = DELETE_PLAYER_MESSAGE_NULL;
		}
	} else {
		$result [DELETE_PLAYER_RESULT] = false;
		$result [DELETE_PLAYER_MESSAGE] = DELETE_PLAYER_MESSAGE_ADMIN;
	}
	return json_encode ( $result );
}
function getAllPlayers() {
	$query = "SELECT " . TABLE_PLAYER_ID . ", " . TABLE_PLAYER_NAME . " FROM " . TABLE_PLAYER . " ORDER BY " . TABLE_PLAYER_ID . " ASC";
	$result = executeQuery ( $query, null );
	$players = array ();
	if (! empty ( $result )) {
		foreach ( $result as $line ) {
			$player = array ();
			$player [PLAYER_ID] = $line [TABLE_PLAYER_ID];
			$player [PLAYER_NAME] = $line [TABLE_PLAYER_NAME];
			$players [] = $player;
		}
	}
	return json_encode ( $players );
}
?>