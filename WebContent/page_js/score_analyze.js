var outerTable = document.getElementById("scoreOuterTable");
var topPlayerNameScroll = document.getElementById("topPlayerNameScroll");
var leftPlayerNameScroll = document.getElementById("leftPlayerNameScroll");
var scoreTableScroll = document.getElementById("scoreTableScroll");

function toggleSelect() {
	var selectPeriod = document.getElementById("selectPeriod");
	var selectYear = document.getElementById("selectYear");
	var selectTrimester = document.getElementById("selectTrimester");
	var selectMonth = document.getElementById("selectMonth");
	var selectDay = document.getElementById("selectDay");

	if (selectPeriod.selectedIndex == 0) {
		selectYear.style.visibility = "hidden";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "hidden";
		selectDay.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 1) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "hidden";
		selectDay.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 2) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "hidden";
		selectDay.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 3) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "visible";
		selectMonth.style.visibility = "hidden";
		selectDay.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 4) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "visible";
		selectDay.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 5) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "visible";
		selectDay.style.visibility = "visible";
	}
}

function synchronizeScrolls() {
	topPlayerNameScroll.scrollLeft = scoreTableScroll.scrollLeft;
	leftPlayerNameScroll.scrollTop = scoreTableScroll.scrollTop;
}

function updateScroll() {
	var windowWidth = $(window).width();
	var windowHeight = $(window).height();

	topPlayerNameScroll.style.minWidth = (windowWidth - 178) + "px";
	topPlayerNameScroll.style.maxWidth = (windowWidth - 178) + "px";
	topPlayerNameScroll.style.width = (windowWidth - 178) + "px";

	leftPlayerNameScroll.style.minHeight = (windowHeight - 368) + "px";
	leftPlayerNameScroll.style.maxHeight = (windowHeight - 368) + "px";
	leftPlayerNameScroll.style.height = (windowHeight - 368) + "px";

	scoreTableScroll.style.minWidth = (windowWidth - 160) + "px";
	scoreTableScroll.style.maxWidth = (windowWidth - 160) + "px";
	scoreTableScroll.style.width = (windowWidth - 160) + "px";
	scoreTableScroll.style.minHeight = (windowHeight - 350) + "px";
	scoreTableScroll.style.maxHeight = (windowHeight - 350) + "px";
	scoreTableScroll.style.height = (windowHeight - 350) + "px";
}

function displayStat(stat) {
	var topPlayerNameTable = document.createElement("table");
	topPlayerNameTable.border = "2px solid gray";
	var leftPlayerNameTable = document.createElement("table");
	leftPlayerNameTable.border = "2px solid gray";
	var scoreTable = document.createElement("table");
	scoreTable.border = "2px solid gray";

	if (stat !== null) {
		var x, y;
		var nbPlayers = stat.players.length;

		{
			var titleLine = document.createElement("tr");
			topPlayerNameTable.appendChild(titleLine);
			for (x = 0; x < nbPlayers; x++) {
				var titlePlayerName = document.createElement("th");
				titlePlayerName.style.width = "72px";
				titlePlayerName.style.minWidth = "72px";
				titlePlayerName.style.maxWidth = "72px";
				titlePlayerName.style.height = "143px";
				titlePlayerName.style.minHeight = "143px";
				titlePlayerName.style.maxHeight = "143px";
				titlePlayerName.style.textAlign = "center";
				titleLine.appendChild(titlePlayerName);

				var titlePlayerNameSpan = document.createElement("span");
				titlePlayerNameSpan.style.writingMode = "vertical-rl";
				titlePlayerNameSpan.innerHTML = stat.players[x];
				titlePlayerName.appendChild(titlePlayerNameSpan);
			}
		}

		{
			for (y = 0; y < nbPlayers; y++) {
				var line = document.createElement("tr");
				leftPlayerNameTable.appendChild(line);

				var titlePlayerName = document.createElement("th");
				titlePlayerName.style.width = "143px";
				titlePlayerName.style.minWidth = "143px";
				titlePlayerName.style.maxWidth = "143px";
				titlePlayerName.style.height = "24px";
				titlePlayerName.style.minHeight = "24px";
				titlePlayerName.style.maxHeight = "24px";
				titlePlayerName.style.textAlign = "center";
				titlePlayerName.innerHTML = stat.players[y];
				line.appendChild(titlePlayerName);
			}

			var line = document.createElement("tr");
			leftPlayerNameTable.appendChild(line);
			var sumTitle = document.createElement("th");
			sumTitle.style.width = "72px";
			sumTitle.style.minWidth = "72px";
			sumTitle.style.maxWidth = "72px";
			sumTitle.style.height = "24px";
			sumTitle.style.minHeight = "24px";
			sumTitle.style.maxHeight = "24px";
			sumTitle.style.textAlign = "center";
			sumTitle.style.borderTopWidth = "2px";
			sumTitle.innerHTML = "Somme";
			leftPlayerNameTable.appendChild(sumTitle);

		}

		{
			for (y = 0; y < nbPlayers; y++) {
				var line = document.createElement("tr");
				scoreTable.appendChild(line);
				for (x = 0; x < nbPlayers; x++) {
					var playerScore = document.createElement("td");
					playerScore.style.width = "72px";
					playerScore.style.minWidth = "72px";
					playerScore.style.maxWidth = "72px";
					playerScore.style.height = "24px";
					playerScore.style.minHeight = "24px";
					playerScore.style.maxHeight = "24px";
					playerScore.align = "center";
					if (x == y) {
						playerScore.style.backgroundColor = "#000000";
					} else {
						playerScore.innerHTML = stat.scores[x][y];
						if (y % 2 == 0) {
							playerScore.style.backgroundColor = "#DFDFDF";
						}
					}
					line.appendChild(playerScore);
				}
			}

			var sumLine = document.createElement("tr");
			scoreTable.appendChild(sumLine);

			for (x = 0; x < nbPlayers; x++) {
				var sumScore = document.createElement("td");
				sumScore.style.width = "72px";
				sumScore.style.minWidth = "72px";
				sumScore.style.maxWidth = "72px";
				sumScore.style.height = "24px";
				sumScore.style.minHeight = "24px";
				sumScore.style.maxHeight = "24px";
				sumScore.align = "center";
				sumScore.style.borderTopWidth = "2px";
				sumScore.innerHTML = stat.sums[x];
				sumLine.appendChild(sumScore);
			}
		}
	}
	updateScroll();
	topPlayerNameScroll.appendChild(topPlayerNameTable);
	leftPlayerNameScroll.appendChild(leftPlayerNameTable);
	scoreTableScroll.appendChild(scoreTable);
}

