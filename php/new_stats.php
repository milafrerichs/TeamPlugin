<h2>Neue Statistiken</h2>
<? 
if(isset($_POST['game_id']) && $_POST['game_id'] != "")
{
	$spieler_ids = $_POST['spieler_id'];
	$table = $wpdb->prefix."statistiken";
	$game_id = $_POST['game_id'];
	foreach($spieler_ids as $spieler_id)
	{
		
		$gespielt = (isset($_POST['gespielt'][$spieler_id]))?$_POST['gespielt'][$spieler_id]:'N';
		$ab = $_POST['ab'][$spieler_id];
		$h = $_POST['h'][$spieler_id];
		$r = $_POST['r'][$spieler_id];
		$db = $_POST['2b'][$spieler_id];
		$tb = $_POST['3b'][$spieler_id];
		$hr = $_POST['hr'][$spieler_id];
		$bb = $_POST['bb'][$spieler_id];
		$hbp = $_POST['hbp'][$spieler_id];
		$sh = $_POST['sh'][$spieler_id];
		$sb = $_POST['sb'][$spieler_id];
		$so = $_POST['so'][$spieler_id];
		$cs = $_POST['cs'][$spieler_id];
		$e = $_POST['errors'][$spieler_id];
		$rbi = $_POST['rbi'][$spieler_id];
		$po = $_POST['po'][$spieler_id];
		$assists = $_POST['assists'][$spieler_id];
		
		
		$pa = $ab + $bb + $hbp + $sh;
		if(($ab + $bb + $hbp + $sh)  != 0 ) {
			$obs = ($h+$db+$tb+$hr+$bb+$hbp)/($ab+$bb+$hbp+$sh);
		}else {
			$obs = 0;
		}
		if(($ab)  != 0 ) {	
			$slg = (($h)+(2*$db)+(3*$tb)+(4*$hr))/$ab;
			$avg = ($h+$db+$tb+$hr)/$ab;
		}else {
			$slg = 0;
			$avg = 0;
		}
		$stat_id = $wpdb->get_var("SELECT id FROM ".$table." WHERE game_id = ".$game_id." AND player_id = ".$spieler_id."");
		if($stat_id != "") {
			$wpdb->update($table,array("player_id"=>$spieler_id,"game_id"=>$game_id,"ab"=>$ab,"hits"=>$h,"doubles"=>$db,"triples"=>$tb,"homeruns"=>$hr,"runs"=>$r,"walks"=>$bb,"hbp"=>$hbp,"sac"=>$sh,"sb"=>$sb,"so"=>$so,"errors"=>$e,"pa"=>$pa,"avg"=>$avg,"obs"=>$obs,"slg"=>$slg,"gespielt"=>$gespielt,"rbi"=>$rbi,"cs"=>$cs,"po"=>$po,"assists"=>$assists),array("id"=>$stat_id));
			
		}else {
		$wpdb->insert($table,array("player_id"=>$spieler_id,"game_id"=>$game_id,"ab"=>$ab,"hits"=>$h,"doubles"=>$db,"triples"=>$tb,"homeruns"=>$hr,"runs"=>$r,"walks"=>$bb,"hbp"=>$hbp,"sac"=>$sh,"sb"=>$sb,"so"=>$so,"errors"=>$e,"pa"=>$pa,"avg"=>$avg,"obs"=>$obs,"slg"=>$slg,"gespielt"=>$gespielt,"rbi"=>$rbi));
		}
	}
	
	?>
	<div class="updated"><p><strong>Gespeichert</strong></p></div>  
	<?
}


