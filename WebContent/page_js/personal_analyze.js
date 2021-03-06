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
		selectTrimester.style.visibility = "visible";
		selectMonth.style.visibility = "hidden";
		selectDay.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 3) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "visible";
		selectDay.style.visibility = "hidden";
	} else if (selectPeriod.selectedIndex == 4) {
		selectYear.style.visibility = "visible";
		selectTrimester.style.visibility = "hidden";
		selectMonth.style.visibility = "visible";
		selectDay.style.visibility = "visible";
	}
}

function displayStat(data, scoreMode) {
	if (data !== null) {
		document.getElementById("nbGames").innerHTML = parseInt(data.numberOfGames).toLocaleString("fr-fr");
		document.getElementById("scoreMax").innerHTML = parseInt(data.scoreMax).toLocaleString("fr-fr");
		document.getElementById("scoreMin").innerHTML = parseInt(data.scoreMin).toLocaleString("fr-fr");
		document.getElementById("positive").innerHTML = parseInt(data.positiveGames).toLocaleString("fr-fr");
		document.getElementById("positivePercent").innerHTML = data.positiveGamesPercent.toString() + "%";
		document.getElementById("negative").innerHTML = parseInt(data.negativeGames).toLocaleString("fr-fr");
		document.getElementById("negativePercent").innerHTML = data.negativeGamesPercent.toString() + "%";
		document.getElementById("scoreTotal").innerHTML = parseInt(data.scoreTotal).toLocaleString("fr-fr");
		document.getElementById("scoreMean").innerHTML = parseInt(data.scoreMean.toString()).toLocaleString("fr-fr") + " ("
				+ parseInt(data.scoreStandardDeviation.toString()).toLocaleString("fr-fr") + ")";
		document.getElementById("positiveTotal").innerHTML = parseInt(data.positiveTotal).toLocaleString("fr-fr");
		document.getElementById("negativeTotal").innerHTML = parseInt(data.negativeTotal).toLocaleString("fr-fr");

		if (data.numberOfFourPlayerGames > 0) {
			document.getElementById("nbFourPlayerGames").innerHTML = parseInt(data.numberOfFourPlayerGames).toLocaleString("fr-fr");
			document.getElementById("fourPlayer1").innerHTML = parseInt(data.fourPlayerGamePlaces[0]).toLocaleString("fr-fr");
			document.getElementById("fourPlayer2").innerHTML = parseInt(data.fourPlayerGamePlaces[1]).toLocaleString("fr-fr");
			document.getElementById("fourPlayer3").innerHTML = parseInt(data.fourPlayerGamePlaces[2]).toLocaleString("fr-fr");
			document.getElementById("fourPlayer4").innerHTML = parseInt(data.fourPlayerGamePlaces[3]).toLocaleString("fr-fr");
			document.getElementById("fourPlayer1percent").innerHTML = data.fourPlayerGamePlacePercent[0].toString() + "%";
			document.getElementById("fourPlayer2percent").innerHTML = data.fourPlayerGamePlacePercent[1].toString() + "%";
			document.getElementById("fourPlayer3percent").innerHTML = data.fourPlayerGamePlacePercent[2].toString() + "%";
			document.getElementById("fourPlayer4percent").innerHTML = data.fourPlayerGamePlacePercent[3].toString() + "%";
		} else {
			document.getElementById("nbFourPlayerGames").innerHTML = "0";
			document.getElementById("fourPlayer1").innerHTML = "0";
			document.getElementById("fourPlayer2").innerHTML = "0";
			document.getElementById("fourPlayer3").innerHTML = "0";
			document.getElementById("fourPlayer4").innerHTML = "0";
			document.getElementById("fourPlayer1percent").innerHTML = "0%";
			document.getElementById("fourPlayer2percent").innerHTML = "0%";
			document.getElementById("fourPlayer3percent").innerHTML = "0%";
			document.getElementById("fourPlayer4percent").innerHTML = "0%";
		}
		if (data.numberOfFivePlayerGames > 0) {
			document.getElementById("nbFivePlayerGames").innerHTML = parseInt(data.numberOfFivePlayerGames).toLocaleString("fr-fr");
			document.getElementById("fivePlayer1").innerHTML = parseInt(data.fivePlayerGamePlaces[0]).toLocaleString("fr-fr");
			document.getElementById("fivePlayer2").innerHTML = parseInt(data.fivePlayerGamePlaces[1]).toLocaleString("fr-fr");
			document.getElementById("fivePlayer3").innerHTML = parseInt(data.fivePlayerGamePlaces[2]).toLocaleString("fr-fr");
			document.getElementById("fivePlayer4").innerHTML = parseInt(data.fivePlayerGamePlaces[3]).toLocaleString("fr-fr");
			document.getElementById("fivePlayer5").innerHTML = parseInt(data.fivePlayerGamePlaces[4]).toLocaleString("fr-fr");
			document.getElementById("fivePlayer1percent").innerHTML = data.fivePlayerGamePlacePercent[0].toString() + "%";
			document.getElementById("fivePlayer2percent").innerHTML = data.fivePlayerGamePlacePercent[1].toString() + "%";
			document.getElementById("fivePlayer3percent").innerHTML = data.fivePlayerGamePlacePercent[2].toString() + "%";
			document.getElementById("fivePlayer4percent").innerHTML = data.fivePlayerGamePlacePercent[3].toString() + "%";
			document.getElementById("fivePlayer5percent").innerHTML = data.fivePlayerGamePlacePercent[4].toString() + "%";
		} else {
			document.getElementById("nbFivePlayerGames").innerHTML = "0";
			document.getElementById("fivePlayer1").innerHTML = "0";
			document.getElementById("fivePlayer2").innerHTML = "0";
			document.getElementById("fivePlayer3").innerHTML = "0";
			document.getElementById("fivePlayer4").innerHTML = "0";
			document.getElementById("fivePlayer5").innerHTML = "0";
			document.getElementById("fivePlayer1percent").innerHTML = "0%";
			document.getElementById("fivePlayer2percent").innerHTML = "0%";
			document.getElementById("fivePlayer3percent").innerHTML = "0%";
			document.getElementById("fivePlayer4percent").innerHTML = "0%";
			document.getElementById("fivePlayer5percent").innerHTML = "0%";
		}

		var dateOptions = {
			year : 'numeric',
			month : 'short',
			day : 'numeric'
		};
		var index;
		var scoreList = [];
		var sumList = [];
		var sum = 0;
		for (index = 0; index < data.listScore.length; index++) {
			var dataDate = data.listDate[index];
			var date = new Date(dataDate.year, dataDate.month, dataDate.day, 0, 0, 0, 0);
			var dateString = date.toLocaleDateString("fr-fr", dateOptions) + " (" + dataDate.id.toString() + ")";

			var score = data.listScore[index];
			scoreList.push([ dateString, score ]);
			sum += score;
			sumList.push([ dateString, sum ]);
		}

		var scoreChart = {
			"colors" : [ "#ff7f00" ],
			"title" : {
				"text" : "Score"
			},
			"xAxis" : {
				"type" : "category",
				"tickInterval" : Math.ceil(data.listScore.length / 10)
			},
			"yAxis" : {
				"title" : {
					"text" : "Score"
				}
			},
			"legend" : {
				"enabled" : true
			},
			"series" : [ {
				"type" : "column",
				"name" : "Score",
				"data" : scoreList,
				"dataLabels" : {
					"enabled" : false
				},
				"animation" : false
			} ]
		};
		$("#scoreChart").highcharts(scoreChart);

		var sumChart = {
			"colors" : [ "#0000ff" ],
			"title" : {
				"text" : "Total"
			},
			"xAxis" : {
				"type" : "category",
				"tickInterval" : Math.ceil(data.listScore.length / 10)
			},
			"yAxis" : {
				"title" : {
					"text" : "Total"
				}
			},
			"legend" : {
				"enabled" : true
			},
			"series" : [ {
				"type" : "area",
				"name" : "Total",
				"data" : sumList,
				"dataLabels" : {
					"enabled" : false
				},
				"animation" : false
			} ]
		};
		$("#sumChart").highcharts(sumChart);
	}
}

