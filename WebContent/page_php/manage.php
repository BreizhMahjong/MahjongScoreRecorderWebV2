<div style="width: 648px">
	<table class="table" style="margin: 4px; width: 640px; border: 2px solid gray">
		<tr>
			<td colSpan="3" style="text-align: center; border-bottom: 1px groove gray">Ajouter joueur</td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Pseudo&nbsp;:</td>
			<td style="width: 50%;"><input id="inputNewPlayerName" type="text" required="required" style="width: 100%;" /></td>
			<td rowSpan="2" style="text-align: center; width: 20%;"><button id="buttonAddPlayer" style="width: 80%;">Ajouter</button></td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Nom&nbsp;:</td>
			<td style="width: 50%;"><input id="inputNewPlayerRealName" type="text" required="required" style="width: 100%;" /></td>
		</tr>
	</table>
	<table class="table" style="margin: 4px; width: 640px; border: 2px solid gray">
		<tr>
			<td colSpan="3" style="text-align: center; border-bottom: 1px groove gray">Modifier joueur</td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Pseudo&nbsp;:</td>
			<td style="width: 50%;"><select id="selectModifyPlayer" style="width: 100%;"></select></td>
			<td rowSpan="5" style="text-align: center; width: 20%;"><button id="buttonModifyPlayer" style="width: 80%;">Modifier</button></td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Nouveau pseudo&nbsp;:</td>
			<td style="width: 50%;"><input id="inputModifyPlayerName" type="text" style="width: 100%;" /></td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Nouveau nom&nbsp;:</td>
			<td style="width: 50%;"><input id="inputModifyPlayerRealName" type="text" style="width: 100%;" /></td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Fréquent&nbsp;:</td>
			<td style="text-align: left; width: 50%;"><input id="inputFrequent" type="checkbox" /></td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Régulier&nbsp;:</td>
			<td style="text-align: left; width: 50%;"><input id="inputRegular" type="checkbox" /></td>
		</tr>
	</table>
	<table class="table" style="margin: 4px; width: 640px; border: 2px solid gray">
		<tr>
			<td colSpan="3" style="text-align: center; border-bottom: 1px groove gray">Supprimer joueur</td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Pseudo&nbsp;:</td>
			<td style="width: 50%;"><select id="selectDeletePlayer" style="width: 100%;"></select></td>
			<td style="text-align: center; width: 20%;"><button id="buttonDeletePlayer" style="width: 80%;">Supprimer</button></td>
		</tr>
	</table>
	<table class="table" style="margin: 4px; width: 640px; border: 2px solid gray">
		<tr>
			<td colSpan="3" style="text-align: center; border-bottom: 1px groove gray">Ajouter tournoi</td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Nom&nbsp;:</td>
			<td style="width: 50%;"><input id="inputNewTournamentName" type="text" style="width: 100%;" /></td>
			<td style="text-align: center; width: 20%;"><button id="buttonAddTournament" style="width: 80%;">Ajouter</button></td>
		</tr>
	</table>
	<table class="table" style="margin: 4px; width: 640px; border: 2px solid gray">
		<tr>
			<td colSpan="3" style="text-align: center; border-bottom: 1px groove gray">Modifier tournoi</td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Nom&nbsp;:</td>
			<td style="width: 50%;"><select id="selectModifyTournament" style="width: 100%;"></select></td>
			<td rowSpan="2" style="text-align: center; width: 20%;"><button id="buttonModifyTournament" style="width: 80%;">Modifier</button></td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Nouveau nom&nbsp;:</td>
			<td style="width: 50%;"><input id="inputModifyTournamentName" type="text" required="required" style="width: 100%;" /></td>
		</tr>
	</table>
	<table class="table" style="margin: 4px; width: 640px; border: 2px solid gray">
		<tr>
			<td colSpan="3" style="text-align: center; border-bottom: 1px groove gray">Supprimer tournoi</td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">Nom&nbsp;:</td>
			<td style="width: 50%;"><select id="selectDeleteTournament" style="width: 100%;"></select></td>
			<td style="text-align: center; width: 20%;"><button id="buttonDeleteTournament" style="width: 80%;">Supprimer</button></td>
		</tr>
	</table>
	<table class="table" style="margin: 4px; width: 640px; border: 2px solid gray">
		<tr>
			<td colSpan="3" style="text-align: center; border-bottom: 1px groove gray">Supprimer partie</td>
		</tr>
		<tr>
			<td style="text-align: right; width: 30%;">ID&nbsp;:</td>
			<td style="width: 50%;"><input id="inputDeleteGameId" type="number" style="width: 100%;" /></td>
			<td style="text-align: center; width: 20%;"><button id="buttonDeleteGame" style="width: 80%;">Supprimer</button></td>
		</tr>
	</table>
</div>
<div class="loadingImage">
	<img src="images/rolling.gif" />
</div>