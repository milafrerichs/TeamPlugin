<h2>Spieler Übersicht <a class="button add-new-h2" href="admin.php?page=new-player">neu</a></h2>
<?
$rows = $wpdb->get_results( "SELECT spieler.id, spieler.name,spieler.vorname,spieler.team_id,teams.name as team FROM ".$wpdb->prefix."team_members  AS spieler LEFT JOIN ".$wpdb->prefix."teams as teams ON (teams.id = spieler.team_id) ORDER BY spieler.name" );
?>
<table class="widefat">
<thead>
<th class="manage-column">Id</th>
<th class="manage-column">Name</th>
<th class="manage-column">Team</th>
<th class="manage-column">Aktionen</th>
</thead>
<?
foreach($rows as $row) 
{
	?>
	<tr><td><?php echo $row->id;?></td>
		<td><a href="admin.php?page=new-player&id=<?php echo $row->id;?>"><?php echo $row->name;?>,<?php echo $row->vorname;?></a></td>
		<td><?php echo $row->team;?></td>
		<td><a href="admin.php?page=new-player&action=delete&id=<?php echo $row->id;?>">L&ouml;schen</a></td>
	</tr>	
<?
}
?>
</table>