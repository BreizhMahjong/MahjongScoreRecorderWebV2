<?php
require_once ("query_database_table_common.php");

define ("TABLE_RCR_TOURNAMENT", "bmjr_rcr_tournament");
define ("TABLE_RCR_TOURNAMENT_ID", "rcr_trmt_id");
define ("TABLE_RCR_TOURNAMENT_NAME", "rcr_trmt_name");

define ("TABLE_RCR_GAME_ID", "bmjr_rcr_game_id");
define ("TABLE_RCR_GAME_ID_ID", "rgi_id");
define ("TABLE_RCR_GAME_ID_DATE", "rgi_date");
define ("TABLE_RCR_GAME_ID_TOURNAMENT_ID", "rgi_trmt_id");
define ("TABLE_RCR_GAME_ID_NB_PLAYERS", "rgi_nb_players");
define ("TABLE_RCR_GAME_ID_NB_ROUNDS", "rgi_nb_rounds");

define ("TABLE_RCR_GAME_SCORE", "bmjr_rcr_game_score");
define ("TABLE_RCR_GAME_SCORE_GAME_ID", "rgs_game_id");
define ("TABLE_RCR_GAME_SCORE_PLAYER_ID", "rgs_player_id");
define ("TABLE_RCR_GAME_SCORE_RANKING", "rgs_ranking");
define ("TABLE_RCR_GAME_SCORE_GAME_SCORE", "rgs_game_score");
define ("TABLE_RCR_GAME_SCORE_UMA_SCORE", "rgs_uma_score");
define ("TABLE_RCR_GAME_SCORE_FINAL_SCORE", "rgs_final_score");
?>