<?



$game_id = get_post_meta($post_id, '_spielbericht', true);

$flickr_set = get_post_meta($post_id, '_flickr', true);

$game = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."spiele WHERE id = ".$game_id);
$stats = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."statistiken WHERE game_id = ".$game_id);

$team_id = $wpdb->get_var("SELECT ligen.team_id FROM ".$wpdb->prefix."ligen as ligen WHERE ligen.id =".$game->liga_id);

$gegner_tmp = split(" ",$game->gegner);
$gegner = "";
for($i=1;$i<count($gegner_tmp);$i++)
{
	$gegner .= $gegner_tmp[$i]." ";
}
$ms = true;
if($game->ort != "Münster")
{
	$ms = false;
	
}
list($jahr,$monat,$tag) = explode('-',$game->datum);
$datum = $tag.".".$monat.".".$jahr;

$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);

##print_r($matches);

$min_games = 0;
$team = $wpdb->get_results("SELECT spieler.id, spieler.name,spieler.vorname FROM ".$wpdb->prefix."player_teams as pt LEFT JOIN ".$wpdb->prefix."team_members as spieler ON (spieler.id = pt.player_id) WHERE pt.team_id = ".$team_id);
$statistik = '<h2>Offense</h2><table class="statistik" border="0" cellpadding="0" cellspacing="0">';
$statistik .= '<tr><th class="border">Spieler</th><th class="border">AB</th><th>H</th><th>RBI</th><th>R</th><th>BB</th><th class="border">K</th><th>AVG</th><th>OBP</th><th>SLG</th></tr>';

$max_hits = 0;
$max_doubles = 0;
$max_triples = 0;
$max_homeruns = 0;
$max_rbi = 0;
$max_runs = 0;
$max_walks = 0;
$max_k = 0;
$max_sac = 0;
$max_hbp = 0;
$max_sb = 0;
$max_cs = 0;
$max_avg = 0;
$max_obp = 0;
$max_slg = 0;

$max_hits_id = 0;
$max_doubles_id = 0;
$max_triples_id = 0;
$max_homeruns_id = 0;
$max_rbi_id = 0;
$max_runs_id = 0;
$max_walks_id = 0;
$max_k_id = 0;
$max_sac_id = 0;
$max_hbp_id = 0;
$max_sb_id = 0;
$max_cs_id = 0;
$max_avg_id = 0;
$max_obp_id = 0;
$max_slg_id = 0;

$team_abs = 0;
$team_hits = 0;
$team_doubles = 0;
$team_triples = 0;
$team_hr = 0;
$team_walks = 0;
$team_hbp = 0;
$team_sac = 0;
$team_k = 0;
$team_rbi = 0;
$team_runs = 0;

$team_double_hitter = array();
$team_tripple_hitter = array();
$team_homerun_hitter = array();
$team_rbi_hitter = array();


