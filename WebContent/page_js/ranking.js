function toggleSelect() {
	var selectRanking = document.getElementById("selectRanking");
	var selectPeriod = document.getElementById("selectPeriod");
	var selectYear = document.getElementById("selectYear");
	var selectTrimester = document.getElementById("selectTrimester");
	var selectMonth = document.getElementById("selectMonth");

	var toHide = false;
	if (selectRanking.selectedIndex < 5) {
		selectPeriod.style.visibility = "visible";
	} else {
		selectPeriod.style.visibility = "hidden";
		toHide = true;
	}
	if (toHide || selectPeriod.selectedIndex == 0) {
		selectYear.style.visibility = "hidden";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 1) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 2) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "visible";
		selectMonth.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 3) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "visible";
	}
}

function displayRanking(selectedRankingIndex, listScores) {
	var table = document.getElementById("rankingTable");
	var title3 = document.getElementById("tableTitle3");
	var title4 = document.getElementById("tableTitle4");
	var tableBody = document.getElementById("rankingTableBody");
	var newTableBody = document.createElement("tbody");
	newTableBody.id = "rankingTableBody";

	switch (selectedRankingIndex) {
		case 0: {// Total
			title3.innerHTML = "Score";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastScore = null;
			for (var index = 0; index < listScores.length; index++) {
				var score = listScores[index];
				if (lastIndex == -1 || lastScore.score != score.score) {
					lastIndex = index;
				}
				lastScore = score;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				colTotalScore.innerHTML = parseInt(score.score).toLocaleString("fr-fr");
				line.appendChild(colTotalScore);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(score.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 1: {// Final Scores
			var dateOptions = {
				weekday : 'short',
				year : 'numeric',
				month : 'short',
				day : 'numeric'
			};
			title3.innerHTML = "Score (UMA)";
			title4.innerHTML = "Date";
			var lastIndex = -1;
			var lastScore = null;
			for (var index = 0; index < listScores.length; index++) {
				var score = listScores[index];
				if (lastIndex == -1 || lastScore.score != score.score) {
					lastIndex = index;
				}
				lastScore = score;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var colScore = document.createElement("td");
				colScore.align = "center";
				colScore.style.width = "25%";
				colScore.innerHTML = "" + parseInt(score.score).toLocaleString("fr-fr") + " (" + parseInt(score.uma).toLocaleString("fr-fr") + ")";
				line.appendChild(colScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				var date = new Date(score.year, score.month, score.day, 0, 0, 0, 0);
				colDate.innerHTML = date.toLocaleDateString("fr-fr", dateOptions);
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 2: { // Mean final score
			title3.innerHTML = "Score moyen";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastScore = null;
			for (var index = 0; index < listScores.length; index++) {
				var score = listScores[index];
				if (lastIndex == -1 || lastScore.score != score.score) {
					lastIndex = index;
				}
				lastScore = score;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				colTotalScore.innerHTML = parseInt(score.score).toLocaleString("fr-fr");
				line.appendChild(colTotalScore);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(score.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 3: { // Stack
			var dateOptions = {
				weekday : 'short',
				year : 'numeric',
				month : 'short',
				day : 'numeric'
			};
			title3.innerHTML = "Stack";
			title4.innerHTML = "Date";
			var lastIndex = -1;
			var lastScore = null;
			for (var index = 0; index < listScores.length; index++) {
				var score = listScores[index];
				if (lastIndex == -1 || lastScore.score != score.score) {
					lastIndex = index;
				}
				lastScore = score;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var colScore = document.createElement("td");
				colScore.align = "center";
				colScore.style.width = "25%";
				colScore.innerHTML = parseInt(score.score).toLocaleString("fr-fr");
				line.appendChild(colScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				var date = new Date(score.year, score.month, score.day, 0, 0, 0, 0);
				colDate.innerHTML = date.toLocaleDateString("fr-fr", dateOptions);
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 4: { // Mean stack
			title3.innerHTML = "Stack moyen";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastScore = null;
			for (var index = 0; index < listScores.length; index++) {
				var score = listScores[index];
				if (lastIndex == -1 || lastScore.score != score.score) {
					lastIndex = index;
				}
				lastScore = score;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				colTotalScore.innerHTML = parseInt(score.score).toLocaleString("fr-fr");
				line.appendChild(colTotalScore);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(score.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 5: { // Annual total
			title3.innerHTML = "Total";
			title4.innerHTML = "Période";
			var lastIndex = -1;
			var lastScore = null;
			for (var index = 0; index < listScores.length; index++) {
				var score = listScores[index];
				if (lastIndex == -1 || lastScore.score != score.score) {
					lastIndex = index;
				}
				lastScore = score;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var colScore = document.createElement("td");
				colScore.align = "center";
				colScore.style.width = "25%";
				colScore.innerHTML = parseInt(score.score).toLocaleString("fr-fr");
				line.appendChild(colScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				colDate.innerHTML = score.year;
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 6: { // Trimesterial total
			var trimesterStrings = [ "1er", "2ème", "3ème", "4ème" ];
			title3.innerHTML = "Total";
			title4.innerHTML = "Période";
			var lastIndex = -1;
			var lastScore = null;
			for (var index = 0; index < listScores.length; index++) {
				var score = listScores[index];
				if (lastIndex == -1 || lastScore.score != score.score) {
					lastIndex = index;
				}
				lastScore = score;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var colScore = document.createElement("td");
				colScore.align = "center";
				colScore.style.width = "25%";
				colScore.innerHTML = parseInt(score.score).toLocaleString("fr-fr");
				line.appendChild(colScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				colDate.innerHTML = trimesterStrings[score.month] + " " + score.year;
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 7: { // Mensual total
			var dateOptions = {
				year : 'numeric',
				month : 'short'
			};
			title3.innerHTML = "Total";
			title4.innerHTML = "Période";
			var lastIndex = -1;
			var lastScore = null;
			for (var index = 0; index < listScores.length; index++) {
				var score = listScores[index];
				if (lastIndex == -1 || lastScore.score != score.score) {
					lastIndex = index;
				}
				lastScore = score;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var colScore = document.createElement("td");
				colScore.align = "center";
				colScore.style.width = "25%";
				colScore.innerHTML = parseInt(score.score).toLocaleString("fr-fr");
				line.appendChild(colScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				var date = new Date(score.year, score.month, 1, 0, 0, 0, 0);
				colDate.innerHTML = date.toLocaleDateString("fr-fr", dateOptions);
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case -1: {
		}
			break;
	}
	table.replaceChild(newTableBody, tableBody);
}

function getRanking() {
	toggleSelect();
	var selectTournament = document.getElementById("selectTournament");
	var selectedTournamentIndex = selectTournament.selectedIndex;
	var selectRanking = document.getElementById("selectRanking");
	var selectedRankingIndex = selectRanking.selectedIndex;
	var selectSorting = document.getElementById("selectSorting");
	var selectPeriod = document.getElementById("selectPeriod");
	var selectYear = document.getElementById("selectYear");
	var selectedYearIndex = selectYear.selectedIndex;
	var selectTrimester = document.getElementById("selectTrimester");
	var selectMonth = document.getElementById("selectMonth");
	showLoading();

	if (selectedTournamentIndex !== -1 && selectedYearIndex !== -1) {
		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "getRCRRanking",
				"tournamentId" : selectTournament.options[selectTournament.selectedIndex].value,
				"rankingMode" : selectRanking.options[selectRanking.selectedIndex].value,
				"sortingMode" : selectSorting.options[selectSorting.selectedIndex].value,
				"periodMode" : selectPeriod.options[selectPeriod.selectedIndex].value,
				"year" : selectYear.options[selectYear.selectedIndex].value,
				"trimester" : selectTrimester.options[selectTrimester.selectedIndex].value,
				"month" : selectMonth.options[selectMonth.selectedIndex].value
			},
			success : function(result) {
				hideLoading();
				listScores = $.parseJSON(result);
				displayRanking(selectedRankingIndex, listScores);
				hideLoading();
			},
			error : function(xhr, status, error) {
				displayRanking(-1, null);
				hideLoading();
			}
		});
	} else {
		displayRanking(-1, null);
	}
}

function getYears() {
	var selectTournament = document.getElementById("selectTournament");
	var selectedTournamentId = selectTournament.options[selectTournament.selectedIndex].value;
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "getRCRYears",
			"tournamentId" : selectedTournamentId
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
			getRanking();
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
	toggleSelect();
	getTournaments();
	document.getElementById("selectTournament").onchange = getYears;
	document.getElementById("selectRanking").onchange = getRanking;
	document.getElementById("selectSorting").onchange = getRanking;
	document.getElementById("selectPeriod").onchange = getRanking;
	document.getElementById("selectYear").onchange = getRanking;
	document.getElementById("selectTrimester").onchange = getRanking;
	document.getElementById("selectMonth").onchange = getRanking;
}

$(document).ready(prepare());