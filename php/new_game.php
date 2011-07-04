<h2><?php if(isset($_GET['id'])){ echo 'Edit';}else{ echo 'Neues';} ?> Spiel</h2>
<? 
if(isset($_POST['datum']) && $_POST['datum'] != "")
{
	$liga = $_POST['liga'];
	$uhrzeit = $_POST['uhrzeit'];
	$saison = $_POST['saison'];
	$gegner = $_POST['gegner'];
	$ort = $_POST['ort'];
	list($tag,$monat,$jahr) = explode('.',$_POST['datum']);
	$datum = $jahr."-".$monat."-".$tag;
	$table = $wpdb->prefix . "spiele";
	
	if(isset($_POST['id']) && $_POST['id'] != "")
	{
		$wpdb->update($table,array("liga_id"=>$liga,"gegner"=>$gegner,"saison"=>$saison,"ort"=>$ort,"datum"=>$datum,"uhrzeit"=>$uhrzeit),array("id"=>$_POST['id']));
	}else{
		$wpdb->insert($table,array("liga_id"=>$liga,"gegner"=>$gegner,"saison"=>$saison,"ort"=>$ort,"datum"=>$datum,"uhrzeit"=>$uhrzeit));
	}
	?>
	<div class="updated"><p><strong><?=$gegner.'('.$datum.')';?> Gespeichert</strong></p></div>  
	<?
}
$ligen =  $wpdb->get_results( "SELECT id, name FROM ".$wpdb->prefix."ligen" );

if(isset($_GET['id'])) {
	$game = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."spiele WHERE id = ".$_GET['id'] );
	
	if(isset($game->datum)) {
		list($jahr,$monat,$tag) = explode('-',$game->datum);
		$datum = $tag.".".$monat.".".$jahr;
	}
	else {
		$datum = "TT.MM.JJJJ";
	}
}else{
	$datum = "TT.MM.JJJJ";
}	
?>
<form name="new_team_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
	<input type="hidden" name="id" value="<?php echo (isset($game->id))?$game->id:"";?>" />
	<p>
	<label for="liga">
	Liga: <select name="liga" id="liga">
					<? foreach($ligen as $liga)
					{
						if((!empty($game) && isset($game) && $liga->id == $game->liga_id) || (isset($_GET['liga_id']) && $liga->id == $_GET['liga_id']))
						{
							$selected = 'selected="selected"';
						}else {
							$selected = "";
						}
						echo '<option value="'.$liga->id.'" '.$selected.'>'.$liga->name.'</option>';
						
					}
					?>
				</select>
	</label></p>
	<p><label for="datum">Datum: <input type="text" name="datum" value="<? echo $datum;?>" id="datum"/></label></p>
	<p><label for="uhrzeit">Uhrzeit: <input type="text" name="uhrzeit" value="<? echo (isset($game->uhrzeit))?$game->uhrzeit:"";?>" id="uhrzeit"/></label></p>
	
	<p><label for="ort">
	Ort: <input type="text" name="ort" value="<? echo (isset($game->ort))?$game->ort:"";?>" id="ort"/>
	</label></p>
	<p><label for="gegner">
	Gegner: <input type="text" name="gegner" value="<? echo (isset($game->gegner))?$game->gegner:"";?>" id="gegner"/>
	</label></p>
	<p><label for="saison">
	Saison: <input type="text" name="saison" value="<? echo (isset($game->saison))?$game->saison:"";?>" id="saison"/>
	</label></p>
	<p class="submit">  
	        <input type="submit" name="Submit" value="Eintragen" />  
	        </p>  
</form>