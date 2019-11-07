<?php
ini_set ('display_errors', 'On');

require_once ("./db_php/query_config.php");
require_once ("./db_php/query_login_logout.php");
require_once ("./db_php/query_player.php");
require_once ("./db_php/query_rcr_tournament.php");
require_once ("./db_php/query_rcr_game.php");
require_once ("./db_php/query_rcr_ranking.php");
require_once ("./db_php/query_rcr_personal_analyze.php");
require_once ("./db_php/query_rcr_score_analyze.php");

$action = isset ($_POST [ACTION]) ? $_POST [ACTION] : null;
if ($action !== null) {
	switch ($action) {
		case ACTION_LOGIN:
			$userName = isset ($_POST [ACTION_LOGIN_PARAM_USER]) ? $_POST [ACTION_LOGIN_PARAM_USER] : null;
			$password = isset ($_POST [ACTION_LOGIN_PARAM_PASSWORD]) ? $_POST [ACTION_LOGIN_PARAM_PASSWORD] : null;
			echo login ($userName, $password);
			break;
		case ACTION_IS_LOGGED_IN:
			echo isLoggedIn ();
			break;
		case ACTION_LOGOUT:
			echo logout ();
			break;
		case ACTION_ADD_PLAYER:
			$name = isset ($_POST [ACTION_ADD_PLAYER_PARAM_NAME]) ? $_POST [ACTION_ADD_PLAYER_PARAM_NAME] : null;
			$realName = isset ($_POST [ACTION_ADD_PLAYER_PARAM_REAL_NAME]) ? $_POST [ACTION_ADD_PLAYER_PARAM_REAL_NAME] : null;
			echo addPlayer ($name, $realName);
			break;
		case ACTION_MODIFY_PLAYER:
			$playerId = isset ($_POST [ACTION_MODIFY_PLAYER_PARAM_ID]) ? intval ($_POST [ACTION_MODIFY_PLAYER_PARAM_ID]) : null;
			$name = isset ($_POST [ACTION_MODIFY_PLAYER_PARAM_NAME]) ? $_POST [ACTION_MODIFY_PLAYER_PARAM_NAME] : null;
			$realName = isset ($_POST [ACTION_MODIFY_PLAYER_PARAM_REAL_NAME]) ? $_POST [ACTION_MODIFY_PLAYER_PARAM_REAL_NAME] : null;
			$frequent = isset ($_POST [ACTION_MODIFY_PLAYER_PARAM_FREQUENT]) ? intval ($_POST [ACTION_MODIFY_PLAYER_PARAM_FREQUENT]) : null;
			$regular = isset ($_POST [ACTION_MODIFY_PLAYER_PARAM_REGULAR]) ? intval ($_POST [ACTION_MODIFY_PLAYER_PARAM_REGULAR]) : null;
			echo modifyPlayer ($playerId, $name, $realName, $frequent, $regular);
			break;
		case ACTION_DELETE_PLAYER:
			$playerId = isset ($_POST [ACTION_DELETE_PLAYER_PARAM_ID]) ? intval ($_POST [ACTION_DELETE_PLAYER_PARAM_ID]) : null;
			echo deletePlayer ($playerId);
			break;
		case ACTION_GET_ALL_PLAYERS:
			echo getAllPlayers ();
			break;
		case ACTION_GET_PLAYERS:
			$frequentPlayersOnly = isset ($_POST [ACTION_GET_PLAYERS_PARAM_FREQUENT]) ? intval ($_POST [ACTION_GET_PLAYERS_PARAM_FREQUENT]) : null;
			echo getPlayers ($frequentPlayersOnly);
			break;
		case ACTION_ADD_RCR_TOURNAMENT:
			$name = isset ($_POST [ACTION_ADD_RCR_TOURNAMENT_PARAM_NAME]) ? $_POST [ACTION_ADD_RCR_TOURNAMENT_PARAM_NAME] : null;
			echo addRCRTournament ($name);
			break;
		case ACTION_MODIFY_RCR_TOURNAMENT:
			$id = isset ($_POST [ACTION_MODIFY_RCR_TOURNAMENT_PARAM_ID]) ? intval ($_POST [ACTION_MODIFY_RCR_TOURNAMENT_PARAM_ID]) : null;
			$name = isset ($_POST [ACTION_MODIFY_RCR_TOURNAMENT_PARAM_NAME]) ? $_POST [ACTION_MODIFY_RCR_TOURNAMENT_PARAM_NAME] : null;
			echo modifyRCRTournament ($id, $name);
			break;
		case ACTION_DELETE_RCR_TOURNAMENT:
			$id = isset ($_POST [ACTION_DELETE_RCR_TOURNAMENT_PARAM_ID]) ? intval ($_POST [ACTION_DELETE_RCR_TOURNAMENT_PARAM_ID]) : null;
			echo deleteRCRTournament ($id);
			break;
		case ACTION_GET_RCR_TOURNAMENTS:
			echo getRCRTournaments ();
			break;
		case ACTION_ADD_RCR_GAME:
			$game = isset ($_POST [ACTION_ADD_RCR_GAME_PARAM_GAME]) ? $_POST [ACTION_ADD_RCR_GAME_PARAM_GAME] : null;
			echo addRCRGame ($game);
			break;
		case ACTION_DELETE_RCR_GAME:
			$id = isset ($_POST [ACTION_DELETE_RCR_GAME_PARAM_ID]) ? intval ($_POST [ACTION_DELETE_RCR_GAME_PARAM_ID]) : null;
			echo deleteRCRGame ($id);
			break;
		case ACTION_GET_REGULAR_RCR_PLAYERS:
			echo getRegularRCRPlayers ();
			break;
		case ACTION_GET_RCR_YEARS:
			$tournamentId = isset ($_POST [ACTION_GET_RCR_YEARS_PARAM_TOURNAMENT_ID]) ? intval ($_POST [ACTION_GET_RCR_YEARS_PARAM_TOURNAMENT_ID]) : null;
			echo getRCRYears ($tournamentId);
			break;
		case ACTION_GET_RCR_DAYS:
			$tournamentId = isset ($_POST [ACTION_GET_RCR_DAYS_PARAM_TOURNAMENT_ID]) ? intval ($_POST [ACTION_GET_RCR_DAYS_PARAM_TOURNAMENT_ID]) : null;
			$year = isset ($_POST [ACTION_GET_RCR_DAYS_PARAM_YEAR]) ? intval ($_POST [ACTION_GET_RCR_DAYS_PARAM_YEAR]) : null;
			$month = isset ($_POST [ACTION_GET_RCR_DAYS_PARAM_MONTH]) ? intval ($_POST [ACTION_GET_RCR_DAYS_PARAM_MONTH]) : null;
			echo getRCRDays ($tournamentId, $year, $month);
			break;
		case ACTION_GET_RCR_GAME_IDS:
			$tournamentId = isset ($_POST [ACTION_GET_RCR_GAME_IDS_PARAMS_TOURNAMENT_ID]) ? intval ($_POST [ACTION_GET_RCR_GAME_IDS_PARAMS_TOURNAMENT_ID]) : null;
			$year = isset ($_POST [ACTION_GET_RCR_GAME_IDS_PARAMS_YEAR]) ? intval ($_POST [ACTION_GET_RCR_GAME_IDS_PARAMS_YEAR]) : null;
			$month = isset ($_POST [ACTION_GET_RCR_GAME_IDS_PARAMS_MONTH]) ? intval ($_POST [ACTION_GET_RCR_GAME_IDS_PARAMS_MONTH]) : null;
			$day = isset ($_POST [ACTION_GET_RCR_GAME_IDS_PARAMS_DAY]) ? intval ($_POST [ACTION_GET_RCR_GAME_IDS_PARAMS_DAY]) : null;
			echo getRCRGameIds ($tournamentId, $year, $month, $day);
			break;
		case ACTION_GET_RCR_GAME:
			$id = isset ($_POST [ACTION_GET_RCR_GAME_PARAM_ID]) ? intval ($_POST [ACTION_GET_RCR_GAME_PARAM_ID]) : null;
			echo getRCRGame ($id);
			break;
		case ACTION_GET_RCR_PERSONAL_ANALYZE:
			$tournamentId = isset ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_TOURNAMENT_ID]) ? intval ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_TOURNAMENT_ID]) : null;
			$playerId = isset ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_PLAYER_ID]) ? intval ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_PLAYER_ID]) : null;
			$scoreMode = isset ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_SCORE_MODE]) ? $_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_SCORE_MODE] : null;
			$periodMode = isset ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_PERIOD_MODE]) ? $_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_PERIOD_MODE] : null;
			$year = isset ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_YEAR]) ? intval ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_YEAR]) : null;
			$trimester = isset ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_TRIMESTER]) ? intval ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_TRIMESTER]) : null;
			$month = isset ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_MONTH]) ? intval ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_MONTH]) : null;
			$day = isset ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_DAY]) ? intval ($_POST [ACTION_GET_RCR_PERSONAL_ANALYZE_PARAM_DAY]) : null;
			echo getRCRPersonalAnalyze ($tournamentId, $playerId, $scoreMode, $periodMode, $year, $trimester, $month, $day);
			break;
		case ACTION_GET_RCR_SCORE_ANALYZE:
			$tournamentId = isset ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_TOURNAMENT_ID]) ? intval ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_TOURNAMENT_ID]) : null;
			$periodMode = isset ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_PERIOD_MODE]) ? $_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_PERIOD_MODE] : null;
			$year = isset ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_YEAR]) ? intval ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_YEAR]) : null;
			$trimester = isset ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_TRIMESTER]) ? intval ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_TRIMESTER]) : null;
			$month = isset ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_MONTH]) ? intval ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_MONTH]) : null;
			$day = isset ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_DAY]) ? intval ($_POST [ACTION_GET_RCR_SCORE_ANALYZE_PARAM_DAY]) : null;
			echo getRCRScoreAnalyze ($tournamentId, $periodMode, $year, $trimester, $month, $day);
			break;
		case ACTION_GET_RCR_RANKING:
			$tournamentId = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_TOURNAMENT_ID]) ? intval ($_POST [ACTION_GET_RCR_RANKING_PARAM_TOURNAMENT_ID]) : null;
			$rankingMode = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE]) ? $_POST [ACTION_GET_RCR_RANKING_PARAM_RANKING_MODE] : null;
			$sortingMode = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE]) ? $_POST [ACTION_GET_RCR_RANKING_PARAM_SORTING_MODE] : null;
			$periodMode = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_PERIOD_MODE]) ? $_POST [ACTION_GET_RCR_RANKING_PARAM_PERIOD_MODE] : null;
			$year = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_YEAR]) ? intval ($_POST [ACTION_GET_RCR_RANKING_PARAM_YEAR]) : null;
			$trimester = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_TRIMESTER]) ? intval ($_POST [ACTION_GET_RCR_RANKING_PARAM_TRIMESTER]) : null;
			$month = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_MONTH]) ? intval ($_POST [ACTION_GET_RCR_RANKING_PARAM_MONTH]) : null;
			$day = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_DAY]) ? intval ($_POST [ACTION_GET_RCR_RANKING_PARAM_DAY]) : null;
			$useMinGames = isset ($_POST [ACTION_GET_RCR_RANKING_PARAM_USE_MIN_GAMES]) ? boolval ($_POST [ACTION_GET_RCR_RANKING_PARAM_USE_MIN_GAMES]) : false;
			echo getRCRRanking ($tournamentId, $rankingMode, $sortingMode, $periodMode, $year, $trimester, $month, $day, $useMinGames);
			break;
		default:
			break;
	}
}
?>