function getStat() {
	toggleSelect();
	showLoading();
	outerTable.style.display = "none";

	var selectTournament = document.getElementById("selectTournament");
	var selectedTournamentIndex = selectTournament.selectedIndex;
	var selectPeriod = document.getElementById("selectPeriod");
	var selectYear = document.getElementById("selectYear");
	var selectedYearIndex = selectYear.selectedIndex;
	var selectTrimester = document.getElementById("selectTrimester");
	var selectMonth = document.getElementById("selectMonth");
	var selectDay = document.getElementById("selectDay");

	if (selectedTournamentIndex !== -1 && selectedYearIndex !== -1) {
		while (topPlayerNameScroll.firstChild) {
			topPlayerNameScroll.removeChild(topPlayerNameScroll.firstChild);
		}

		while (leftPlayerNameScroll.firstChild) {
			leftPlayerNameScroll.removeChild(leftPlayerNameScroll.firstChild);
		}

		while (scoreTableScroll.firstChild) {
			scoreTableScroll.removeChild(scoreTableScroll.firstChild);
		}

		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "getRCRScoreAnalyze",
				"tournamentId" : selectTournament.options[selectTournament.selectedIndex].value,
				"periodMode" : selectPeriod.options[selectPeriod.selectedIndex].value,
				"year" : selectYear.options[selectYear.selectedIndex].value,
				"trimester" : selectTrimester.options[selectTrimester.selectedIndex].value,
				"month" : selectMonth.options[selectMonth.selectedIndex].value,
				"day" : selectDay.options[selectDay.selectedIndex].value
			},
			success : function(result) {
				hideLoading();
				data = $.parseJSON(result);
				displayStat(data);
				outerTable.style.display = "table";
			},
			error : function(xhr, status, error) {
				hideLoading();
			}
		});
	} else {
		displayStat(null);
	}
}

function getDays() {
	var selectTournament = document.getElementById("selectTournament");
	var selectedTournamentIndex = selectTournament.selectedIndex;
	var selectYear = document.getElementById("selectYear");
	var selectedYearIndex = selectYear.selectedIndex;
	var selectMonth = document.getElementById("selectMonth");
	if (selectedTournamentIndex !== -1 && selectedYearIndex !== -1) {
		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "getRCRDays",
				"tournamentId" : selectTournament[selectedTournamentIndex].value,
				"year" : selectYear[selectedYearIndex].value,
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
				getStat();
			}
		});
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
			getDays();
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
	document.getElementById("scoreTableScroll").onscroll = synchronizeScrolls;

	document.getElementById("selectPeriod").onchange = getStat;
	document.getElementById("selectYear").onchange = getDays;
	document.getElementById("selectTrimester").onchange = getStat;
	document.getElementById("selectMonth").onchange = getDays;
	document.getElementById("selectDay").onchange = getStat;

	window.onresize = updateScroll;
}

$(document).ready(prepare());