function getStat() {
	toggleSelect();
	showLoading();
	var selectTournament = document.getElementById("selectTournament");
	var selectPlayer = document.getElementById("selectPlayer");
	var selectScore = document.getElementById("selectScore");
	var selectPeriod = document.getElementById("selectPeriod");
	var selectYear = document.getElementById("selectYear");
	var selectTrimester = document.getElementById("selectTrimester");
	var selectMonth = document.getElementById("selectMonth");
	var selectDay = document.getElementById("selectDay");

	if (selectTournament.selectedIndex !== -1 
		&& selectPlayer.selectedIndex !== -1 
		&& selectYear.selectedIndex !== -1) {
		$.ajax({
			url : SERVER_QUERY_URL,
			type : "POST",
			data : {
				"action" : "getRCRPersonalAnalyze",
				"tournamentId" : selectTournament.options[selectTournament.selectedIndex].value,
				"playerId" : selectPlayer.options[selectPlayer.selectedIndex].value,
				"scoreMode" : selectScore.options[selectScore.selectedIndex].value,
				"periodMode" : selectPeriod.options[selectPeriod.selectedIndex].value,
				"year" : selectYear.options[selectYear.selectedIndex].value,
				"trimester" : selectTrimester.options[selectTrimester.selectedIndex].value,
				"month" : selectMonth.options[selectMonth.selectedIndex].value,
				"day" : selectDay.selectedIndex !== -1 ? selectDay.options[selectDay.selectedIndex].value : null
			},
			success : function(result) {
				hideLoading();
				data = $.parseJSON(result);
				displayStat(data, selectScore.selectedIndex);
			},
			error : function(xhr, status, error) {
				hideLoading();
			}
		});
	} else {
		displayStat(null, selectScore.selectedIndex);
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

function getPlayers(selectedPlayer) {
	$.ajax({
		url : SERVER_QUERY_URL,
		type : "POST",
		data : {
			"action" : "getRegularRCRPlayers"
		},
		success : function(result) {
			players = $.parseJSON(result);
			players.sort(function(player1, player2) {
				return player1.name.toUpperCase().localeCompare(player2.name.toUpperCase());
			});
			var index;
			var selectPlayer = document.getElementById("selectPlayer");
			selectPlayer.options.length = 0;
			for (index = 0; index < players.length; index++) {
				player = players[index];
				var option = document.createElement("option");
				option.value = player.id;
				option.innerHTML = player.name;
				if (selectedPlayer === player.name) {
					option.selected = "selected";
				}
				selectPlayer.appendChild(option);
			}
		}
	});
}

function prepare() {
	var url = decodeURIComponent(window.location.href);
	var regex = new RegExp("[?&]player=([^&#]*)");
	var results = regex.exec(url);
	var player = results && results[1] ? results[1] : "";
	toggleSelect();
	getPlayers(player);
	getTournaments();
	document.getElementById("selectPlayer").onchange = getStat;
	document.getElementById("selectTournament").onchange = getYears;
	document.getElementById("selectScore").onchange = getStat;

	document.getElementById("selectPeriod").onchange = getStat;
	document.getElementById("selectYear").onchange = getDays;
	document.getElementById("selectTrimester").onchange = getStat;
	document.getElementById("selectMonth").onchange = getDays;
	document.getElementById("selectDay").onchange = getStat;
}

$(document).ready(prepare());