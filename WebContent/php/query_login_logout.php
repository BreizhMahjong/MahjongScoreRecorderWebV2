<?php
require_once ("query_database_connection.php");
require_once ("query_database_table_user.php");
require_once ("query_login_logout_config.php");
require_once ("../wp-includes/class-phpass.php");
function login($userName, $password) {
	$result = array (
			LOGIN_RESULT => true,
			LOGIN_IS_ADMIN => false,
			LOGIN_MESSAGE => LOGIN_MESSAGE_OK 
	);
	if ($userName !== null && $password !== null) {
		$query = "SELECT " . TABLE_USERS . DOT . TABLE_USERS_ID . ", " . TABLE_ADMINS_LEVEL . ", " . TABLE_USERS_PASSWORD . ", " . TABLE_USERS_DISPLAY_NAME . " FROM " . TABLE_USERS . " LEFT OUTER JOIN " . TABLE_ADMINS . " ON " . TABLE_USERS . DOT . TABLE_USERS_ID . "=" . TABLE_ADMINS . DOT . TABLE_ADMINS_USER_ID . " WHERE " . TABLE_USERS_USER_NAME . "=?";
		$parameters = array (
				$userName 
		);
		$user = executeQuery ( $query, $parameters );
		if (! empty ( $user )) {
			$id = intval ( $user [0] [TABLE_USERS_ID] );
			$isAdmin = ! is_null ( $user [0] [TABLE_ADMINS_LEVEL] );
			$hash = $user [0] [TABLE_USERS_PASSWORD];
			$displayName = $user [0] [TABLE_USERS_DISPLAY_NAME];
			
			$wpHasher = new PasswordHash ( 8, true );
			$passwordMatch = $wpHasher->CheckPassword ( $password, $hash );
			// $passwordMatch = $password === $hash;
			if ($passwordMatch) {
				session_start ();
				$_SESSION [SESSION_LOG_IN_ID] = $id;
				$_SESSION [SESSION_IS_ADMIN] = $isAdmin;
				$_SESSION [SESSION_DISPLAY_NAME] = $displayName;
				$result [LOGIN_RESULT] = true;
				$result [LOGIN_IS_ADMIN] = $isAdmin;
				$result [LOGIN_MESSAGE] = LOGIN_MESSAGE_OK;
			} else {
				$result [LOGIN_RESULT] = false;
				$result [LOGIN_MESSAGE] = LOGIN_MESSAGE_PWD_ERROR;
			}
		} else {
			$result [LOGIN_RESULT] = false;
			$result [LOGIN_MESSAGE] = LOGIN_MESSAGE_ID_ERROR;
		}
	} else {
		$result [LOGIN_RESULT] = false;
		$result [LOGIN_MESSAGE] = LOGIN_MESSAGE_NULL;
	}
	return json_encode ( $result );
}
function isLoggedIn() {
	session_start ();
	$result = array (
			LOGIN_RESULT => isset ( $_SESSION [SESSION_LOG_IN_ID] ),
			LOGIN_IS_ADMIN => isset ( $_SESSION [SESSION_IS_ADMIN] ) ? boolval ( $_SESSION [SESSION_IS_ADMIN] ) : false,
			LOGIN_MESSAGE => LOGIN_MESSAGE_OK 
	);
	return json_encode ( $result );
}
function logout() {
	session_start ();
	unset ( $_SESSION [SESSION_LOG_IN_ID] );
	unset ( $_SESSION [SESSION_IS_ADMIN] );
	unset ( $_SESSION [SESSION_DISPLAY_NAME] );
	session_destroy ();
}
?>