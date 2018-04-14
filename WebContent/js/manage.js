function addPlayer() {
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
	    }
	});
}

function deletePlayer() {
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
	    }
	});
}

function modifyPlayer() {
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
	    }
	});
}

function addTournament() {
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
	    }
	});
}

function deleteTournament() {
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
	    }
	});
}

function modifyTournament() {
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
	    }
	});
}

function prepare() {
	getPlayers();
	getTournaments();
	document.getElementById("buttonAddPlayer").onclick = addPlayer;
	document.getElementById("buttonDeletePlayer").onclick = deletePlayer;
	document.getElementById("buttonModifyPlayer").onclick = modifyPlayer;
	document.getElementById("buttonAddTournament").onclick = addTournament;
	document.getElementById("buttonDeleteTournament").onclick = deleteTournament;
	document.getElementById("buttonModifyTournament").onclick = modifyTournament;
	document.getElementById("buttonDeleteGame").onclick = deleteGame;
}

$(document).ready(prepare());