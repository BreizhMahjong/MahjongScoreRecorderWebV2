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
		$query = "SELECT " . TABLE_USERS . DOT . TABLE_USERS_ID . ", " . TABLE_ADMINS_LEVEL . ", " . TABLE_USERS_PASSWORD . " FROM " . TABLE_USERS . " LEFT OUTER JOIN " . TABLE_ADMINS . " ON " . TABLE_USERS . DOT . TABLE_USERS_ID . "=" . TABLE_ADMINS . DOT . TABLE_ADMINS_USER_ID . " WHERE " . TABLE_USERS_USER_NAME . "=?";
		$parameters = array (
			$userName
		);
		$user = executeQuery ($query, $parameters);
		if (!empty ($user)) {
			$id = intval ($user [0] [TABLE_USERS_ID]);
			$isAdmin = !is_null ($user [0] [TABLE_ADMINS_LEVEL]);
			$hash = $user [0] [TABLE_USERS_PASSWORD];

			$wpHasher = new PasswordHash (8, true);
			$passwordMatch = $wpHasher->CheckPassword ($password, $hash);

			if ($passwordMatch) {
				session_start ();
				$now = time ();
				$encryptedText = encryptCookie($id, $isAdmin);
				setcookie (COOKIE_NAME_ID, $encryptedText, $now + COOKIE_EXPIRE_TIME, "", "", false, false);

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
	return json_encode ($result);
}
function isLoggedIn() {
	session_start ();
	$result = array (
		LOGIN_RESULT => isset ($_SESSION[SESSION_LOG_IN_ID]),
		LOGIN_IS_ADMIN => isset ($_SESSION[SESSION_IS_ADMIN]) ? $_SESSION[SESSION_IS_ADMIN] : false,
		LOGIN_MESSAGE => LOGIN_MESSAGE_OK
	);
	return json_encode ($result);
}
function encryptCookie($id, $isAdmin) {
    $idString = ($isAdmin ? "1" : "0") . strval ($id);

    $ivSize = mcrypt_get_iv_size (MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv ($ivSize, MCRYPT_RAND);
    $encryptedId = mcrypt_encrypt (MCRYPT_RIJNDAEL_128, ENCRYPTION_KEY, $idString, MCRYPT_MODE_CBC, $iv);
    return base64_encode ($iv . $encryptedId);
}
function decryptCookie($encryptedCookieBase64) {
    $success = false;
    $encryptedText = base64_decode ($encryptedCookieBase64);
    if ($encryptedText !== false) {
        $ivSize = mcrypt_get_iv_size (MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = substr ($encryptedText, 0, $ivSize);
        $encryptedId = substr ($encryptedText, $ivSize);
        $decryptedId = mcrypt_decrypt (MCRYPT_RIJNDAEL_128, ENCRYPTION_KEY, $encryptedId, MCRYPT_MODE_CBC, $iv);

        if ($decryptedId !== false && strlen ($decryptedId) >= 2) {
            $isAdminString = substr ($decryptedId, 0, 1);
            $idString = trim (substr ($decryptedId, 1));
            if (is_numeric ($isAdminString) && is_numeric ($idString)) {
                $_SESSION[SESSION_LOG_IN_ID] = intval ($idString);
                $_SESSION[SESSION_IS_ADMIN] = $isAdminString === "1";
                $success = true;
            }
        }
    }

    if (!$success) {
        unset ($_SESSION[SESSION_LOG_IN_ID]);
        unset ($_SESSION[SESSION_IS_ADMIN]);
    }
}
function logout() {
	session_start ();
	$now = time ();
	setcookie (COOKIE_NAME_ID, "", $now - 1, "", "", false, false);
	unset ($_SESSION[SESSION_LOG_IN_ID]);
	unset ($_SESSION[SESSION_IS_ADMIN]);
	session_destroy ();
}
?>