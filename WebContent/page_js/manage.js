var selectModifyPlayer;
var inputModifyPlayerName;
var inputHidden;
var inputRegular;
var selectDeletePlayer;

var buttonAddPlayer;
var buttonDeletePlayer;
var buttonModifyPlayer;

var selectModifyTournament;
var inputModifyTournamentName;
var selectDeleteTournament;

var buttonAddTournament;
var buttonDeleteTournament;
var buttonModifyTournament;
var buttonDeleteGame;

var players;
var tournaments;

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

function displayPlayer() {
	if (selectModifyPlayer.selectedIndex >= 0) {
		inputModifyPlayerName.value = players[selectModifyPlayer.selectedIndex].name;
		inputHidden.checked = players[selectModifyPlayer.selectedIndex].hidden != 0;
		inputRegular.checked = players[selectModifyPlayer.selectedIndex].regular != 0;
	}
}

function deletePlayer() {
	disableButtons();
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "deletePlayer",
			"id" : players[selectDeletePlayer.selectedIndex].id
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
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "modifyPlayer",
			"id" : players[selectModifyPlayer.selectedIndex].id,
			"name" : inputModifyPlayerName.value,
			"hidden" : inputHidden.checked ? "1" : "0",
			"regular" : inputRegular.checked ? "1" : "0"
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

function displayTournament() {
	if (selectModifyTournament.selectedIndex >= 0) {
		inputModifyTournamentName.value = tournaments[selectModifyTournament.selectedIndex].name;
	}
}

function deleteTournament() {
	disableButtons();
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "deleteRCRTournament",
			"id" : tournaments[selectDeleteTournament.selectedIndex].id
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
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "modifyRCRTournament",
			"id" : tournaments[selectModifyTournament.selectedIndex].id,
			"name" : inputModifyTournamentName.value
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
			selectModifyPlayer.options.length = 0;
			selectDeletePlayer.options.length = 0;
			var index;
			for (index = 0; index < players.length; index++) {
				player = players[index];
				{
					var option = document.createElement("option");
					option.value = player.id;
					option.innerHTML = player.name;
					selectModifyPlayer.appendChild(option);
				}
				{
					var option = document.createElement("option");
					option.value = player.id;
					option.innerHTML = player.name;
					selectDeletePlayer.appendChild(option);
				}
			}
			displayPlayer();
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
			selectModifyTournament.options.length = 0;
			selectDeleteTournament.options.length = 0;
			var index;
			for (index = 0; index < tournaments.length; index++) {
				tournament = tournaments[index];
				{
					var option = document.createElement("option");
					option.value = tournament.id;
					option.innerHTML = tournament.name;
					selectModifyTournament.appendChild(option);
				}
				{
					var option = document.createElement("option");
					option.value = tournament.id;
					option.innerHTML = tournament.name;
					selectDeleteTournament.appendChild(option);
				}
			}
			displayTournament();
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
	selectModifyPlayer = document.getElementById("selectModifyPlayer");
	inputModifyPlayerName = document.getElementById("inputModifyPlayerName");
	inputHidden = document.getElementById("inputHidden");
	inputRegular = document.getElementById("inputRegular");
	selectDeletePlayer = document.getElementById("selectDeletePlayer");
	
	selectModifyTournament = document.getElementById("selectModifyTournament");
	inputModifyTournamentName = document.getElementById("inputModifyTournamentName");
	selectDeleteTournament = document.getElementById("selectDeleteTournament");
	
	buttonAddPlayer = document.getElementById("buttonAddPlayer");
	buttonDeletePlayer = document.getElementById("buttonDeletePlayer");
	buttonModifyPlayer = document.getElementById("buttonModifyPlayer");
	buttonAddTournament = document.getElementById("buttonAddTournament");
	buttonDeleteTournament = document.getElementById("buttonDeleteTournament");
	buttonModifyTournament = document.getElementById("buttonModifyTournament");
	buttonDeleteGame = document.getElementById("buttonDeleteGame");

	selectModifyPlayer.onchange = displayPlayer;
	selectModifyTournament.onchange = displayTournament;
	buttonAddPlayer.onclick = addPlayer;
	buttonDeletePlayer.onclick = deletePlayer;
	buttonModifyPlayer.onclick = modifyPlayer;
	buttonAddTournament.onclick = addTournament;
	buttonDeleteTournament.onclick = deleteTournament;
	buttonModifyTournament.onclick = modifyTournament;
	buttonDeleteGame.onclick = deleteGame;

	getPlayers();
	getTournaments();
}

$(document).ready(prepare());