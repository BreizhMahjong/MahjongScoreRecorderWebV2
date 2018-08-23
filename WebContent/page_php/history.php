<div style="width: 640px">
	<table class="table" style="width: 640px">
		<tr>
			<td>Tournoi&nbsp;:</td>
			<td><select id="selectTournament"></select></td>
			<td>Année&nbsp;:</td>
			<td><select id="selectYear">
			</select></td>
			<td>Mois&nbsp;:</td>
			<td><select id="selectMonth">
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
			<td>Jour&nbsp;:</td>
			<td><select id="selectDay">
			</select></td>
			<td><button id="buttonDisplay" type="button">Afficher</button></td>
		</tr>
	</table>
</div>
<div id="gamePanel" style="width: 648px;"></div>
<div class="loadingImage">
	<img src="images/rolling.gif" />
</div>