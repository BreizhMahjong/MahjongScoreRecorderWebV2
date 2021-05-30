var inputInitScore;
var buttonDisplay;
var initialScore;

function createGameNode(table, game) {
	var gameInfoLine = document.createElement("tr");
	table.appendChild(gameInfoLine);

	var idGrid = document.createElement("td");
	gameInfoLine.appendChild(idGrid);
	idGrid.colSpan = "2";
	idGrid.style.width = "40%";
	idGrid.style.paddingLeft = "10px";
	idGrid.style.paddingRight = "10px";
	idGrid.style.borderTop = "none";
	idGrid.style.borderBottom = "2px solid gray";
	idGrid.style.borderLeft = "none";
	idGrid.style.borderRight = "none";
	idGrid.innerHTML = "ID : " + game.id;

	var dateGrid = document.createElement("td");
	gameInfoLine.appendChild(dateGrid);
	dateGrid.colSpan = "2";
	dateGrid.style.width = "40%";
	dateGrid.style.paddingLeft = "10px";
	dateGrid.style.paddingRight = "10px";
	dateGrid.style.borderTop = "none";
	dateGrid.style.borderBottom = "2px solid gray";
	dateGrid.style.borderLeft = "none";
	dateGrid.style.borderRight = "none";
	var date = new Date(game.year, game.month, game.day, 0, 0, 0, 0);
	dateGrid.innerHTML = "Date : " + date.toLocaleDateString("fr-fr", {
		weekday : 'long',
		year : 'numeric',
		month : 'long',
		day : 'numeric'
	});

	var nbRoundsGrid = document.createElement("td");
	gameInfoLine.appendChild(nbRoundsGrid);
	nbRoundsGrid.style.width = "20%";
	nbRoundsGrid.style.paddingLeft = "10px";
	nbRoundsGrid.style.paddingRight = "10px";
	nbRoundsGrid.style.borderTop = "none";
	nbRoundsGrid.style.borderBottom = "2px solid gray";
	nbRoundsGrid.style.borderLeft = "none";
	nbRoundsGrid.style.borderRight = "none";
	nbRoundsGrid.innerHTML = "Manches : " + game.nbRounds;

	var titleLine = document.createElement("tr");
	table.appendChild(titleLine);

	var rankingTitleGrid = document.createElement("td");
	titleLine.appendChild(rankingTitleGrid);
	rankingTitleGrid.style.width = "10%";
	rankingTitleGrid.align = "center";
	rankingTitleGrid.innerHTML = "#";

	var nameTitleGrid = document.createElement("td");
	titleLine.appendChild(nameTitleGrid);
	nameTitleGrid.style.width = "30%";
	nameTitleGrid.align = "center";
	nameTitleGrid.innerHTML = "Nom du joueur";

	var gameScoreTitleGrid = document.createElement("td");
	titleLine.appendChild(gameScoreTitleGrid);
	gameScoreTitleGrid.style.width = "20%";
	gameScoreTitleGrid.align = "center";
	gameScoreTitleGrid.innerHTML = "Stack";

	var umaScoreTitleGrid = document.createElement("td");
	titleLine.appendChild(umaScoreTitleGrid);
	umaScoreTitleGrid.style.width = "20%";
	umaScoreTitleGrid.align = "center";
	umaScoreTitleGrid.innerHTML = "UMA";

	var finalScoreTitleGrid = document.createElement("td");
	titleLine.appendChild(finalScoreTitleGrid);
	finalScoreTitleGrid.style.width = "20%";
	finalScoreTitleGrid.align = "center";
	finalScoreTitleGrid.innerHTML = "Score";

	var index;
	for (index = 0; index < game.nbPlayers; index++) {
		var score = game.scores[index];

		var playerLine = document.createElement("tr");
		table.appendChild(playerLine);

		var rankingGrid = document.createElement("td");
		playerLine.appendChild(rankingGrid);
		rankingGrid.style.width = "10%";
		rankingGrid.align = "center";
		rankingGrid.innerHTML = score.ranking;

		var nameGrid = document.createElement("td");
		playerLine.appendChild(nameGrid);
		nameGrid.style.width = "30%";
		nameGrid.style.paddingLeft = "10px";
		nameGrid.style.paddingRight = "10px";
		nameGrid.innerHTML = score.playerName;

		var gameScoreGrid = document.createElement("td");
		playerLine.appendChild(gameScoreGrid);
		gameScoreGrid.style.width = "20%";
		gameScoreGrid.align = "center";
		gameScoreGrid.innerHTML = parseInt(score.gameScore + initialScore).toLocaleString("fr-fr");

		var umaScoreGrid = document.createElement("td");
		playerLine.appendChild(umaScoreGrid);
		umaScoreGrid.style.width = "20%";
		umaScoreGrid.align = "center";
		umaScoreGrid.innerHTML = parseInt(score.umaScore).toLocaleString("fr-fr");

		var finalScore = parseInt(score.finalScore);
		var finalScoreGrid = document.createElement("td");
		playerLine.appendChild(finalScoreGrid);
		finalScoreGrid.style.width = "20%";
		finalScoreGrid.align = "center";
		if (finalScore > 0) {
			finalScoreGrid.innerHTML = "+" + finalScore.toLocaleString("fr-fr");
		} else if (finalScore == 0) {
			finalScoreGrid.innerHTML = "000";
		} else {
			var finalScoreFontGrid = document.createElement("font");
			finalScoreGrid.appendChild(finalScoreFontGrid);
			finalScoreFontGrid.color = "#FF0000";
			finalScoreFontGrid.innerHTML = finalScore.toLocaleString("fr-fr");
		}
	}
}