foreach($team as $spieler)
{
	$spieler_id = $spieler->id;
	$stats = $wpdb->get_row("SELECT (stats.gespielt), (stats.ab) as ab, (stats.hits) as hits, (stats.doubles) as doubles, (stats.triples) as triples, (stats.homeruns) as homeruns, (stats.walks) as walks, (stats.sac) as sh, (stats.hbp) as hbp, (stats.sb) as sb, (stats.so) as so, (stats.rbi) as rbi, (stats.runs) as runs, (stats.cs) as cs,stats.gespielt FROM ".$wpdb->prefix."statistiken as stats WHERE stats.player_id = ".$spieler_id." AND game_id = ".$game_id."");
	$games = $wpdb->get_var("SELECT SUM(stats.gespielt) FROM ".$wpdb->prefix."statistiken as stats WHERE stats.gespielt = 'Y' AND stats.player_id = ".$spieler_id."");
	if($games == "")
	{
		$games = 0;
	}
	##print_r($stats);
	$team_abs += $stats->ab;
	$team_hits += $stats->hits;
	$team_doubles += $stats->doubles;
	$team_triples += $stats->triples;
	$team_hr += $stats->homeruns;
	$team_walks += $stats->walks;
	$team_hbp += $stats->hbp;
	$team_sac += $stats->sh;
	$team_k += $stats->so;
	$team_rbi += $stats->rbi;
	$team_runs += $stats->runs;
	
	if($stats->doubles > 0)
	{
		$team_double_hitter[] = $spieler->name.' ('.$stats->doubles.')';
	}
	if($stats->triples > 0)
	{
		$team_tripple_hitter[] = $spieler->name.' ('.$stats->triples.')';
	}
	if($stats->homeruns > 0)
	{
		$team_homerun_hitter[] = $spieler->name.' ('.$stats->homeruns.')';
	}
	if($stats->rbi > 0)
	{
		$team_rbi_hitter[] = $spieler->name.' ('.$stats->rbi.')';
	}
	
	$pa = $stats->ab + $stats->walks + $stats->hbp + $stats->sh;
	if(($stats->ab + $stats->walks + $stats->hbp + $stats->sh)  != 0 ) {
		$obp = ($stats->hits+$stats->doubles+$stats->triples+$stats->homeruns+$stats->walks+$stats->hbp)/($stats->ab+$stats->walks+$stats->hbp+$stats->sh);
	}else {
		$obp = 0;
	}
	if(($stats->ab)  != 0 ) {
		$slg = (($stats->hits)+(2*$stats->doubles)+(3*$stats->triples)+(4*$stats->homeruns))/$stats->ab;
		$avg = ($stats->hits+$stats->doubles+$stats->triples+$stats->homeruns)/$stats->ab;
	}else {
		$avg = 0;
		$slg = 0;
	}
	if($stats->gespielt == 'Y')
	{
	$hits = ($stats->hits)+($stats->doubles)+($stats->triples)+($stats->homeruns);
	$statistik .= '<tr><td class="border">';
	$statistik .= ''.$spieler->vorname.' '.substr($spieler->name,0,1).'. </td>';
	$statistik .= '<td class="border">'.$stats->ab.'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_hits_id)?'<b>'.$hits.'</b>':$hits).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_rbi_id)?'<b>'.$stats->rbi.'</b>':$stats->rbi).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_runs_id)?'<b>'.$stats->runs.'</b>':$stats->runs).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_walks_id)?'<b>'.$stats->walks.'</b>':$stats->walks).'</td>';
	$statistik .= '<td class="border">'.(($spieler_id == $max_k_id)?'<b>'.$stats->so.'</b>':$stats->so).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_avg_id)?'<b>'.number_format($avg,3,".",",").'</b>':number_format($avg,3,".",",")).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_obp_id)?'<b>'.number_format($obp,3,".",",").'</b>':number_format($obp,3,".",",")).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_slg_id)?'<b>'.number_format($slg,3,".",",").'</b>':number_format($slg,3,".",",")).'</td>';
	$statistik .= '</tr>';
	}
}

$team_avg = ($team_hits+$team_doubles+$team_triples+$team_hr)/$team_abs;
$team_obp = ($team_hits+$team_doubles+$team_triples+$team_hr+$team_walks+$team_hbp)/($team_abs+$team_walks+$team_hbp+$team_sac);
$team_slg = (($team_hits)+(2*$team_doubles)+(3*$team_triples)+(4*$team_hr))/$team_abs;



