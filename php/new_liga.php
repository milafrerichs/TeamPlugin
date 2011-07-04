<h2>Neue Liga</h2>
<? 
$wpdb->show_errors();
if(isset($_POST['name']) && $_POST['name'] != "")
{
	$name = $_POST['name'];
	$team_id = $_POST['team'];
	$description = $_POST['description'];
	$saison = $_POST['saison'];
	$table = $wpdb->prefix . "ligen";
	if(isset($_POST['id']) && $_POST['id'] != "")
	{
		$wpdb->update($table,array("team_id"=>$team_id,"name"=>$name,"description"=>$description,"saison"=>$saison),array("id"=>$_POST['id']));
	}else
	{
		$wpdb->insert($table,array("team_id"=>$team_id,"name"=>$name,"description"=>$description,"saison"=>$saison) );
	}
	?>
	<div class="updated"><p><strong> Gespeichert</strong></p></div>  
	<?
}

if(isset($_GET['id']))
{
	$liga = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."ligen WHERE id = ".$_GET['id'] );
}
$teams =  $wpdb->get_results( "SELECT id, name,saison FROM ".$wpdb->prefix."teams" );

?>


<form name="new_team_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
	<input type="hidden" name="id" value="<?php echo (isset($liga->id))?$liga->id:'';?>" />
	<p>Name: <input type="text" name="name" value="<?php echo (isset($liga->name))?$liga->name:'';?>" /></p>
	<p>Team: <select name="team">
					<? foreach($teams as $team)
					{
						echo '<option value="'.$team->id.'">'.$team->name.'('.$team->saison.')</option>';
					}
					?>
				</select></p>
	<p>Description: <input type="text" name="description" value="<?php echo (isset($liga->description))?$liga->description:'';?>" /></p>
	<p>Saison: <input type="text" name="saison" value="<?php echo (isset($liga->saison))?$liga->saison:'';?>" /></p>
	
	<p class="submit">  
	        <input type="submit" name="Submit" value="Eintragen" />  
	        </p>  
</form>