if(isset($_GET['game_id']))
{
	$spieler_list = $wpdb->get_results("SELECT spieler.name,spieler.id,spieler.vorname FROM ".$wpdb->prefix."team_members as spieler WHERE spieler.team_id = (SELECT liga.team_id FROM ".$wpdb->prefix."spiele as spiele LEFT JOIN ".$wpdb->prefix."ligen as liga ON(liga.id = spiele.liga_id) WHERE spiele.id = ".$_GET['game_id'].") ORDER BY spieler.name");
	
	$stat_list = $wpdb->get_results("SELECT stats.* FROM ".$wpdb->prefix."statistiken as stats WHERE stats.game_id = '".$_GET['game_id']."'");
	$game = $wpdb->get_row("SELECT game.* FROM ".$wpdb->prefix."spiele as game WHERE game.id = ".$_GET['game_id']."");

foreach($stat_list as $player_stats)
{
	$stats[$player_stats->player_id] = $player_stats;
}

?>
<h3><?php echo $game->datum.",".$game->gegner;?></h3>
<form name="new_team_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
<h2>Offense</h2>
<input type="hidden" name="game_id" value="<?php echo (isset($game->id))?$game->id:"";?>" />
<input type="hidden" name="saison" value="<?php echo (isset($game->id))?$game->saison:"";?>" />
<table class="widefat">
<thead>
<th class="manage-column">Spieler</th>
<th class="manage-column">gespielt</th>
<th class="manage-column">AB</th>
<th class="manage-column">1B</th>
<th class="manage-column">2B</th>
<th class="manage-column">3B</th>
<th class="manage-column">HR</th>
<th class="manage-column">R</th>
<th class="manage-column">RBI</th>
<th class="manage-column">BB</th>
<th class="manage-column">HBP</th>
<th class="manage-column">SO</th>
<th class="manage-column">SB</th>
<th class="manage-column">CS</th>
<th class="manage-column">SH</th>

</thead>

<?
foreach($spieler_list as $spieler)
{

?>
<tr>
	<td><?php echo $spieler->name;?><input type="hidden" name="spieler_id[]" value="<?php echo $spieler->id;?>" /></td>
	<td><input type="checkbox" name="gespielt[<?php echo $spieler->id;?>]" value="Y" <?php echo (isset($stats[$spieler->id]->gespielt) && $stats[$spieler->id]->gespielt == "Y")?"checked='checked'":""; ?>" /></td>
	<td><input type="text" name="ab[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->ab))?$stats[$spieler->id]->ab:""; ?>" size="3"/></td>
	<td><input type="text" name="h[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->hits))?$stats[$spieler->id]->hits:""; ?>" size="3"/></td>
	<td><input type="text" name="2b[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->doubles))?$stats[$spieler->id]->doubles:""; ?>" size="3" /></td>
	<td><input type="text" name="3b[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->triples))?$stats[$spieler->id]->triples:""; ?>" size="3"/></td>
	<td><input type="text" name="hr[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->homeruns))?$stats[$spieler->id]->homeruns:""; ?>" size="3"/></td>
	<td><input type="text" name="r[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->runs))?$stats[$spieler->id]->runs:""; ?>" size="3"/></td>
	<td><input type="text" name="rbi[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->rbi))?$stats[$spieler->id]->rbi:""; ?>" size="3"/></td>
	<td><input type="text" name="bb[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->walks))?$stats[$spieler->id]->walks:""; ?>" size="3"/></td>
	<td><input type="text" name="hbp[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->hbp))?$stats[$spieler->id]->hbp:""; ?>" size="3"/></td>
	<td><input type="text" name="so[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->so))?$stats[$spieler->id]->so:""; ?>" size="3"/></td>
	<td><input type="text" name="sb[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->sb))?$stats[$spieler->id]->sb:""; ?>" size="3"/></td>
	<td><input type="text" name="cs[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->cs))?$stats[$spieler->id]->cs:""; ?>" size="3"/></td>
	<td><input type="text" name="sh[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->sac))?$stats[$spieler->id]->sac:""; ?>" size="3"/></td>
		
</tr>
<?
}
?>
</table>

<h2>Defense</h2>
<input type="hidden" name="game_id" value="<?php echo (isset($game->id))?$game->id:"";?>" />
<table class="widefat">
<thead>
<th class="manage-column">Spieler</th>
<th class="manage-column">PO</th>
<th class="manage-column">A</th>
<th class="manage-column">E</th>

</thead>

<?
foreach($spieler_list as $spieler)
{

?>
<tr>
	<td><?php echo $spieler->name;?></td>
	<td><input type="text" name="po[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->po))?$stats[$spieler->id]->po:""; ?>" size="3"/></td>
	<td><input type="text" name="assists[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->assists))?$stats[$spieler->id]->assists:""; ?>" size="3"/></td>
	<td><input type="text" name="errors[<?php echo $spieler->id;?>]" value="<?php echo (isset($stats[$spieler->id]->errors))?$stats[$spieler->id]->errors:""; ?>" size="3" /></td>
		
</tr>
<?
}
?>
</table>

	<p class="submit">  
	        <input type="submit" name="Submit" value="Eintragen" />  
	        </p>  
</form>
<?
}else {
	//Spielauswahl
	$games = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spiele");
	echo '<form method="get" action="">';
	echo '<input type="hidden" name="page" value="new-stats" />';
	echo '<select name="game_id">';
	foreach($games as $game)
	{
		echo '<option value="'.$game->id.'">'.$game->datum.','.$game->gegner.'</option>';
	}
	echo '</select>';
	echo '<p class="submit"><input type="submit" name="Submit" value="Eintragen" /></p></form>';
}
?>
