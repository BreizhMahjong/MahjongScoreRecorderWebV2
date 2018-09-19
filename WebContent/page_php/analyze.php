<table class="table" style="width: 100%">
	<tr>
		<td style="text-align: right; width: 10%;">Joueur&nbsp;:</td>
		<td style="width: 15%;"><select id="selectPlayer" style="width: 100%;">
		</select></td>
		<td style="text-align: right; width: 10%;">Tournoi&nbsp;:</td>
		<td colspan="3" style="width: 30%;"><select id="selectTournament" style="width: 100%;">
		</select></td>
		<td style="text-align: right; width: 10%;">Score&nbsp;:</td>
		<td style="width: 15%;"><select id="selectScore" style="width: 100%;">
				<option value="finalScore">Score final</option>
				<option value="absScore">Score brut</option>
				<option value="gameScore">Stack</option>
		</select></td>
		<td style="width: 10%;"></td>
	</tr>
	<tr>
		<td style="text-align: right; width: 10%;">Période&nbsp;:</td>
		<td style="width: 15%;"><select id="selectPeriod" style="width: 100%;">
				<option value="all">Tout</option>
				<option value="year">Année</option>
				<option value="trimester" selected="selected">Trimestre</option>
				<option value="month">Mois</option>
		</select></td>
		<td style="text-align: right; width: 10%;">Année&nbsp;:</td>
		<td style="width: 10%;"><select id="selectYear" style="width: 100%;">
		</select></td>
		<td style="text-align: right; width: 10%;">Trimestre&nbsp;:</td>
		<td style="width: 10%;"><select id="selectTrimester" style="width: 100%;">
				<?php $trimester = floor ((intval (date ("m")) - 1) / 3);?>
				<option value="0" <?php if ($trimester == 0) { echo " selected=\"selected\""; }?>>1er</option>
				<option value="1" <?php if ($trimester == 1) { echo " selected=\"selected\""; }?>>2ème</option>
				<option value="2" <?php if ($trimester == 2) { echo " selected=\"selected\""; }?>>3ème</option>
				<option value="3" <?php if ($trimester == 3) { echo " selected=\"selected\""; }?>>4ème</option>
		</select></td>
		<td style="text-align: right; width: 10%;">Mois&nbsp;:</td>
		<td style="width: 15%;"><select id="selectMonth" style="width: 100%;">
				<option value="0">Janvier</option>
				<option value="1">Février</option>
				<option value="2">Mars</option>
				<option value="3">Avril</option>
				<option value="4">Mai</option>
				<option value="5">Juin</option>
				<option value="6">Juillet</option>
				<option value="7">Août</option>
				<option value="8">Septembre</option>
				<option value="9">Octobre</option>
				<option value="10">Novembre</option>
				<option value="11">Décembre</option>
		</select></td>
		<td style="width: 10%;" />
	</tr>
</table>
<div class="chart" id="scoreChart" style="width: 100%;"></div>
<div class="chart" id="sumChart" style="width: 100%;"></div>
<div class="table" style="width: 100%;">
	<table style="width: 100%; border: 2px solid gray;">
		<tr>
			<td align="center" style="width: 15%;">Parties</td>
			<td align="center" style="width: 15%;">Score max</td>
			<td align="center" style="width: 15%;">Positif</td>
			<td align="center" style="width: 15%;">Négatif</td>
			<td align="center" style="width: 15%;">Score total</td>
			<td align="center" style="width: 15%;">Total max</td>
		</tr>
		<tr>
			<td id="nbGames" align="center" style="width: 15%;">0</td>
			<td id="scoreMax" align="center" style="width: 15%;">0</td>
			<td id="positive" align="center" style="width: 15%;">0</td>
			<td id="negative" align="center" style="width: 15%;">0</td>
			<td id="scoreTotal" align="center" style="width: 15%;">0</td>
			<td id="totalMax" align="center" style="width: 15%;">0</td>
		</tr>
		<tr>
			<td align="center" style="width: 15%;"></td>
			<td align="center" style="width: 15%;">Score min</td>
			<td align="center" style="width: 15%;">Positif %</td>
			<td align="center" style="width: 15%;">Négatif %</td>
			<td align="center" style="width: 15%;">Moyenne (écart type)</td>
			<td align="center" style="width: 15%;">Total min</td>
		</tr>
		<tr>
			<td align="center" style="width: 15%;"></td>
			<td id="scoreMin" align="center" style="width: 15%;">0</td>
			<td id="positivePercent" align="center" style="width: 15%;">0</td>
			<td id="negativePercent" align="center" style="width: 15%;">0</td>
			<td id="scoreMean" align="center" style="width: 15%;">0</td>
			<td id="totalMin" align="center" style="width: 15%;">0</td>
		</tr>
	</table>
	<table style="width: 100%; border: 2px solid gray;">
		<tr>
			<td align="center" style="width: 15%;">Parties à 4 joueurs</td>
			<td align="center" style="width: 15%;">1er</td>
			<td align="center" style="width: 15%;">2ème</td>
			<td align="center" style="width: 15%;">3ème</td>
			<td align="center" style="width: 15%;">4ème</td>
			<td align="center" style="width: 15%;">-</td>
		</tr>
		<tr>
			<td id="nbFourPlayerGames" align="center" style="width: 15%;">0</td>
			<td id="fourPlayer1" align="center" style="width: 15%;">0</td>
			<td id="fourPlayer2" align="center" style="width: 15%;">0</td>
			<td id="fourPlayer3" align="center" style="width: 15%;">0</td>
			<td id="fourPlayer4" align="center" style="width: 15%;">0</td>
			<td align="center">-</td>
		</tr>
		<tr>
			<td align="center" style="width: 15%;"></td>
			<td id="fourPlayer1percent" align="center" style="width: 15%;">0</td>
			<td id="fourPlayer2percent" align="center" style="width: 15%;">0</td>
			<td id="fourPlayer3percent" align="center" style="width: 15%;">0</td>
			<td id="fourPlayer4percent" align="center" style="width: 15%;">0</td>
			<td align="center" style="width: 15%;">-</td>
		</tr>
	</table>
	<table style="width: 100%; border: 2px solid gray;">
		<tr>
			<td align="center" style="width: 15%;">Parties à 5 joueurs</td>
			<td align="center" style="width: 15%;">1er</td>
			<td align="center" style="width: 15%;">2ème</td>
			<td align="center" style="width: 15%;">3ème</td>
			<td align="center" style="width: 15%;">4ème</td>
			<td align="center" style="width: 15%;">5ème</td>
		</tr>
		<tr>
			<td id="nbFivePlayerGames" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer1" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer2" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer3" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer4" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer5" align="center" style="width: 15%;">0</td>
		</tr>
		<tr>
			<td align="center" style="width: 15%;"></td>
			<td id="fivePlayer1percent" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer2percent" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer3percent" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer4percent" align="center" style="width: 15%;">0</td>
			<td id="fivePlayer5percent" align="center" style="width: 15%;">0</td>
		</tr>
	</table>
</div>
<div id="debug" style="width: 100%"></div>
<div class="loadingImage">
	<img src="images/rolling.gif" />
</div>

