<h2><?php 
if(isset($_GET['action']))
{
	if($_GET['action'] == "delete")
	{
		
		$spieler = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."team_members WHERE id = ".$_GET['id'] );
		echo "Spieler ".$spieler->vorname." ".$spieler->name." löschen?</h2>";
		
		?>
		<form method="get" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="page" value="new-player" />
			<input type="hidden" name="action" value="delete_confirm" />
			<input type="hidden" name="id" value="<? echo $_GET['id'];?>" />
			
			 <p class="submit">
			        <input type="submit" name="Submit" value="Endgültig löschen" />  
 
			        <a href="admin.php?page=spieler"><input type="cancel" name="abbrechen" value="Abbrechen" /></a>
			     </p>  
		</form>
		<?
	}
	if($_GET['action'] == "delete_confirm" && isset($_GET['id']))
	{
		$id = $_GET['id'];
		$table = $wpdb->prefix . "team_members";
		$table_pt = $wpdb->prefix . "player_teams";
		$table_s = $wpdb->prefix . "statistiken";
		$wpdb->query("DELETE FROM ".$table." WHERE id = ".$id."");
		$wpdb->query("DELETE FROM ".$table_pt." WHERE player_id = ".$id."");
		$wpdb->query("DELETE FROM ".$table_s." WHERE player_id = ".$id."");
		
		echo "Spieler gelöscht</h2>";
	}
}else
{
if(isset($_GET['id'])){ echo 'Edit';}else{ echo 'Neuer';} ?> Spieler</h2>
<? 
if(isset($_POST['name']) && $_POST['name'] != "")
{
	$name = $_POST['name'];
	$vorname = $_POST['vorname'];
	$team = $_POST['team'];
	$teams = $_POST['teams'];
	list($tag,$monat,$jahr) = explode('.',$_POST['gebdatum']);
	$gebdatum = $jahr."-".$monat."-".$tag;
	$nummer = $_POST['nummer'];
	$positionen = $_POST['positionen'];
	$spielt_seit = $_POST['spielt_seit'];
	$wirft = $_POST['wirft'];
	$schlaegt = $_POST['schlaegt'];
	$vereine = $_POST['vereine'];
	
	$table = $wpdb->prefix . "team_members";
	if($_POST['id'] != "")
	{
		$wpdb->update($table,array("name"=>$name,"vorname"=>$vorname,"team_id"=>$team,"nummer"=>$nummer,"gebdatum"=>$gebdatum,"positionen"=>$positionen,"spielt_seit"=>$spielt_seit,"wirft"=>$wirft,"schlaegt"=>$schlaegt,"vereine"=>$vereine),array("id"=>$_POST['id']));	
		
		$table_con = $wpdb->prefix."player_teams";
		/*
		$count = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(team_id) FROM ".$table_con." WHERE player_id = ".$_POST['id']." AND team_id = ".$team ));
		
		if($count < 1)
		{
			$wpdb->insert($table_con,array("player_id"=>$_POST['id'],"team_id"=>$team));
		}
		*/
		$p_teams = $wpdb->get_col("SELECT team_id FROM ".$table_con." WHERE player_id = ".$_POST["id"]);
		
			foreach($teams as $team_id)
			{
				if(in_array($team_id, $p_teams))
				{
				
				}else
				{
					echo "insert team ".$team_id."for player".$_POST['id'];
					$wpdb->insert($table_con,array("player_id"=>$_POST['id'],"team_id"=>$team_id));
				}
			}
		
		
			foreach($p_teams as $team_id)
			{
				if(!in_array($team_id, $teams))
				{
					
					$wpdb->query("DELETE FROM ".$table_con." WHERE player_id = ".$_POST['id']." AND team_id = ".$team_id);
				}
			}
		
		
	}else{
		$wpdb->insert($table,array("name"=>$name,"vorname"=>$vorname,"team_id"=>$team,"nummer"=>$nummer,"gebdatum"=>$gebdatum,"positionen"=>$positionen,"spielt_seit"=>$spielt_seit,"wirft"=>$wirft,"schlaegt"=>$schlaegt,"vereine"=>$vereine));
		
		$new_id = $wpdb->insert_id;
		
		$table_con = $wpdb->prefix."player_teams";
		foreach($teams as $team_id)
		{
			##echo "insert team ".$team_id."for player".$_POST['id'];
			$wpdb->insert($table_con,array("player_id"=>$new_id,"team_id"=>$team_id));
		}
	}
	?>
	<div class="updated"><p><strong><?=$name;?> Gespeichert</strong></p></div>  
	<?
}
if(isset($_GET['id'])) {
	$spieler = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."team_members WHERE id = ".$_GET['id'] );
	
	if(isset($spieler->gebdatum)) {
		list($jahr,$monat,$tag) = explode('-',$spieler->gebdatum);
		$gebdatum = $tag.".".$monat.".".$jahr;
	}
	else {
		$gebdatum = "TT.MM.JJJJ";
	}
}else {
	$gebdatum = "TT.MM.JJJJ";
}
if(isset($spieler->id))
{	
	$player_teams =  $wpdb->get_col( "SELECT pt.team_id FROM ".$wpdb->prefix."player_teams as pt WHERE pt.player_id = ".$spieler->id);
}
$teams = $wpdb->get_results( "SELECT id,name,saison FROM ".$wpdb->prefix."teams");
wp_enqueue_script('jquery'); 
wp_print_scripts('jquery-form');
?>
<script type="text/javascript" src="<?=WP_PLUGIN_URL;?>/team/js/script.js"></script>

