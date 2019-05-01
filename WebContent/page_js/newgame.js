var umaSet = [ [ [ 15000, 5000, -5000, -15000 ], [ 15000, 5000, 0, -5000, -15000 ] ], [ [ 30000, 10000, -10000, -30000 ], [ 30000, 10000, 0, -10000, -30000 ] ] ];

var inputDate;
var selectTournament;
var selectPlayer;
var selectRound;
var inputInitScore;
var selectUma;
var scoreError;

var buttonCalculate;
var buttonSave;
var buttonReset;

var nodeRankings;
var nodePlayers;
var nodeInputScores;
var nodeUmas;
var nodeScores;
var scoreList;

function calculate() {
	var nbPlayersIndex = selectPlayer.selectedIndex;
	var nbPlayers = parseInt(selectPlayer.options[nbPlayersIndex].value);
	var umaSetIndex = selectUma.selectedIndex;
	var initialScore = parseInt(inputInitScore.value);
	scoreList = [];
	var totalScore = 0;
	var index;

	hideSaveButton();

	for (var playerIndex = 0; playerIndex < nbPlayers; playerIndex++) {
		// Check if all players are selected
		var selectedPlayerIndex = nodePlayers[playerIndex].selectedIndex;
		if (selectedPlayerIndex == 0) {
			window.alert("Veuillez choisir tous les joueurs");
			scoreList = [];
			return;
		}

		var playerScore = {};
		scoreList.push(playerScore);
		playerScore.index = selectedPlayerIndex;
		playerScore.playerId = parseInt(nodePlayers[playerIndex][selectedPlayerIndex].value);

		// Check player id is not present more than once
		for (var innerIndex = 0; innerIndex < playerIndex; innerIndex++) {
			if (scoreList[innerIndex].index == playerScore.index) {
				window.alert("Un joueur ne peut être présent qu'une seule fois");
				scoreList = [];
				return;
			}
		}

		// Check game score
		playerScore.gameScore = parseInt(nodeInputScores[playerIndex].value);
		if (playerScore.gameScore % 100 != 0) {
			window.alert("Certain score n'est pas multiple de 100");
			scoreList = [];
			return;
		}
		playerScore.finalScore = playerScore.gameScore - initialScore;
		totalScore += playerScore.gameScore;
	}

	// Check total score
	var sumDifference = totalScore - initialScore * nbPlayers;
	if (sumDifference != 0) {
		window.alert("Le total des scores n'est pas correct");
		scoreError.innerHTML = sumDifference;
		scoreList = [];
		return;
	} else {
		scoreError.innerHTML = "";
	}

	// If initial score is not 30000, adjust
	if (initialScore != 30000) {
		var diff = initialScore - 30000;
		for (var playerIndex = 0; playerIndex < nbPlayers; playerIndex++) {
			scoreList[playerIndex].gameScore -= diff;
		}
		// inputInitScore.value = 30000;
		window.alert("Les stacks ont été ajustés à la base de 30000");
	}

	// Sort score list
	scoreList.sort(function(a, b) {
		return b.gameScore - a.gameScore
	});

	// Distribute uma
	var uma = umaSet[umaSetIndex][nbPlayersIndex];
	var playerIndex = 0;
	while (playerIndex < nbPlayers) {
		// equalityPlayerIndex: index + 1 of last player who has the same game
		// score than playerIndex
		var equalityPlayerIndex = playerIndex + 1;
		while (equalityPlayerIndex < nbPlayers && scoreList[playerIndex].gameScore == scoreList[equalityPlayerIndex].gameScore) {
			equalityPlayerIndex++;
		}
		var totalUma = 0;
		for (index = playerIndex; index < equalityPlayerIndex; index++) {
			totalUma += uma[index];
		}
		totalUma /= equalityPlayerIndex - playerIndex;
		for (index = playerIndex; index < equalityPlayerIndex; index++) {
			scoreList[index].umaScore = totalUma;
			scoreList[index].finalScore += totalUma;
			scoreList[index].place = playerIndex + 1;
		}
		playerIndex = equalityPlayerIndex;
	}

	// Adjust score according to number of rounds
	switch (selectRound.selectedIndex) {
		case 0:
			for (var index = 0; index < nbPlayers; index++) {
				scoreList[index].finalScore /= 2;
			}
			break;
		case 2:
			for (var index = 0; index < nbPlayers; index++) {
				scoreList[index].finalScore *= 2;
			}
			break;
		default:
			break;
	}

	// Update display
	for (var index = 0; index < nbPlayers; index++) {
		nodeRankings[index].innerHTML = scoreList[index].place;
		nodePlayers[index].selectedIndex = scoreList[index].index;
		nodeInputScores[index].value = scoreList[index].gameScore;
		nodeUmas[index].innerHTML = scoreList[index].umaScore;
		nodeScores[index].innerHTML = scoreList[index].finalScore;
	}

	showSaveButton();
}

function save() {
	if (scoreList.length > 0) {
		disableButtons();
		scores = [];
		for (var index = 0; index < scoreList.length; index++) {
			scores.push({
				"playerId" : scoreList[index].playerId,
				"ranking" : scoreList[index].place,
				"gameScore" : scoreList[index].gameScore,
				"umaScore" : scoreList[index].umaScore,
				"finalScore" : scoreList[index].finalScore
			});
		}
		var dateString = inputDate.value;
		var game = {
			"tournamentId" : parseInt(selectTournament[selectTournament.selectedIndex].value),
			"nbRounds" : parseInt(selectRound[selectRound.selectedIndex].value),
			"nbPlayers" : parseInt(selectPlayer[selectPlayer.selectedIndex].value),
			"year" : parseInt(dateString.substring(0, 4)),
			"month" : parseInt(dateString.substring(5, 7)) - 1,
			"day" : parseInt(dateString.substring(8, 10)),
			"scores" : scores
		}
		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "addRCRGame",
				"game" : JSON.stringify(game)
			},
			success : function(result) {
				updateResult = $.parseJSON(result);
				if (updateResult.result) {
					window.alert(updateResult.message);
					reset();
				} else {
					window.alert(updateResult.message);
					enableButtons();
				}
			},
			error : function(xhr, status, error) {
				enableButtons();
			}
		});
	}
}