$statistik .= "<tr><td class='border'>Total</td><td class='border'>".$team_abs."</td><td>".($team_hits+$team_doubles+$team_triples+$team_hr)."</td><td>".$team_rbi."</td><td>".$team_runs."</td><td>".$team_walks."</td><td class='border'>".$team_k."</td><td>".number_format($team_avg,3,".",",")."</td><td>".number_format($team_obp,3,".",",")."</td><td>".number_format($team_slg,3,".",",")."</td></tr>";
$statistik .= "</table>";
/*
$statistik .= '<h2>Defense</h2><table class="statistik" border="0" cellpadding="0" cellspacing="0">';
$statistik .= '<tr><th>Spieler</th><th>G</th><th>PO</th><th>A</th><th>E</th><th>Fld. %</th></tr>';
foreach($team as $spieler)
{
	$spieler_id = $spieler->id;
	$stats = $wpdb->get_row("SELECT SUM(stats.po) as po, SUM(stats.assists) as assists, SUM(stats.errors) as errors,stats.gespielt FROM ".$wpdb->prefix."statistiken as stats WHERE stats.player_id = ".$spieler_id." AND stats.game_id = ".$game_id);
	$games = $wpdb->get_var("SELECT SUM(stats.gespielt) FROM ".$wpdb->prefix."statistiken as stats WHERE stats.gespielt = 'Y' AND stats.player_id = ".$spieler_id." AND stats.game_id = ".$game_id);
	if($games == "")
	{
		$games = 0;
	}
	if($stats->gespielt == 'Y')
	{
	##print_r($stats);
	if($stats->po+$stats->assists+$stats->errors > 0)
	{
		$fielding = ($stats->po+$stats->assists) / ($stats->po+$stats->assists+$stats->errors);
	}else {
		$fielding = 0;
	}
	$statistik .= '<tr><td>';
	$statistik .= '<a href="../spieler/#'.$spieler->id.'">'.$spieler->vorname.' '.substr($spieler->name,0,1).'. </td>';
	$statistik .= '<td>'.$games.'</td>';
	$statistik .= '<td>'.$stats->po.'</td>';
	$statistik .= '<td>'.$stats->assists.'</td>';
	$statistik .= '<td>'.$stats->errors.'</td>';
	$statistik .= '<td>'.number_format($fielding,3,".",",").'</td>';
	$statistik .= '</tr>';
	}
}
$statistik .= "</table>";
*/
?>
<div id="game_nav_container">
<div class="matchup"><? echo ($ms)?$gegner:"Cardinals";?> vs. <? echo ($ms)?"Cardinals":$gegner;?></div>
<div class="game_nav">
<ul id="game_nav">
	<li class="first"><a class="wrap" href="javascript:;">Übersicht</a></li>
	<li <? if(count($stats) == 0 && !$flickr_set){?>class="last"<? } ?>><a class="bericht" href="javascript:;">Bericht</a></li>
	<? if(count($stats) > 0) { ?><li><a class="stats" href="javascript:;">Statistiken</a></li><? } ?>
	<? if($flickr_set){?><li class="last"><a href="javascript:;" class="fotos">Fotos</a></li><? } ?>
</ul>
</div>
<div class="datum"><? echo $datum;?></div>
</div>
<div id="gamewrap"></div>
<div id="pre_wrap">
<div id="game_impressions">
	<? if(count($matches[1]) > 0)
	{
		$src =  $matches[1][0];
	}else { 
		$src = TEAMIMAGES."homeplate.jpg";
	}
	?>
	<img src="<? echo $src;?>" width="750"/>
	<h1><?php echo the_title(); ?></h1>
	<div class="game_score">	&nbsp;	
	</div>
	<h3><? echo (!$ms)?"Münster Cardinals":$game->gegner;?> <? echo $game->score_gast;?>, <? echo ($ms)?"Münster Cardinals":$game->gegner;?> <? echo $game->score_heim;?></h3>
	

