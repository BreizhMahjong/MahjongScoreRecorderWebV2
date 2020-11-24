function toggleSelect() {
	var selectRanking = document.getElementById("selectRanking");
	var selectPeriod = document.getElementById("selectPeriod");
	var selectYear = document.getElementById("selectYear");
	var selectTrimester = document.getElementById("selectTrimester");
	var selectMonth = document.getElementById("selectMonth");
	var selectDay = document.getElementById("selectDay");

	var toHide = false;
	if (selectRanking.selectedIndex < 10) {
		selectPeriod.style.visibility = "visible";
	} else {
		selectPeriod.style.visibility = "hidden";
		toHide = true;
	}
	if (toHide || selectPeriod.selectedIndex == 0) {
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

function displayRanking(selectedRankingIndex, listScores) {
	var table = document.getElementById("rankingTable");
	var title3 = document.getElementById("tableTitle3");
	var title4 = document.getElementById("tableTitle4");
	var tableBody = document.getElementById("rankingTableBody");
	var newTableBody = document.createElement("tbody");
	newTableBody.id = "rankingTableBody";

	switch (selectedRankingIndex) {
		case 0: {// Total final score
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var totalScore = parseInt(score.score);
				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				if (totalScore > 0) {
					colTotalScore.innerHTML = "+" + totalScore.toLocaleString("fr-fr");
				} else if (totalScore == 0) {
					colTotalScore.innerHTML = "000";
				} else {
					var colTotalScoreFont = document.createElement("font");
					colTotalScore.appendChild(colTotalScoreFont);
					colTotalScoreFont.color = "#FF0000";
					colTotalScoreFont.innerHTML = totalScore.toLocaleString("fr-fr");
				}
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
		case 1: { // Mean final score
			title3.innerHTML = "Score moyen (écart type)";
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var meanScore = parseInt(score.score);
				var colMeanScore = document.createElement("td");
				colMeanScore.align = "center";
				colMeanScore.style.width = "25%";
				if (meanScore > 0) {
					colMeanScore.innerHTML = "+" + meanScore.toLocaleString("fr-fr") + " (" + parseInt(score.uma).toLocaleString("fr-fr") + ")";
				} else if (meanScore == 0) {
					colMeanScore.innerHTML = "0" + " (" + parseInt(score.uma).toLocaleString("fr-fr") + ")";
				} else {
					var colMeanScoreFont = document.createElement("font");
					colMeanScore.appendChild(colMeanScoreFont);
					colMeanScoreFont.color = "#FF0000";
					colMeanScoreFont.innerHTML = meanScore.toLocaleString("fr-fr") + " (" + parseInt(score.uma).toLocaleString("fr-fr") + ")";
				}
				line.appendChild(colMeanScore);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(score.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 2: {// Best final Scores
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var bestScore = parseInt(score.score);
				var colScore = document.createElement("td");
				colScore.align = "center";
				colScore.style.width = "25%";
				if (bestScore > 0) {
					colScore.innerHTML = "+" + bestScore.toLocaleString("fr-fr") + " (" + parseInt(score.uma).toLocaleString("fr-fr") + ")";
				} else if (bestScore == 0) {
					colScore.innerHTML = "00" + " (" + parseInt(score.uma).toLocaleString("fr-fr") + ")";
				} else {
					var colScoreFont = document.createElement("font");
					colScore.appendChild(colScoreFont);
					colScoreFont.color = "#FF0000";
					colScoreFont.innerHTML = bestScore.toLocaleString("fr-fr") + " (" + parseInt(score.uma).toLocaleString("fr-fr") + ")";
				}
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
		case 3: {// Total game score
			title3.innerHTML = "Stack";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastStack = null;
			for (var index = 0; index < listScores.length; index++) {
				var stack = listScores[index];
				if (lastIndex == -1 || lastStack.score != stack.score) {
					lastIndex = index;
				}
				lastStack = stack;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + stack.name + "\">" + stack.name + "</a>";
				line.appendChild(colName);

				var totalStack = parseInt(stack.score);
				var colTotalStack = document.createElement("td");
				colTotalStack.align = "center";
				colTotalStack.style.width = "25%";
				if (totalStack > 0) {
					colTotalStack.innerHTML = "+" + totalStack.toLocaleString("fr-fr");
				} else if (totalStack == 0) {
					colTotalStack.innerHTML = "0";
				} else {
					var colTotalStackFont = document.createElement("font");
					colTotalStack.appendChild(colTotalStackFont);
					colTotalStackFont.color = "#FF0000";
					colTotalStackFont.innerHTML = totalStack.toLocaleString("fr-fr");
				}
				line.appendChild(colTotalStack);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(stack.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 4: { // Mean game score
			title3.innerHTML = "Stack moyen (écart type)";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastStack = null;
			for (var index = 0; index < listScores.length; index++) {
				var stack = listScores[index];
				if (lastIndex == -1 || lastStack.score != stack.score) {
					lastIndex = index;
				}
				lastStack = stack;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + stack.name + "\">" + stack.name + "</a>";
				line.appendChild(colName);

				var meanStack = parseInt(stack.score);
				var colMeanStack = document.createElement("td");
				colMeanStack.align = "center";
				colMeanStack.style.width = "25%";
				if (meanStack > 0) {
					colMeanStack.innerHTML = "+" + meanStack.toLocaleString("fr-fr") + " (" + parseInt(stack.uma).toLocaleString("fr-fr") + ")";
				} else if (meanStack == 0) {
					colMeanStack.innerHTML = "0" + " (" + parseInt(stack.uma).toLocaleString("fr-fr") + ")";
				} else {
					var colMeanStackFont = document.createElement("font");
					colMeanStack.appendChild(colMeanStackFont);
					colMeanStackFont.color = "#FF0000";
					colMeanStackFont.innerHTML = meanStack.toLocaleString("fr-fr") + " (" + parseInt(stack.uma).toLocaleString("fr-fr") + ")";
				}
				line.appendChild(colMeanStack);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(stack.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 5: { // Best game score
			var dateOptions = {
			    weekday : 'short',
			    year : 'numeric',
			    month : 'short',
			    day : 'numeric'
			};
			title3.innerHTML = "Stack";
			title4.innerHTML = "Date";
			var lastIndex = -1;
			var lastStack = null;
			for (var index = 0; index < listScores.length; index++) {
				var stack = listScores[index];
				if (lastIndex == -1 || lastStack.score != stack.score) {
					lastIndex = index;
				}
				lastStack = stack;

				var line = document.createElement("tr");
				var colRanking = document.createElement("td");
				colRanking.align = "center";
				colRanking.style.width = "25%";
				colRanking.innerHTML = (lastIndex + 1);
				line.appendChild(colRanking);

				var colName = document.createElement("td");
				colName.style.width = "25%";
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + stack.name + "\">" + stack.name + "</a>";
				line.appendChild(colName);

				var bestStack = parseInt(stack.score);
				var colStack = document.createElement("td");
				colStack.align = "center";
				colStack.style.width = "25%";
				if (bestStack > 0) {
					colStack.innerHTML = "+" + bestStack.toLocaleString("fr-fr");
				} else if (bestScore == 0) {d
					colStack.innerHTML = "0";
				} else {
					var colStackFont = document.createElement("font");
					colStack.appendChild(colStackFont);
					colStackFont.color = "#FF0000";
					colStackFont.innerHTML = bestStack.toLocaleString("fr-fr");
				}
				line.appendChild(colStack);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				var date = new Date(stack.year, stack.month, stack.day, 0, 0, 0, 0);
				colDate.innerHTML = date.toLocaleDateString("fr-fr", dateOptions);
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 6: { // Win rate 4
			title3.innerHTML = "Taux de victoire";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastScore = null;
			var rateStringOption = {
			    minimumFractionDigits : 2,
			    maximumFractionDigits : 2
			};
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var rate = parseFloat(score.score);
				var colRate = document.createElement("td");
				colRate.align = "center";
				colRate.style.width = "25%";
				if (rate >= 25.0) {
					colRate.innerHTML = rate.toLocaleString("fr-fr", rateStringOption) + "%";
				} else {
					var colRateFont = document.createElement("font");
					colRate.appendChild(colRateFont);
					colRateFont.color = "#FF0000";
					colRateFont.innerHTML = rate.toLocaleString("fr-fr", rateStringOption) + "%";
				}
				line.appendChild(colRate);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(score.uma).toLocaleString("fr-fr") + " / " + parseInt(score.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 7: { // Win rate 5
			title3.innerHTML = "Taux de victoire";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastScore = null;
			var rateStringOption = {
			    minimumFractionDigits : 2,
			    maximumFractionDigits : 2
			};
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var rate = parseFloat(score.score);
				var colRate = document.createElement("td");
				colRate.align = "center";
				colRate.style.width = "25%";
				if (rate >= 20.0) {
					colRate.innerHTML = rate.toLocaleString("fr-fr", rateStringOption) + "%";
				} else {
					var colRateFont = document.createElement("font");
					colRate.appendChild(colRateFont);
					colRateFont.color = "#FF0000";
					colRateFont.innerHTML = rate.toLocaleString("fr-fr", rateStringOption) + "%";
				}
				line.appendChild(colRate);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(score.uma).toLocaleString("fr-fr") + " / " + parseInt(score.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 8: { // Positive rate 4
			title3.innerHTML = "Taux de positif";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastScore = null;
			var rateStringOption = {
			    minimumFractionDigits : 1,
			    maximumFractionDigits : 1
			};
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var rate = parseFloat(score.score);
				var colRate = document.createElement("td");
				colRate.align = "center";
				colRate.style.width = "25%";
				if (rate >= 50.0) {
					colRate.innerHTML = rate.toLocaleString("fr-fr", rateStringOption) + "%";
				} else {
					var colRateFont = document.createElement("font");
					colRate.appendChild(colRateFont);
					colRateFont.color = "#FF0000";
					colRateFont.innerHTML = rate.toLocaleString("fr-fr", rateStringOption) + "%";
				}
				line.appendChild(colRate);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(score.uma).toLocaleString("fr-fr") + " / " + parseInt(score.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 9: { // Positive rate 5
			title3.innerHTML = "Taux de positif";
			title4.innerHTML = "Nombre de parties";
			var lastIndex = -1;
			var lastScore = null;
			var rateStringOption = {
			    minimumFractionDigits : 1,
			    maximumFractionDigits : 1
			};
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var rate = parseFloat(score.score);
				var colRate = document.createElement("td");
				colRate.align = "center";
				colRate.style.width = "25%";
				if (rate >= 40.0) {
					colRate.innerHTML = rate.toLocaleString("fr-fr", rateStringOption) + "%";
				} else {
					var colRateFont = document.createElement("font");
					colRate.appendChild(colRateFont);
					colRateFont.color = "#FF0000";
					colRateFont.innerHTML = rate.toLocaleString("fr-fr", rateStringOption) + "%";
				}
				line.appendChild(colRate);

				var colNbGames = document.createElement("td");
				colNbGames.align = "center";
				colNbGames.style.width = "25%";
				colNbGames.innerHTML = parseInt(score.uma).toLocaleString("fr-fr") + " / " + parseInt(score.nbGames).toLocaleString("fr-fr");
				line.appendChild(colNbGames);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 10: { // Annual total final score
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var totalScore = parseInt(score.score);
				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				if (totalScore > 0) {
					colTotalScore.innerHTML = "+" + totalScore.toLocaleString("fr-fr");
				} else if (totalScore == 0) {
					colTotalScore.innerHTML = "000";
				} else {
					var colTotalScoreFont = document.createElement("font");
					colTotalScore.appendChild(colTotalScoreFont);
					colTotalScoreFont.color = "#FF0000";
					colTotalScoreFont.innerHTML = totalScore.toLocaleString("fr-fr");
				}
				line.appendChild(colTotalScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				colDate.innerHTML = score.year;
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 11: { // Trimesterial total final score
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var totalScore = parseInt(score.score);
				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				if (totalScore > 0) {
					colTotalScore.innerHTML = "+" + totalScore.toLocaleString("fr-fr");
				} else if (totalScore == 0) {
					colTotalScore.innerHTML = "000";
				} else {
					var colTotalScoreFont = document.createElement("font");
					colTotalScore.appendChild(colTotalScoreFont);
					colTotalScoreFont.color = "#FF0000";
					colTotalScoreFont.innerHTML = totalScore.toLocaleString("fr-fr");
				}
				line.appendChild(colTotalScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				colDate.innerHTML = trimesterStrings[score.month] + " " + score.year;
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 12: { // Mensual total final score
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var totalScore = parseInt(score.score);
				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				if (totalScore > 0) {
					colTotalScore.innerHTML = "+" + totalScore.toLocaleString("fr-fr");
				} else if (totalScore == 0) {
					colTotalScore.innerHTML = "000";
				} else {
					var colTotalScoreFont = document.createElement("font");
					colTotalScore.appendChild(colTotalScoreFont);
					colTotalScoreFont.color = "#FF0000";
					colTotalScoreFont.innerHTML = totalScore.toLocaleString("fr-fr");
				}
				line.appendChild(colTotalScore);

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
		case 13: { // Annual total game score
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var totalScore = parseInt(score.score);
				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				if (totalScore > 0) {
					colTotalScore.innerHTML = "+" + totalScore.toLocaleString("fr-fr");
				} else if (totalScore == 0) {
					colTotalScore.innerHTML = "000";
				} else {
					var colTotalScoreFont = document.createElement("font");
					colTotalScore.appendChild(colTotalScoreFont);
					colTotalScoreFont.color = "#FF0000";
					colTotalScoreFont.innerHTML = totalScore.toLocaleString("fr-fr");
				}
				line.appendChild(colTotalScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				colDate.innerHTML = score.year;
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 14: { // Trimesterial total game score
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var totalScore = parseInt(score.score);
				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				if (totalScore > 0) {
					colTotalScore.innerHTML = "+" + totalScore.toLocaleString("fr-fr");
				} else if (totalScore == 0) {
					colTotalScore.innerHTML = "000";
				} else {
					var colTotalScoreFont = document.createElement("font");
					colTotalScore.appendChild(colTotalScoreFont);
					colTotalScoreFont.color = "#FF0000";
					colTotalScoreFont.innerHTML = totalScore.toLocaleString("fr-fr");
				}
				line.appendChild(colTotalScore);

				var colDate = document.createElement("td");
				colDate.align = "center";
				colDate.style.width = "25%";
				colDate.innerHTML = trimesterStrings[score.month] + " " + score.year;
				line.appendChild(colDate);

				newTableBody.appendChild(line);
			}
		}
			break;
		case 15: { // Mensual total game score
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
				colName.innerHTML = "<a href=\"/bmjc/?menu=personal_analyze&player=" + score.name + "\">" + score.name + "</a>";
				line.appendChild(colName);

				var totalScore = parseInt(score.score);
				var colTotalScore = document.createElement("td");
				colTotalScore.align = "center";
				colTotalScore.style.width = "25%";
				if (totalScore > 0) {
					colTotalScore.innerHTML = "+" + totalScore.toLocaleString("fr-fr");
				} else if (totalScore == 0) {
					colTotalScore.innerHTML = "000";
				} else {
					var colTotalScoreFont = document.createElement("font");
					colTotalScore.appendChild(colTotalScoreFont);
					colTotalScoreFont.color = "#FF0000";
					colTotalScoreFont.innerHTML = totalScore.toLocaleString("fr-fr");
				}
				line.appendChild(colTotalScore);

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
	var selectDay = document.getElementById("selectDay");
	var checkUseMinGames = document.getElementById("checkUseMinGames");
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
		        "month" : selectMonth.options[selectMonth.selectedIndex].value,
		        "day" : selectDay.options[selectDay.selectedIndex].value,
		        "useMinGames" : checkUseMinGames.checked ? "1" : "0"
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
				getRanking();
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

var konamicode = [ 38, 38, 40, 40, 37, 39, 37, 39 // , 66, 65
];
var currentCodeIndex = 0;
var keyDown = false;

function keydown(e) {
	if (!keyDown) {
		keyDown = true;

		if (e && e.keyCode == konamicode[currentCodeIndex]) {
			currentCodeIndex++;
		} else {
			currentCodeIndex = 0;
		}

		if (currentCodeIndex == konamicode.length) {
			var checkUseMinGames = document.getElementById("checkUseMinGames");
			checkUseMinGames.checked = !checkUseMinGames.checked;
			var audio;
			if (checkUseMinGames.checked) {
				audio = new Audio("sound/MarioPowerup.wav");
			} else {
				audio = new Audio("sound/MarioPipe.wav");
			}
			audio.play();

			currentCodeIndex = 0;
			getRanking();
		}
	}
}

function keyup(e) {
	keyDown = false;
}

function prepare() {
	toggleSelect();
	getTournaments();
	document.getElementById("selectTournament").onchange = getYears;
	document.getElementById("selectRanking").onchange = getRanking;
	document.getElementById("selectSorting").onchange = getRanking;
	document.getElementById("selectPeriod").onchange = getRanking;
	document.getElementById("selectYear").onchange = getDays;
	document.getElementById("selectTrimester").onchange = getRanking;
	document.getElementById("selectMonth").onchange = getDays;
	document.getElementById("selectDay").onchange = getRanking;

	if (document.addEventListener) {
		document.addEventListener("keydown", keydown, false);
		document.addEventListener("keyup", keyup, false);
	} else if (document.attachEvent) {
		document.attachEvent("onkeydown", keydown);
		document.attachEvent("onkeyup", keyup);
	} else {
		document.onkeydown = keydown;
		document.onkeyup = keyup;
	}
}

$(document).ready(prepare());