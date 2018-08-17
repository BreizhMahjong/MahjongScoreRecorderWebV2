<table class="table" style="width: 100%">
	<tr>
		<td style="text-align: right; width: 10%;">Classement&nbsp;:</td>
		<td style="width: 15%;"><select id="selectRanking" style="width: 100%;">
				<option value="total">Total</option>
				<option value="finalScore">Score final</option>
				<option value="meanFinalScore">Score final moyen</option>
				<option value="gameScore">Stack</option>
				<option value="meanGameScore">Stack moyen</option>
				<option value="totalAnnual">Total annuel</option>
				<option value="totalTrimestrial">Total trimestriel</option>
				<option value="totalMensual">Total mensuel</option>
		</select></td>
		<td style="text-align: right; width: 10%;">Tournoi&nbsp;:</td>
		<td colspan="3" style="width: 30%;"><select id="selectTournament" style="width: 100%;"></select></td>
		<td style="text-align: right; width: 10%;">Tri&nbsp;:</td>
		<td style="width: 15%;"><select id="selectSorting" style="width: 100%;">
				<option value="highest">Plus grand</option>
				<option value="lowest">Plus petit</option>
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
				<option value="0" <?php if($trimester==0) { echo " selected=\"selected\""; } ?>>1er</option>
				<option value="1" <?php if($trimester==1) { echo " selected=\"selected\""; } ?>>2ème</option>
				<option value="2" <?php if($trimester==2) { echo " selected=\"selected\""; } ?>>3ème</option>
				<option value="3" <?php if($trimester==3) { echo " selected=\"selected\""; } ?>>4ème</option>
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
<table id="rankingTable" class="table table-striped" style="width: 100%;">
	<thead>
		<tr>
			<th style="text-align: center; width: 25%;">#</th>
			<th style="text-align: left; width: 25%;">Joueur</th>
			<th id="tableTitle3" style="text-align: center; width: 25%;">Score</th>
			<th id="tableTitle4" style="text-align: center; width: 25%;">Nombre de parties</th>
		</tr>
	</thead>
	<tbody id="rankingTableBody">
	</tbody>
</table>
<div class="loadingImage">
	<img src="images/rolling.gif" />
</div>