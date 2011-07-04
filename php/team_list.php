<h2>Teams Übersicht <a class="button add-new-h2" href="admin.php?page=new-team">neu</a></h2>
<?
$rows = $wpdb->get_results( "SELECT id, name,saison FROM ".$wpdb->prefix."teams" );
?>
<table class="widefat">
<thead>
<th class="manage-column">Id</th>
<th class="manage-column">Name</th>
<th class="manage-column">Saison</th>
<th class="manage-column">Spieler</th>
</thead>
<?
foreach($rows as $row) 
{
	$players = $wpdb->get_results( "SELECT players.id, players.name,players.vorname,players.nummer FROM ".$wpdb->prefix."player_teams as pt LEFT JOIN ".$wpdb->prefix."team_members as players ON (pt.player_id = players.id) WHERE pt.team_id =".$row->id );
	?>
	<tr><td><?php echo $row->id;?></td>
		<td><a href="admin.php?page=teams&mode=team&id=<?php echo $row->id;?>"><?php echo $row->name;?></a><br/>
			<a href="admin.php?page=new-team&id=<?=$row->id;?>">Editieren</a> - 
			<a href="admin.php?page=new-player&team_id=<? echo $row->id;?>">neuer Spieler</a> -
			<a href="admin.php?page=new-team&action=delete&id=<? echo $row->id;?>">Löschen</a>
		</td>
		<td><?php echo $row->saison;?></td>
		<td><?php echo count($players);?></td>
	</tr>
	<tr <?php if(isset($_GET['mode']) && $_GET['id'] == $row->id){}else { echo 'class="hidden"';} ?>><td></td>
	<td colspan="3">
	<table>
			<?
				foreach($players as $player)
				{
					?>
					<tr><td><a href="admin.php?page=new-player&id=<? echo $player->id;?>"><?php echo $player->name.", ".$player->vorname;?></a></td>
						<td><?php echo $player->nummer;?></td>
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