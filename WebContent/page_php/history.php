<div style="width: 100%">
	<table class="table" style="width: 60%">
		<tr>
			<td style="text-align: right; width: 11%;">Tournoi&nbsp;:</td>
			<td colspan="7" style="width: 89%;"><select id="selectTournament" style="width: 100%;"></select></td>
		</tr>
		<tr>
			<td style="text-align: right; width: 11%;">Stack Initial&nbsp;:</td>
			<td style="width: 11%;"><input id="inputInitGameScore" type="number" min="0" max="35000" step="100" value="30000" required="required" style="width: 100%;" /></td>
			<td style="text-align: right; width: 11%;">Année&nbsp;:</td>
			<td style="width: 11%;"><select id="selectYear" style="width: 100%;"></select></td>
			<td style="text-align: right; width: 11%;" style="width: 100%;">Mois&nbsp;:</td>
			<td style="width: 11%;"><select id="selectMonth">
					<?php $month = intval (date ("m")) - 1; ?>
					<option value="0" <?php if ($month == 0) { echo " selected=\"selected\""; }?>>Janvier</option>
					<option value="1" <?php	if ($month == 1) { echo " selected=\"selected\""; }?>>Février</option>
					<option value="2" <?php if ($month == 2) { echo " selected=\"selected\""; }?>>Mars</option>
					<option value="3" <?php if ($month == 3) { echo " selected=\"selected\""; }?>>Avril</option>
					<option value="4" <?php if ($month == 4) { echo " selected=\"selected\""; }?>>Mai</option>
					<option value="5" <?php if ($month == 5) { echo " selected=\"selected\""; }?>>Juin</option>
					<option value="6" <?php if ($month == 6) { echo " selected=\"selected\""; }?>>Juillet</option>
					<option value="7" <?php if ($month == 7) { echo " selected=\"selected\""; }?>>Août</option>
					<option value="8" <?php if ($month == 8) { echo " selected=\"selected\""; }?>>Septembre</option>
					<option value="9" <?php if ($month == 9) { echo " selected=\"selected\""; }?>>Octobre</option>
					<option value="10" <?php if ($month == 10) { echo " selected=\"selected\""; }?>>Novembre</option>
					<option value="11" <?php if ($month == 11) { echo " selected=\"selected\""; }?>>Décembre</option>
			</select></td>
			<td style="text-align: right; width: 11%;">Jour&nbsp;:</td>
			<td style="width: 11%;"><select id="selectDay" style="width: 100%;"></select></td>
			<td style="width: 12%;"><button id="buttonDisplay" type="button" style="width: 100%;">Afficher</button></td>
		</tr>
	</table>
</div>
<div id="gamePanel" style="width: 640px;"></div>
<div class="loadingImage">
	<img src="images/rolling.gif" />
</div>