<table class="table" style="width: 100%">
	<tr>
		<td style="width: 8%;"></td>
		<td style="width: 12%;"></td>
		<td style="text-align: right; width: 6%;">Tournoi&nbsp;:</td>
		<td colspan="5" style="width: 48%;"><select id="selectTournament" style="width: 100%;">
		</select></td>
		<td style="width: 6%;"></td>
		<td style="width: 12%;"></td>
		<td style="width: 8%;"></td>
	</tr>
	<tr>
		<td style="text-align: right; width: 8%;">Période&nbsp;:</td>
		<td style="width: 12%;"><select id="selectPeriod" style="width: 100%;">
				<option value="all">Tout</option>
				<option value="season">Saison</option>
				<option value="year">Année</option>
				<option value="trimester" selected="selected">Trimestre</option>
				<option value="month">Mois</option>
				<option value="day">Jour</option>
		</select></td>
		<td style="text-align: right; width: 6%;">Année&nbsp;:</td>
		<td style="width: 12%;"><select id="selectYear" style="width: 100%;">
		</select></td>
		<td style="text-align: right; width: 6%;">Trimestre&nbsp;:</td>
		<td style="width: 12%;"><select id="selectTrimester" style="width: 100%;">
				<?php $trimester = floor ((intval (date ("m")) - 1) / 3);?>
				<option value="0" <?php if ($trimester == 0) { echo " selected=\"selected\""; }?>>1er</option>
				<option value="1" <?php if ($trimester == 1) { echo " selected=\"selected\""; }?>>2ème</option>
				<option value="2" <?php if ($trimester == 2) { echo " selected=\"selected\""; }?>>3ème</option>
				<option value="3" <?php if ($trimester == 3) { echo " selected=\"selected\""; }?>>4ème</option>
		</select></td>
		<td style="text-align: right; width: 6%;">Mois&nbsp;:</td>
		<td style="width: 12%;"><select id="selectMonth" style="width: 100%;">
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
		<td style="text-align: right; width: 6%;">Jour&nbsp;:</td>
		<td style="width: 12%;"><select id="selectDay" style="width: 100%;">
		</select></td>
		<td style="width: 8%;" />
	</tr>
</table>
<table id="scoreOuterTable" style="display: none; min-width: 100%; max-width: 100%; width: 100%;">
	<tr>
		<td>
			<div style="min-width: 144px; max-width: 144px; width: 144px; min-height: 144px; max-height: 144px; height: 144px;">
				<table border="2px solid gray">
					<tbody>
						<tr>
							<td style="min-width: 143px; max-width: 143px; width: 143px; min-height: 143px; max-height: 143px; height: 143px;"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</td>
		<td>
			<div id="topPlayerNameScroll" style="overflow: hidden; min-height: 144px; max-height: 144px; height: 144px;"></div>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<div id="leftPlayerNameScroll" style="overflow: hidden; min-width: 144px; max-width: 144px; width: 144px;"></div>
		</td>
		<td>
			<div id="scoreTableScroll" style="overflow: scroll;"></div>
		</td>
	</tr>
</table>
<div class="loadingImage">
	<img src="images/rolling.gif" />
</div>