<form name="new_spieler_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" id="spieler_form">  
	<input type="hidden" name="id" value="<?php echo (isset($spieler->id))?$spieler->id:"";?>" />
	<p>Name: <input type="text" name="name" value="<?php echo (isset($spieler->name))?$spieler->name:"";?>" /></p>
	<p>Vorname: <input type="text" name="vorname" value="<?php echo (isset($spieler->vorname))?$spieler->vorname:"";?>" /></p>
	<p>Geburtsdatum: <input type="text" name="gebdatum" value="<?php echo $gebdatum;?>" /></p>
	<p>Nummer: <input type="text" name="nummer" value="<?php echo (isset($spieler->nummer))?$spieler->nummer:"";?>" /></p>
	<p>Teams: 	
	
		<? 
		foreach($teams as $team)
		{
		##print_r($team);
		if(isset($spieler->id))
		{
			if(in_array($team->id,$player_teams))
			{
				$checked = ' checked="checked"';
			}
			else
			{
				$checked = '';
			}
		}
		else
		{
			$checked = '';
		}
		 ?>
		
		<input type="checkbox" name="teams[]" value="<? echo $team->id;?>"<? echo $checked;?>><? echo $team->name."(".$team->saison.")";?>
		<?
		}
	?>
	
	</p>
	<p>Aktives Team: 
	<select name="team">
					<option value="0">inaktiv</option>
					<? foreach($teams as $team)
					{
						echo '<option value="'.$team->id.'" ';
						if(isset($spieler->team_id)) {
							echo ($team->id == $spieler->team_id)?'selected="selected"':'';
						}
						echo '>'.$team->name.'('.$team->saison.')</option>';
					}
					?>
				</select>			
	</p>
	<p>Positionen: <input type="text" name="positionen" value="<?php echo (isset($spieler->positionen))?$spieler->positionen:"";?>" /></p>
	<p>Spielt seit: <input type="text" name="spielt_seit" value="<?php echo (isset($spieler->spielt_seit))?$spieler->spielt_seit:"";?>" /></p>
	<p>Wirft: 	<select name="wirft">
					<option value="L" <?php echo (isset($spieler->wirft) && $spieler->wirft == "L")?'selected="selected"':"";?>>Links</option>
					<option value="R" <?php echo (isset($spieler->wirft) && $spieler->wirft == "R")?'selected="selected"':"";?>>Rechts</option>
				</select>
		</p>
	<p>Schlägt: 	<select name="schlaegt">
					<option value="L" <?php echo (isset($spieler->schlaegt) && $spieler->schlaegt == "L")?'selected="selected"':"";?>>Links</option>
					<option value="R" <?php echo (isset($spieler->schlaegt) && $spieler->schlaegt == "R")?'selected="selected"':"";?>>Rechts</option>
				</select>
		</p>
	<p>Vereine <textarea name="vereine" rows="2" cols="40"><?php echo (isset($spieler->vereine))?$spieler->vereine:"";?></textarea></p>
	<p class="submit">  
	        <input type="submit" name="Submit" value="<?php if(isset($_GET['id'])){ echo 'Aktualisieren';}else{ echo 'Eintragen';} ?>" />  
	        </p>  
</form>
<form name="img_form" action="<?=WP_PLUGIN_URL;?>/team/php/ajax.php" method="post" id="spieler_img_form" enctype="multipart/form-data">
	<input type="hidden" name="ajax" value="true" />
	<input type="hidden" name="option" value="fileUpload" />
	<input type="hidden" name="playerdir" value="<?=PLAYER_DIR;?>" />
	<input type="hidden" name="content_url" value="<?=WP_CONTENT_URL;?>" />
	<input type="hidden" name="id" value="<?php echo $spieler->id;?>" />
	<input type="file" name="img" id="file"/>
</form>
<div id="player_image">
<?
if(isset($spieler->id)) {
	if(file_exists(PLAYER_DIR."/player_".$spieler->id.".jpg")) {
		echo '<img src="'.WP_CONTENT_URL.'/players/player_'.$spieler->id.'.jpg" width="250" />';
	}
	if(file_exists(PLAYER_DIR."/player_".$spieler->id.".gif")) {
		echo '<img src="'.WP_CONTENT_URL.'/players/player_'.$spieler->id.'.gif" width="250" />';
	}
	if(file_exists(PLAYER_DIR."/player_".$spieler->id.".png")) {
		echo '<img src="'.WP_CONTENT_URL.'/players/player_'.$spieler->id.'.png" width="250" />';
	}
}else {
	echo '<img src="'.WP_CONTENT_URL.'/players/keinbild.jpg" width="250" />';
}	
}
?>
</div>