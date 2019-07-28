<?php
setlocale (LC_TIME, 'fr_FR.utf8', 'fra');

define ("ACTION", "action");

define ("ACTION_LOGIN", "login");
define ("ACTION_LOGIN_PARAM_USER", "username");
define ("ACTION_LOGIN_PARAM_PASSWORD", "password");

define ("ACTION_IS_LOGGED_IN", "isLoggedIn");

define ("ACTION_LOGOUT", "logout");

define ("ACTION_ADD_PLAYER", "addPlayer");
define ("ACTION_ADD_PLAYER_PARAM_NAME", "name");
define ("ACTION_ADD_PLAYER_PARAM_REAL_NAME", "realName");

define ("ACTION_MODIFY_PLAYER", "modifyPlayer");
define ("ACTION_MODIFY_PLAYER_PARAM_ID", "id");
define ("ACTION_MODIFY_PLAYER_PARAM_NAME", "name");
define ("ACTION_MODIFY_PLAYER_PARAM_REAL_NAME", "realName");
define ("ACTION_MODIFY_PLAYER_PARAM_FREQUENT", "frequent");
define ("ACTION_MODIFY_PLAYER_PARAM_REGULAR", "regular");

define ("ACTION_DELETE_PLAYER", "deletePlayer");
define ("ACTION_DELETE_PLAYER_PARAM_ID", "id");

define ("ACTION_GET_ALL_PLAYERS", "getAllPlayers");

define ("ACTION_GET_PLAYERS", "getPlayers");
define ("ACTION_GET_PLAYERS_PARAM_FREQUENT", "frequentPlayersOnly");

define ("ACTION_ADD_RCR_TOURNAMENT", "addRCRTournament");
define ("ACTION_ADD_RCR_TOURNAMENT_PARAM_NAME", "name");

define ("ACTION_MODIFY_RCR_TOURNAMENT", "modifyRCRTournament");
define ("ACTION_MODIFY_RCR_TOURNAMENT_PARAM_ID", "id");
define ("ACTION_MODIFY_RCR_TOURNAMENT_PARAM_NAME", "name");

define ("ACTION_DELETE_RCR_TOURNAMENT", "deleteRCRTournament");
define ("ACTION_DELETE_RCR_TOURNAMENT_PARAM_ID", "id");

define ("ACTION_GET_RCR_TOURNAMENTS", "getRCRTournaments");

define ("ACTION_ADD_RCR_GAME", "addRCRGame");
define ("ACTION_ADD_RCR_GAME_PARAM_GAME", "game");

define ("ACTION_DELETE_RCR_GAME", "deleteRCRGame");
define ("ACTION_DELETE_RCR_GAME_PARAM_ID", "id");

define ("ACTION_GET_REGULAR_RCR_PLAYERS", "getRegularRCRPlayers");

define ("ACTION_GET_RCR_YEARS", "getRCRYears");
define ("ACTION_GET_RCR_YEARS_PARAM_TOURNAMENT_ID", "tournamentId");

define ("ACTION_GET_RCR_DAYS", "getRCRDays");
define ("ACTION_GET_RCR_DAYS_PARAM_TOURNAMENT_ID", "tournamentId");
define ("ACTION_GET_RCR_DAYS_PARAM_YEAR", "year");
define ("ACTION_GET_RCR_DAYS_PARAM_MONTH", "month");

define ("ACTION_GET_RCR_GAME_IDS", "getRCRGameIds");
define ("ACTION_GET_RCR_GAME_IDS_PARAMS_TOURNAMENT_ID", "tournamentId");
define ("ACTION_GET_RCR_GAME_IDS_PARAMS_YEAR", "year");
define ("ACTION_GET_RCR_GAME_IDS_PARAMS_MONTH", "month");
define ("ACTION_GET_RCR_GAME_IDS_PARAMS_DAY", "day");

define ("ACTION_GET_RCR_GAME", "getRCRGame");
define ("ACTION_GET_RCR_GAME_PARAM_ID", "id");

define ("ACTION_GET_RCR_ANALYZE", "getRCRAnalyze");
define ("ACTION_GET_RCR_ANALYZE_PARAM_TOURNAMENT_ID", "tournamentId");
define ("ACTION_GET_RCR_ANALYZE_PARAM_PLAYER_ID", "playerId");
define ("ACTION_GET_RCR_ANALYZE_PARAM_SCORE_MODE", "scoreMode");
define ("ACTION_GET_RCR_ANALYZE_PARAM_PERIOD_MODE", "periodMode");
define ("ACTION_GET_RCR_ANALYZE_PARAM_YEAR", "year");
define ("ACTION_GET_RCR_ANALYZE_PARAM_TRIMESTER", "trimester");
define ("ACTION_GET_RCR_ANALYZE_PARAM_MONTH", "month");
define ("ACTION_GET_RCR_ANALYZE_PARAM_DAY", "day");

define ("ACTION_GET_RCR_RANKING", "getRCRRanking");
define ("ACTION_GET_RCR_RANKING_PARAM_TOURNAMENT_ID", "tournamentId");
define ("ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE", "rankingMode");
define ("ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE", "sortingMode");
define ("ACTION_GET_RCR_RANKING_PARAM_PERIOD_MODE", "periodMode");
define ("ACTION_GET_RCR_RANKING_PARAM_YEAR", "year");
define ("ACTION_GET_RCR_RANKING_PARAM_TRIMESTER", "trimester");
define ("ACTION_GET_RCR_RANKING_PARAM_MONTH", "month");
define ("ACTION_GET_RCR_RANKING_PARAM_DAY", "day");
define ("ACTION_GET_RCR_RANKING_PARAM_USE_MIN_GAMES", "useMinGames");
?>