<h2>Neues Ergebnis</h2>
<? 
	$innings = 7;
if(isset($_POST['game_id']) && $_POST['game_id'] != "")
{

	
	
	$table = $wpdb->prefix."spiele";
	$game_id = $_POST['game_id'];
	
	##$score_heim = $_POST['score_heim'];
	##$score_gast = $_POST['score_gast'];
	
	$score_heim = $_POST['rhe_heim'][$innings+1];
	$score_gast = $_POST['rhe_gast'][$innings+1];
	
	$heim_box = array($_POST["heim"],$_POST['rhe_heim']);
	$gast_box = array($_POST["gast"],$_POST['rhe_gast']);

	$heimscore = serialize($heim_box);
	$gastscore = serialize($gast_box);	
	
	$wpdb->update($table,array("score_heim"=>$score_heim,"score_gast"=>$score_gast, "box_heim"=>$heimscore,"box_gast"=>$gastscore),array("id"=>$game_id));
	
	?>
	<div class="updated"><p><strong>Gespeichert</strong></p></div>  
	<?
}

if(isset($_GET['game_id']))
{
	$game = $wpdb->get_row("SELECT game.* FROM ".$wpdb->prefix."spiele as game WHERE game.id = ".$_GET['game_id']."");

	if($game->ort == "Münster")
	{
		$heim = "Cardinals";
		$gast = $game->gegner;
	}else 
	{
		$heim = $game->gegner;
		$gast = "Cardinals";
	}


$heim_box_tp = unserialize($game->box_heim);
$gast_box_tp = unserialize($game->box_gast);

include_once('templates/result_form.php');

}else {
	//Spielauswahl
	$games = $wpdb->get_results("SELECT spiele.*,liga.name as liganame FROM ".$wpdb->prefix."spiele as spiele LEFT JOIN ".$wpdb->prefix."ligen as liga ON liga.id = spiele.liga_id ORDER BY spiele.datum");
	echo '<form method="get" action="">';
	echo '<input type="hidden" name="page" value="new-result" />';
	echo '<select name="game_id">';
	foreach($games as $game)
	{
		echo '<option value="'.$game->id.'">'.$game->datum.','.$game->gegner.' ('.$game->liganame.')</option>';
	}
	echo '</select>';
	echo '<p class="submit"><input type="submit" name="Submit" value="Eintragen" /></p></form>';
}
?>