</div>	
<? if($game->box_heim)
{ ?>
<dl class="game_detail" id="box_score">
<dt>Boxscore</dt>
<dd>
<table border="0" cellpadding="0" cellspacing="0">
<?

$heim_box_tp = unserialize($game->box_heim);
$gast_box_tp = unserialize($game->box_gast);

if($game->ort == "Münster")
{
	$heim = "Cardinals";
	$gast = $gegner;
}else 
{
	$heim = $gegner;
	$gast = "Cardinals";
}


$innings = 7;
for($j=0;$j<3;$j++)
{
	?>
		<tr><? echo ($j>0)?"<td>".(($j==1)?$heim:$gast)."</td>":"<th></th>";?>
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
					case $innings+1: echo "R";break;
					case $innings+2: echo "H";break;
					case $innings+3: echo "E";break;
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
			<td><?
			if($box == "heim")
			{
				echo ($i>$innings)?$heim_box_tp[1][$i]:$heim_box_tp[0][$i];
			}else
			{
				echo ($i>$innings)?$gast_box_tp[1][$i]:$gast_box_tp[0][$i];
			}	
			
			?></td>
		
		<?
		}
	}
?>
	</tr>
<?
}
?>

</table>
</dd>
</dl>
<dl class="game_detail" id="bericht_excerp">
<dd><? echo getShortBericht($content, 50)."...";?> &nbsp;<a href="javascript:;" class="bericht">Bericht lesen</a></dd>
</dl>

<?
} else { ?>
<div><? echo getShortBericht($content, 50)."...";?> &nbsp;<a href="javascript:;" class="bericht">Bericht lesen</a></div>
<? } 

?>
<p>Teamstatistik</p>
<table border="0" cellpadding="0" cellspacing="0">
<tr><th>AVG</th><th>OBP</th><th>SLG</th></tr>
<tr><td><?php echo number_format($team_avg,3,".",",");?></td>
<td><?php echo number_format($team_obp,3,".",",");?></td>
<td><?php echo number_format($team_slg,3,".",",");?></td>
</tr>
</table>
<p>
<?php if(count($team_double_hitter) > 0 ){ ?><b>2B</b>:<?php echo join($team_double_hitter, ", ");?><br/>
<? } 
if(count($team_tripple_hitter) > 0 ) { ?><b>3B</b>:<?php echo join($team_tripple_hitter, ", ");?><br/>
<?
}
if(count($team_homerun_hitter) > 0 ) { ?><b>HR</b>:<?php echo join($team_homerun_hitter, ", ");?><br/>
<?
}
if(count($team_rbi_hitter) > 0 ) { ?><b>RBI<?php echo (count($team_rbi_hitter) > 1)?"s":"";?></b>:<?php echo join($team_rbi_hitter, ", ");?><br/>
<?
}
?></p>
</div>
<div class="clear"></div>
<div id="bericht_content">
<h2><?php echo the_title(); ?></h2>
<? echo $content; ?>
</div>
<? if($game->box_heim) { ?>
<div id="">
</div>
<? }
if(count($stats) > 0)
{ ?>
<div id="statistik_content">
<h2>Statistiken</h2>
<?

echo $statistik;
echo '<br/><br/>'; ?>
<p>
<?php if(count($team_double_hitter) > 0 ){ ?><b>2B</b>:<?php echo join($team_double_hitter, ", ");?><br/>
<? } 
if(count($team_tripple_hitter) > 0 ) { ?><b>3B</b>:<?php echo join($team_tripple_hitter, ", ");?><br/>
<?
}
if(count($team_homerun_hitter) > 0 ) { ?><b>HR</b>:<?php echo join($team_homerun_hitter, ", ");?><br/>
<?
}
if(count($team_rbi_hitter) > 0 ) { ?><b>RBI<?php echo (count($team_rbi_hitter) > 1)?"s":"";?></b>:<?php echo join($team_rbi_hitter, ", ");?><br/>
<?
}
?></p>
</div>
<? } ?>

<div id="fotos_content" title="<? echo $flickr_set;?>">
<div id="loading"><img src="<?php echo WP_PLUGIN_URL;?>/team/images/ajax.gif" id="loader" /><br/><p>Inhalte werden geladen</p></div>
</div>

