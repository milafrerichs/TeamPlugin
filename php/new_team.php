<h2><?php 
if(isset($_GET['action']))
{
	if($_GET['action'] == "delete")
	{
		
		$spieler = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."teams WHERE id = ".$_GET['id'] );
		echo "Team ".$spieler->name." ".$spieler->saison." löschen?</h2>";
		echo "Es werden alle Verknüpfungen, Statstiken, etc. gelöscht!!";
		?>
		<form method="get" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="page" value="new-team" />
			<input type="hidden" name="action" value="delete_confirm" />
			<input type="hidden" name="id" value="<? echo $_GET['id'];?>" />
			
			 <p class="submit">
			        <input type="submit" name="Submit" value="Endgültig löschen" />  
 
			        <a href="admin.php?page=teams"><input type="cancel" name="abbrechen" value="Abbrechen" /></a>
			     </p>  
		</form>
		<?
	}
	if($_GET['action'] == "delete_confirm" && isset($_GET['id']))
	{
		$id = $_GET['id'];
		$table = $wpdb->prefix . "teams";
		$table_pt = $wpdb->prefix . "player_teams";
		$wpdb->query("DELETE FROM ".$table." WHERE id = ".$id."");
		$wpdb->query("DELETE FROM ".$table_pt." WHERE team_id = ".$id."");
		
		echo "Team gelöscht</h2>";
	}
}else
{
if(isset($_GET['id'])){ echo 'Edit';}else{ echo 'Neues';} ?>  Team</h2>
<? if(isset($_POST['name']) && $_POST['name'] != "")
{
	$name = $_POST['name'];
	$description = $_POST['description'];
	$saison = $_POST['saison'];
	
	
	$table = $wpdb->prefix . "teams";
	if(isset($_POST['id']) && $_POST['id'] != "" )
	{
		$wpdb->update($table,array("name"=>$name,"description"=>$description,"saison"=>$saison),array("id"=>$_POST['id']));
	}else {
		$wpdb->insert($table,array("name"=>$name,"description"=>$description,"saison"=>$saison));
	}
	
	if(isset($_POST['spieler']))
	{
		$player = $_POST['spieler'];
		$old_team_id = $_POST['team_spieler'];
		
		$team_id = $wpdb->insert_id;
		$players = $wpdb->get_col("SELECT id FROM ".$wpdb->prefix."team_members WHERE team_id = ".$old_team_id."");
		foreach($players as $player_id)
		{
			echo $player_id;
			$wpdb->insert($wpdb->prefix."player_teams",array("player_id"=>$player_id,"team_id"=>$team_id),array("%d"));
		}
		
	}
	
	?>
	<div class="updated"><p><strong><?=$name;?> Gespeichert</strong></p></div>  
	<?
}

if(isset($_GET['id']))
{
	$team = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."teams WHERE id = ".$_GET['id']."");
}

$teams = $wpdb->get_results( "SELECT id,name,saison FROM ".$wpdb->prefix."teams");
?>


<form name="new_team_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
	<input type="hidden" name="id" value="<?php echo (isset($team->id))?$team->id:'';?>" />
	<p>Name: <input type="text" name="name" value="<?php echo (isset($team->name))?$team->name:'';?>" /></p>
	<p>Description: <input type="text" name="description" value="<?php echo (isset($team->description))?$team->description:'';?>" /></p>
	<p>Saison: <input type="text" name="saison" value="<?php echo (isset($team->saison))?$team->saison:'';?>" /></p>
	<p>Spieler übernehmen? <input type="checkbox" name="spieler" value="Y" />
	<p>Team: 
	<select name="team_spieler">
					<? foreach($teams as $team)
					{
						echo '<option value="'.$team->id.'" ';
						echo '>'.$team->name.'('.$team->saison.')</option>';
					}
					?>
				</select>			
	</p>
	
	<p class="submit">  
	        <input type="submit" name="Submit" value="Eintragen" />  
	        </p>  
</form>
<?
}
?>