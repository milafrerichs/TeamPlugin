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
?>
<form name="new_team_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="game_id" value="<?php echo $_GET['game_id'];?>" />
<p>
	<label for="score_heim">
	<?php echo $heim;?>: 
	<input type="text" name="score_heim" value="<? echo (isset($game->score_heim))?$game->score_heim:"";?>" id="score_heim"/>
	</label>
</p>
<p>
	<label for="score_gast">
	<?php echo $gast;?>: 
	<input type="text" name="score_gast" value="<? echo (isset($game->score_gast))?$game->score_gast:"";?>" id="score_gast"/>
	</label>
</p>
<table border="0" cellpadding="0" cellspacing="0">
<? 


$heim_box_tp = unserialize($game->box_heim);
$gast_box_tp = unserialize($game->box_gast);


//serialize array für heim und gast... 

for($j=0;$j<3;$j++)
{
	?>
		<tr><td><? echo ($j>0)?(($j==1)?$heim:$gast):"";?></td>
	<?
	
	for($i=1;$i<=$innings+3;$i++)
	{
		if($j == 0)
		{
		?>
			<th><? 
			if($i<=$innings)
			{
				echo $i;
			}
			else
			{
				switch($i)
				{
					case $innings+1: echo "Runs";break;
					case $innings+2: echo "Hits";break;
					case $innings+3: echo "Errors";break;
				}
			}
			?></th>
		<?
		}
		else
		{
			switch($j)
			{
				case '1' : $box = "heim";break;
				case '2' : $box = "gast";break;
			
			}
						?>
			<td><input type="text" name="<? echo ($i>$innings)?"rhe_".$box."[".$i."]":$box."[".$i."]";?>" value="<?
			if($box == "heim")
			{
				echo ($i>$innings)?$heim_box_tp[1][$i]:$heim_box_tp[0][$i];
			}else
			{
				echo ($i>$innings)?$gast_box_tp[1][$i]:$gast_box_tp[0][$i];
			}	
			
			?>" size="2"/></td>
		
		<?
		}
	}
?>
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
	echo '<input type="hidden" name="page" value="new-result" />';
	echo '<select name="game_id">';
	foreach($games as $game)
	{
		echo '<option value="'.$game->id.'">'.$game->datum.','.$game->gegner.'</option>';
	}
	echo '</select>';
	echo '<p class="submit"><input type="submit" name="Submit" value="Eintragen" /></p></form>';
}
?>
