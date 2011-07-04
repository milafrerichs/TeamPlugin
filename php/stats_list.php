<h2>Statistiken Übersicht </h2>
<?
$rows = $wpdb->get_results("SELECT spieler.id, spieler.name,spieler.vorname FROM ".$wpdb->prefix."team_members as spieler");

?>
<table class="widefat">
<thead>
<th class="manage-column">Spieler</th>
<th class="manage-column">Games</th>
<th class="manage-column">AB</th>
<th class="manage-column">H</th>
<th class="manage-column">AVG</th>
<th class="manage-column">OPS</th>
<th class="manage-column">SLG</th>
</thead>
<?

foreach($rows as $row) 
{
	$spieler_id = $row->id;
	$stats = $wpdb->get_row("SELECT SUM(ab) as ab, COUNT(game_id) as games, SUM(hits) as hits, SUM(doubles) AS doubles, SUM(triples) as triples, SUM(homeruns) as homeruns, SUM(hbp) as hbp, SUM(sac) as sh, SUM(walks) as walks FROM ".$wpdb->prefix."statistiken WHERE player_id = ".$spieler_id."");
	if($stats->ab != 0)
	{
		$pa = $stats->ab + $stats->walks + $stats->hbp + $stats->sh;
		$obs = ($stats->hits+$stats->doubles+$stats->triples+$stats->homeruns+$stats->walks+$stats->hbp)/($stats->ab+$stats->walks+$stats->hbp+$stats->sh);
		$slg = (($stats->hits)+(2*$stats->doubles)+(3*$stats->triples)+(4*$stats->homeruns))/$stats->ab;
		$avg = ($stats->hits+$stats->doubles+$stats->triples+$stats->homeruns)/$stats->ab;
	}else {
		$avg = 0;
		$slg = 0;
		$obs = 0;
	}
?>
	
	<tr><td><?php echo $row->name;?></td>
		<td><?php echo $stats->games;?></td>
		<td><?php echo $stats->ab;?></td>
		<td><?php echo $stats->hits;?></td>
		<td><?php echo $avg;?></td>
		<td><?php echo $obs;?></td>
		<td><?php echo $slg;?></td>

	</tr>
<?
}

?>
</table>