function displayGames(ids) {
	var gamePanel = document.getElementById("gamePanel");
	var newGamePanel = document.createElement("div");
	newGamePanel.id = "gamePanel";
	newGamePanel.style.width = "640px";
	initialScore = parseInt(inputInitScore.value);
	if (initialScore < 0) {
		initialScore = 0;
		inputInitScore.value = initialScore;
	} else if (initialScore > 35000) {
		initialScore = 35000;
		inputInitScore.value = initialScore;
	}

	var index = 0;
	function getNextGame() {
		table = document.createElement("table");
		table.border = "2px solid gray";
		table.style.margin = "4px";
		table.style.width = "640px";
		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "getRCRGame",
				"id" : ids[index]
			},
			success : function(result) {
				createGameNode(table, $.parseJSON(result));
				newGamePanel.appendChild(table);
				index++;
				if (index < ids.length) {
					getNextGame();
				} else {
					gamePanel.parentNode.replaceChild(newGamePanel, gamePanel);
					hideLoading();
				}
			}
		});
	}
	getNextGame();
}

function getRCRGameIds() {
	var selectTournament = document.getElementById("selectTournament");
	var selectYear = document.getElementById("selectYear");
	var selectMonth = document.getElementById("selectMonth");
	var selectDay = document.getElementById("selectDay");

	if (selectTournament.selectedIndex !== -1 
		&& selectYear.selectedIndex !== -1 
		&& selectDay.selectedIndex !== -1) {
		buttonDisplay.disabled = true;

		var gamePanel = document.getElementById("gamePanel");
		while (gamePanel.firstChild) {
			gamePanel.removeChild(gamePanel.firstChild);
		}
		showLoading();

		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "getRCRGameIds",
				"tournamentId" : selectTournament[selectTournament.selectedIndex].value,
				"year" : selectYear[selectYear.selectedIndex].value,
				"month" : selectMonth.selectedIndex,
				"day" : selectDay[selectDay.selectedIndex].value
			},
			success : function(result) {
				displayGames($.parseJSON(result));
				buttonDisplay.disabled = false;
			},
			error : function(xhr, status, error) {
				hideLoading();
				buttonDisplay.disabled = false;
			}
		});
	}
}

function getDays() {
	var selectTournament = document.getElementById("selectTournament");
	var selectYear = document.getElementById("selectYear");
	var selectMonth = document.getElementById("selectMonth");
	if (selectTournament.selectedIndex !== -1 && selectYear.selectedIndex !== -1) {
		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "getRCRDays",
				"tournamentId" : selectTournament[selectTournament.selectedIndex].value,
				"year" : selectYear[selectYear.selectedIndex].value,
				"month" : selectMonth.selectedIndex
			},
			success : function(result) {
				days = $.parseJSON(result);
				var index;
				var selectDay = document.getElementById("selectDay");
				selectDay.options.length = 0;
				for (index = 0; index < days.length; index++) {
					day = days[index];
					var option = document.createElement("option");
					option.value = day;
					option.innerHTML = day;
					selectDay.appendChild(option);
				}
			}
		});
	}
}

function getYears() {
	var selectTournament = document.getElementById("selectTournament");
	var selectedTournamentIndex = selectTournament.selectedIndex;
	if (selectedTournamentIndex !== -1) {
		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "getRCRYears",
				"tournamentId" : selectTournament.options[selectTournament.selectedIndex].value
			},
			success : function(result) {
				years = $.parseJSON(result);
				var index;
				var selectYear = document.getElementById("selectYear");
				selectYear.options.length = 0;
				for (index = 0; index < years.length; index++) {
					year = years[index];
					var option = document.createElement("option");
					option.value = year;
					option.innerHTML = year;
					selectYear.appendChild(option);
				}
				getDays();
			}
		});
	}
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
			var selectTournament = document.getElementById("selectTournament");
			selectTournament.options.length = 0;
			for (index = 0; index < tournaments.length; index++) {
				tournament = tournaments[index];
				var option = document.createElement("option");
				option.value = tournament.id;
				option.innerHTML = tournament.name;
				selectTournament.appendChild(option);
			}
			getYears();
		}
	});
}

function prepare() {
	getTournaments();
	document.getElementById("selectTournament").onchange = getYears;
	document.getElementById("selectYear").onchange = getDays;
	document.getElementById("selectMonth").onchange = getDays;

	inputInitScore = document.getElementById("inputInitGameScore");
	buttonDisplay = document.getElementById("buttonDisplay");
	buttonDisplay.onclick = getRCRGameIds;
}

$(document).ready(prepare());