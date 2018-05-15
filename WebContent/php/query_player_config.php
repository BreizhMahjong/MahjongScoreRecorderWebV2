<?php
require_once ("query_common.php");

define ("ADD_PLAYER_RESULT", "result");
define ("ADD_PLAYER_MESSAGE", "message");
define ("ADD_PLAYER_MESSAGE_OK", "ok");
define ("ADD_PLAYER_MESSAGE_ADMIN", "Seuls les administrateurs peuvent effectuer cette action.");
define ("ADD_PLAYER_MESSAGE_NULL", "Le nom ne peut pas être vide.");
define ("ADD_PLAYER_MESSAGE_EXISTING", "Le nom est déjà utilisé.");

define ("MODIFY_PLAYER_RESULT", "result");
define ("MODIFY_PLAYER_MESSAGE", "message");
define ("MODIFY_PLAYER_MESSAGE_OK", "ok");
define ("MODIFY_PLAYER_MESSAGE_ADMIN", "Seuls les administrateurs peuvent effectuer cette action.");
define ("MODIFY_PLAYER_MESSAGE_NULL", "L'identifiant et le nom ne peuvent pas être vides.");
define ("MODIFY_PLAYER_MESSAGE_EXISTING", "Le nom est déjà utilisé.");

define ("DELETE_PLAYER_RESULT", "result");
define ("DELETE_PLAYER_MESSAGE", "message");
define ("DELETE_PLAYER_MESSAGE_OK", "ok");
define ("DELETE_PLAYER_MESSAGE_ADMIN", "Seuls les administrateurs peuvent effectuer cette action.");
define ("DELETE_PLAYER_MESSAGE_NULL", "L'identifiant ne peut pas être vide.");
define ("DELETE_PLAYER_MESSAGE_DB", "Une erreur est survenue.");
?>