function disableButtons() {
	buttonCalculate.disabled = true;
	buttonSave.disabled = true;
	buttonReset.disabled = true;
}

function enableButtons() {
	buttonCalculate.disabled = false;
	buttonSave.disabled = false;
	buttonReset.disabled = false;
}

function hideSaveButton() {
	buttonSave.style.visibility = "hidden";
}

function showSaveButton() {
	buttonSave.style.visibility = "visible";
}

function reset() {
	// inputInitScore.value = 30000;
	scoreError.innerHTML = "";
	for (var index = 0; index < 5; index++) {
		nodePlayers[index].selectedIndex = 0;
		nodeRankings[index].innerHTML = "?";
		nodeInputScores[index].value = "";
		nodeUmas[index].innerHTML = "";
		nodeScores[index].innerHTML = "";
	}
	enableButtons();
	hideSaveButton();
}

function toggleFifthPlayer() {
	if (selectPlayer.options[selectPlayer.selectedIndex].value == 5) {
		nodeRankings[4].style.visibility = "visible";
		nodePlayers[4].style.visibility = "visible";
		nodeInputScores[4].style.visibility = "visible";
		nodeUmas[4].style.visibility = "visible";
		nodeScores[4].style.visibility = "visible";
	} else {
		nodeRankings[4].style.visibility = "hidden";
		nodePlayers[4].style.visibility = "hidden";
		nodeInputScores[4].style.visibility = "hidden";
		nodeUmas[4].style.visibility = "hidden";
		nodeScores[4].style.visibility = "hidden";
	}
}

function getPlayers() {
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "getNonHiddenPlayers"
		},
		success : function(result) {
			players = $.parseJSON(result);
			players.sort(function(player1, player2) {
				return player1.name.toUpperCase().localeCompare(player2.name.toUpperCase());
			});
			var index;
			for (index = 0; index < nodePlayers.length; index++) {
				nodePlayers[index].options.length = 0;
				var option = document.createElement("option");
				option.value = 0;
				option.innerHTML = "";
				nodePlayers[index].appendChild(option);
			}
			if (isAdmin == "1") {
				for (index = 0; index < players.length; index++) {
					player = players[index];
					for (playerIndex = 0; playerIndex < nodePlayers.length; playerIndex++) {
						var option = document.createElement("option");
						option.value = player.id;
						option.innerHTML = player.name + " - " + player.id;
						nodePlayers[playerIndex].appendChild(option);
					}
				}
			} else {
				for (index = 0; index < players.length; index++) {
					player = players[index];
					for (playerIndex = 0; playerIndex < nodePlayers.length; playerIndex++) {
						var option = document.createElement("option");
						option.value = player.id;
						option.innerHTML = player.name;
						nodePlayers[playerIndex].appendChild(option);
					}
				}
			}
		}
	});
}

function getTournaments() {
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "getRCRTournaments"
		},
		success : function(result) {
			tournaments = $.parseJSON(result);
			var index;
			selectTournament.options.length = 0;
			for (index = 0; index < tournaments.length; index++) {
				tournament = tournaments[index];
				var option = document.createElement("option");
				option.value = tournament.id;
				option.innerHTML = tournament.name;
				selectTournament.appendChild(option);
			}
		}
	});
}

function prepare() {
	inputDate = document.getElementById("inputDate");
	selectTournament = document.getElementById("selectTournament");
	selectPlayer = document.getElementById("selectPlayer");
	selectRound = document.getElementById("selectRounds");
	inputInitScore = document.getElementById("inputInitGameScore");
	selectUma = document.getElementById("selectUma");
	scoreError = document.getElementById("scoreError");

	buttonCalculate = document.getElementById("buttonCalculate");
	buttonSave = document.getElementById("buttonSave");
	buttonReset = document.getElementById("buttonReset");

	nodeRankings = [ document.getElementById("ranking1"), document.getElementById("ranking2"), document.getElementById("ranking3"),
			document.getElementById("ranking4"), document.getElementById("ranking5") ]
	nodePlayers = [ document.getElementById("selectPlayer1"), document.getElementById("selectPlayer2"), document.getElementById("selectPlayer3"),
			document.getElementById("selectPlayer4"), document.getElementById("selectPlayer5") ];
	nodeInputScores = [ document.getElementById("inputScore1"), document.getElementById("inputScore2"), document.getElementById("inputScore3"),
			document.getElementById("inputScore4"), document.getElementById("inputScore5") ];
	nodeUmas = [ document.getElementById("uma1"), document.getElementById("uma2"), document.getElementById("uma3"), document.getElementById("uma4"),
			document.getElementById("uma5") ];
	nodeScores = [ document.getElementById("score1"), document.getElementById("score2"), document.getElementById("score3"), document.getElementById("score4"),
			document.getElementById("score5") ];
	scoreList = [];

	getTournaments();
	getPlayers();
	toggleFifthPlayer();
	reset();

	document.getElementById("inputDate").valueAsDate = new Date();
	document.getElementById("selectPlayer").onchange = toggleFifthPlayer;
	buttonCalculate.onclick = calculate;
	buttonSave.onclick = save;
	buttonReset.onclick = reset;
}

$(document).ready(prepare());