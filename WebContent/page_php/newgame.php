<table class="table" style="width: 80%">
	<tr>
		<td style="text-align: right; width: 12.5%;">Date&nbsp;:</td>
		<td style="width: 12.5%;"><input id="inputDate" type="date" style="width: 100%;"></td>
		<td style="text-align: right; width: 12.5%;">Tournoi&nbsp;:</td>
		<td colspan="5" style="width: 62.5%;"><select id="selectTournament" style="width: 100%;"></select></td>
	</tr>
	<tr>
		<td style="text-align: right; width: 12.5%;">Joueurs&nbsp;:</td>
		<td style="width: 12.5%;"><select id="selectPlayer" style="width: 100%;">
				<option value="4">4</option>
				<option value="5">5</option>
		</select></td>
		<td style="text-align: right; width: 12.5%;">Manches&nbsp;:</td>
		<td style="width: 12.5%;"><select id="selectRounds" style="width: 100%;">
				<option value="1">Tonpusen</option>
				<option value="2" selected="selected">Hanchan</option>
				<option value="4">Ichisosen</option>
		</select></td>
		<td style="text-align: right; width: 12.5%;">Stack Initial&nbsp;:</td>
		<td style="width: 12.5%;"><input id="inputInitGameScore" type="number" min="0" max="35000" step="100" value="30000" required="required" style="width: 100%;" /></td>
		<td style="text-align: right; width: 12.5%;">Uma&nbsp;:</td>
		<td style="width: 12.5%;"><select id="selectUma" style="width: 100%;">
				<option value="1" selected="selected">+15000</option>
				<option value="2">+30000</option>
		</select></td>
	</tr>
	<tr>
		<td style="text-align: right; width: 12.5%;">Options d'affichage&nbsp;:</td>
		<td style="width: 12.5%;"></td>
		<td style="text-align: right; width: 12.5%;">Joueurs fréquents&nbsp;:</td>
		<td style="width: 12.5%;"><input id="inputFrequenPlayersOnly" type="checkbox" onclick="getPlayers()" checked></td>
		<td colspan="4" style="width: 50%;"></td>
	</tr>
</table>
<table class="table" style="width: 80%">
	<thead>
		<tr>
			<th style="text-align: center; width: 15%;">#</th>
			<th style="text-align: center; width: 25%;">Joueur</th>
			<th style="text-align: center; width: 20%;">Stack</th>
			<th style="text-align: center; width: 20%;">Uma</th>
			<th style="text-align: center; width: 20%;">Score</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td id="ranking1" style="text-align: center; width: 15%;">?</td>
			<td style="width: 25%;"><select id="selectPlayer1" style="width: 100%;">
			</select></td>
			<td style="width: 20%;"><input id="inputScore1" type="number" step="100" required="required" style="width: 100%;" /></td>
			<td id="uma1" style="text-align: center; width: 20%;"></td>
			<td id="score1" style="text-align: center; width: 20%;"></td>
		</tr>
		<tr>
			<td id="ranking2" style="text-align: center; width: 15%;">?</td>
			<td style="width: 25%;"><select id="selectPlayer2" style="width: 100%;">
			</select></td>
			<td style="width: 20%;"><input id="inputScore2" type="number" step="100" required="required" style="width: 100%;" /></td>
			<td id="uma2" style="text-align: center; width: 20%;"></td>
			<td id="score2" style="text-align: center; width: 20%;"></td>
		</tr>
		<tr>
			<td id="ranking3" style="text-align: center; width: 15%;">?</td>
			<td style="width: 25%;"><select id="selectPlayer3" style="width: 100%;">
			</select></td>
			<td style="width: 20%;"><input id="inputScore3" type="number" step="100" required="required" style="width: 100%;" /></td>
			<td id="uma3" style="text-align: center; width: 20%;"></td>
			<td id="score3" style="text-align: center; width: 20%;"></td>
		</tr>
		<tr>
			<td id="ranking4" style="text-align: center; width: 15%;">?</td>
			<td style="width: 25%;"><select id="selectPlayer4" style="width: 100%;">
			</select></td>
			<td style="width: 20%;"><input id="inputScore4" type="number" step="100" required="required" style="width: 100%;" /></td>
			<td id="uma4" style="text-align: center; width: 20%;"></td>
			<td id="score4" style="text-align: center; width: 20%;"></td>
		</tr>
		<tr>
			<td id="ranking5" style="text-align: center; width: 15%;">?</td>
			<td style="width: 25%;"><select id="selectPlayer5" style="width: 100%;">
			</select></td>
			<td style="width: 20%;"><input id="inputScore5" type="number" step="100" required="required" style="width: 100%;" /></td>
			<td id="uma5" style="text-align: center; width: 20%;"></td>
			<td id="score5" style="text-align: center; width: 20%;"></td>
		</tr>
		<tr>
			<td style="width: 15%;"></td>
			<td style="width: 25%;"></td>
			<td style="width: 20%;"><font id="scoreError" color="#FF000000"></font></td>
			<td style="width: 20%;"></td>
			<td style="width: 20%;"></td>
		</tr>
	</tbody>
</table>
<table class="table" style="width: 80%">
	<tr>
		<td style="width: 10%;"></td>
		<td style="text-align: center; width: 20%;"><button id="buttonCalculate" style="width: 60%;">Calculer</button></td>
		<td style="width: 10%;"></td>
		<td style="text-align: center; width: 20%;"><button id="buttonSave" style="width: 60%;">Enregistrer</button></td>
		<td style="width: 10%;"></td>
		<td style="text-align: center; width: 20%;"><button id="buttonReset" style="width: 60%;">Réinitialiser</button></td>
		<td style="width: 10%;"></td>
	</tr>
</table>
