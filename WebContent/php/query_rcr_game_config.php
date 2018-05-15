<?php
require_once ("query_rcr_common.php");

define ("ADD_RCR_GAME_RESULT", "result");
define ("ADD_RCR_GAME_MESSAGE", "message");
define ("ADD_RCR_GAME_MESSAGE_OK", "ok");
define ("ADD_RCR_GAME_MESSAGE_LOGIN", "Vous devez vous identifier pour ajouter des scores.");
define ("ADD_RCR_GAME_MESSAGE_NULL", "L'information du jeu ne peut pas être vide.");
define ("ADD_RCR_GAME_MESSAGE_GAME_ERROR", "Il y a une erreur dans les informations du jeu.");
define ("ADD_RCR_GAME_MESSAGE_SCORE_ERROR", "Il y a une erreur dans les scores.");

define ("DELETE_RCR_GAME_RESULT", "result");
define ("DELETE_RCR_GAME_MESSAGE", "message");
define ("DELETE_RCR_GAME_MESSAGE_OK", "ok");
define ("DELETE_RCR_GAME_MESSAGE_ADMIN", "Seuls les administrateurs peuvent effectuer cette action.");
define ("DELETE_RCR_GAME_MESSAGE_NULL", "L'identifiant ne peut pas être vide.");
define ("DELETE_RCR_GAME_MESSAGE_DB", "Une erreur est survenue.");

?>