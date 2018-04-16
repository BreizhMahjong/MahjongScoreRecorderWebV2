var buttonAddPlayer;
var buttonDeletePlayer;
var buttonModifyPlayer;
var buttonAddTournament;
var buttonDeleteTournament;
var buttonModifyTournament;
var buttonDeleteGame;

function addPlayer() {
	disableButtons();
	inputNewPlayerName = document.getElementById("inputNewPlayerName");
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "addPlayer",
			"name" : inputNewPlayerName.value
		},
		success : function(result) {
			updateResult = $.parseJSON(result);
			if (updateResult.result) {
				window.alert("Le joueur a été ajouté");
				inputNewPlayerName.value = "";
				getPlayers();
			} else {
				window.alert(updateResult.message);
			}
			enableButtons();
		},
		error : function(xhr, status, error) {
			enableButtons();
		}
	});
}

function deletePlayer() {
	disableButtons();
	selectPlayer = document.getElementById("selectPlayerName");
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "deletePlayer",
			"id" : selectPlayer[selectPlayer.selectedIndex].value
		},
		success : function(result) {
			updateResult = $.parseJSON(result);
			if (updateResult.result) {
				window.alert("Le joueur a été supprimé");
				getPlayers();
			} else {
				window.alert(updateResult.message);
			}
			enableButtons();
		},
		error : function(xhr, status, error) {
			enableButtons();
		}
	});
}

function modifyPlayer() {
	disableButtons();
	selectPlayer = document.getElementById("selectPlayerName");
	inputNewPlayerName = document.getElementById("inputModifyPlayerName");
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "modifyPlayer",
			"id" : selectPlayer[selectPlayer.selectedIndex].value,
			"name" : inputNewPlayerName.value
		},
		success : function(result) {
			updateResult = $.parseJSON(result);
			if (updateResult.result) {
				window.alert("Le joueur a été modifié");
				inputNewPlayerName.value = "";
				getPlayers();
			} else {
				window.alert(updateResult.message);
			}
			enableButtons();
		},
		error : function(xhr, status, error) {
			enableButtons();
		}
	});
}

function addTournament() {
	disableButtons();
	inputNewTournamentName = document.getElementById("inputNewTournamentName");
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "addRCRTournament",
			"name" : inputNewTournamentName.value
		},
		success : function(result) {
			updateResult = $.parseJSON(result);
			if (updateResult.result) {
				window.alert("Le tournoi a été ajouté");
				inputNewTournamentName.value = "";
				getTournaments();
			} else {
				window.alert(updateResult.message);
			}
			enableButtons();
		},
		error : function(xhr, status, error) {
			enableButtons();
		}
	});
}

function deleteTournament() {
	disableButtons();
	selectTournament = document.getElementById("selectTournamentName");
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "deleteRCRTournament",
			"id" : selectTournament[selectTournament.selectedIndex].value
		},
		success : function(result) {
			updateResult = $.parseJSON(result);
			if (updateResult.result) {
				window.alert("Le tournoi a été supprimé");
				getTournaments();
			} else {
				window.alert(updateResult.message);
			}
			enableButtons();
		},
		error : function(xhr, status, error) {
			enableButtons();
		}
	});
}

function modifyTournament() {
	disableButtons();
	selectTournament = document.getElementById("selectTournamentName");
	inputNewTournamentName = document.getElementById("inputModifyTournamentName");
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "modifyRCRTournament",
			"id" : selectTournament[selectTournament.selectedIndex].value,
			"name" : inputNewTournamentName.value
		},
		success : function(result) {
			updateResult = $.parseJSON(result);
			if (updateResult.result) {
				window.alert("Le tournoi a été modifié");
				inputNewTournamentName.value = "";
				getTournaments();
			} else {
				window.alert(updateResult.message);
			}
			enableButtons();
		},
		error : function(xhr, status, error) {
			enableButtons();
		}
	});
}

function getPlayers() {
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "getAllPlayers"
		},
		success : function(result) {
			players = $.parseJSON(result);
			players.sort(function(player1, player2) {
				return player1.name.toUpperCase().localeCompare(player2.name.toUpperCase());
			});
			playerNode = document.getElementById("selectPlayerName")
			playerNode.options.length = 0;
			for (index = 0; index < players.length; index++) {
				player = players[index];
				var option = document.createElement("option");
				option.value = player.id;
				option.innerHTML = player.name;
				playerNode.appendChild(option);
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
			var selectTournament = document.getElementById("selectTournamentName");
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

function deleteGame() {
	disableButtons();
	inputGameId = document.getElementById("inputDeleteGameId");
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "deleteRCRGame",
			"id" : inputGameId.value
		},
		success : function(result) {
			updateResult = $.parseJSON(result);
			if (updateResult.result) {
				window.alert("La partie a été supprimé");
				inputGameId.value = ""
			} else {
				window.alert(updateResult.message);
			}
			enableButtons();
		},
		error : function(xhr, status, error) {
			enableButtons();
		}
	});
}

function disableButtons() {
	buttonAddPlayer.disabled = true;
	buttonDeletePlayer.disabled = true;
	buttonModifyPlayer.disabled = true;
	buttonAddTournament.disabled = true;
	buttonDeleteTournament.disabled = true;
	buttonModifyTournament.disabled = true;
	buttonDeleteGame.disabled = true;
}

function enableButtons() {
	buttonAddPlayer.disabled = false;
	buttonDeletePlayer.disabled = false;
	buttonModifyPlayer.disabled = false;
	buttonAddTournament.disabled = false;
	buttonDeleteTournament.disabled = false;
	buttonModifyTournament.disabled = false;
	buttonDeleteGame.disabled = false;
}

function prepare() {
	getPlayers();
	getTournaments();
	buttonAddPlayer = document.getElementById("buttonAddPlayer");
	buttonDeletePlayer = document.getElementById("buttonDeletePlayer");
	buttonModifyPlayer = document.getElementById("buttonModifyPlayer");
	buttonAddTournament = document.getElementById("buttonAddTournament");
	buttonDeleteTournament = document.getElementById("buttonDeleteTournament");
	buttonModifyTournament = document.getElementById("buttonModifyTournament");
	buttonDeleteGame = document.getElementById("buttonDeleteGame");

	buttonAddPlayer.onclick = addPlayer;
	buttonDeletePlayer.onclick = deletePlayer;
	buttonModifyPlayer.onclick = modifyPlayer;
	buttonAddTournament.onclick = addTournament;
	buttonDeleteTournament.onclick = deleteTournament;
	buttonModifyTournament.onclick = modifyTournament;
	buttonDeleteGame.onclick = deleteGame;
}

$(document).ready(prepare());