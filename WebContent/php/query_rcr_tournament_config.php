<?php
require_once ("query_rcr_common.php");

define ( "ADD_RCR_TOURNAMENT_RESULT", "result" );
define ( "ADD_RCR_TOURNAMENT_MESSAGE", "message" );
define ( "ADD_RCR_TOURNAMENT_MESSAGE_OK", "ok" );
define ( "ADD_RCR_TOURNAMENT_MESSAGE_ADMIN", "Seuls les administrateurs peuvent effectuer cette action." );
define ( "ADD_RCR_TOURNAMENT_MESSAGE_NULL", "Le nom ne peut pas être vides." );
define ( "ADD_RCR_TOURNAMENT_MESSAGE_EXISTING", "Le nom est déjà utilisé." );

define ( "MODIFY_RCR_TOURNAMENT_RESULT", "result" );
define ( "MODIFY_RCR_TOURNAMENT_MESSAGE", "message" );
define ( "MODIFY_RCR_TOURNAMENT_MESSAGE_OK", "ok" );
define ( "MODIFY_RCR_TOURNAMENT_MESSAGE_ADMIN", "Seuls les administrateurs peuvent effectuer cette action." );
define ( "MODIFY_RCR_TOURNAMENT_MESSAGE_NULL", "L'identifiant et le nom ne peuvent pas être vides." );
define ( "MODIFY_RCR_TOURNAMENT_MESSAGE_EXISTING", "Le nom est déjà utilisé." );

define ( "DELETE_RCR_TOURNAMENT_RESULT", "result" );
define ( "DELETE_RCR_TOURNAMENT_MESSAGE", "message" );
define ( "DELETE_RCR_TOURNAMENT_MESSAGE_OK", "ok" );
define ( "DELETE_RCR_TOURNAMENT_MESSAGE_ADMIN", "Seuls les administrateurs peuvent effectuer cette action." );
define ( "DELETE_RCR_TOURNAMENT_MESSAGE_NULL", "L'identifiant ne peut pas être vide." );
define ( "DELETE_RCR_TOURNAMENT_MESSAGE_DB", "Une erreur est survenue." );
?>
