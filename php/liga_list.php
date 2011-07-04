<h2>Ligen Übersicht <a class="button add-new-h2" href="admin.php?page=new-league">neu</a></h2>
<?
$rows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."ligen" );
?>
<table class="widefat">
<thead>
<th class="manage-column">Id</th>
<th class="manage-column">Name</th>
<th class="manage-column">Saison</th>
<th class="manage-column">Spiele</th>
</thead>
<?
foreach($rows as $row) 
{
	$games = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."spiele as spiele WHERE spiele.liga_id =".$row->id." AND spiele.saison = '".$row->saison."' ORDER BY spiele.datum");
	?>
	<tr><td><?php echo $row->id;?></td>
		<td><a href="admin.php?page=games&mode=team&id=<?php echo $row->id;?>"><?php echo $row->name.' ('.$row->description.')';?></a><br/>
			<a href="admin.php?page=new-league&id=<?=$row->id;?>">Editieren</a> - 
			<a href="admin.php?page=new-game&liga_id=<? echo $row->id;?>">neues Spiel</a>
		</td>
		<td><?php echo $row->saison;?></td>
		<td><?php echo count($games);?></td>
	</tr>
	<tr <?php if(isset($_GET['mode']) && $_GET['id'] == $row->id){}else { echo 'class="hidden"';} ?>>
	<td colspan="4">
	<table width="100%">
			<thead>
				<tr>
					<th>Datum</th>
					<th>Gegner</th>
					<th>Ort</th>
					<th>Ergebnis</th>
				</tr>
			</thead>
			<?
				foreach($games as $game)
				{
					$game_id = $game->id;
					?>
					<tr><td><?php echo $game->datum?></td>
						<td><?php echo $game->gegner;?><br/>
							<a href="admin.php?page=new-game&id=<?=$game_id;?>">Editieren</a> - 
						<a href="admin.php?page=new-stats&game_id=<?=$game_id;?>">Statistiken hinzufügen-</a>
						<a href="admin.php?page=new-result&game_id=<?=$game_id;?>">Ergebnis eintragen</a><br/>
						<td><?php echo $game->ort;?></td>
						<td><?php echo $game->score_gast."-".$game->score_heim;?></td>
					</tr>
					
					<?
				}
			?>
		</table>
	</td>
	</tr>	
<?
}
?>
</table>