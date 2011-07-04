<?

$team_id = get_post_meta($post_id, '_statistik', true);

$team = $wpdb->get_results("SELECT spieler.id,spieler.name,spieler.vorname,spieler.nummer FROM ".$wpdb->prefix."team_members as spieler WHERE spieler.team_id = ".$team_id." ORDER BY spieler.name ASC");

$min_games = $wpdb->get_var("SELECT COUNT(spiele.id) FROM ".$wpdb->prefix."spiele as spiele LEFT JOIN ".$wpdb->prefix."ligen as liga ON(spiele.liga_id = liga.id) WHERE liga.team_id = ".$team_id." AND (spiele.score_heim != 0 AND spiele.score_gast != 0)");
$min_games = round($min_games/2);

$statistik = '<h2>Offense</h2><table class="statistik" border="0" cellpadding="0" cellspacing="0">';
$statistik .= '<tr><th class="border">Spieler</th><th class="border">G</th><th>PA</th><th class="border">AB</th><th>H</th><th>2B</th><th>3B</th><th class="border">HR</th><th>RBI</th><th>R</th><th>BB</th><th class="border">K</th><th>SAC</th><th class="border">HBP</th><th>SB</th><th class="border">CS</th><th>AVG</th><th>OBP</th><th>SLG</th></tr>';

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

foreach($team as $spieler)
{
	$spieler_id = $spieler->id;
	$stats = $wpdb->get_row("SELECT SUM(stats.ab) as ab, SUM(stats.hits) as hits, SUM(stats.doubles) as doubles, SUM(stats.triples) as triples, SUM(stats.homeruns) as homeruns, SUM(stats.walks) as walks, SUM(stats.sac) as sh, SUM(stats.hbp) as hbp, SUM(stats.sb) as sb, SUM(stats.so) as so, SUM(stats.rbi) as rbi, SUM(stats.runs) as runs, SUM(stats.cs) as cs FROM ".$wpdb->prefix."statistiken as stats WHERE stats.player_id = ".$spieler_id."");
	
	$games = $wpdb->get_var("SELECT SUM(stats.gespielt) FROM ".$wpdb->prefix."statistiken as stats WHERE stats.gespielt = 'Y' AND stats.player_id = ".$spieler_id."");
	
	
	if($max_hits < ($stats->hits)+($stats->doubles)+($stats->triples)+($stats->homeruns))
	{ $max_hits = ($stats->hits)+($stats->doubles)+($stats->triples)+($stats->homeruns);$max_hits_id = $spieler_id; }
	if($max_doubles < $stats->doubles)
	{ $max_doubles = $stats->doubles;$max_doubles_id = $spieler_id; }
	if($max_triples < $stats->triples)
	{ $max_triples = $stats->triples;$max_triples_id = $spieler_id; }
	if($max_homeruns < $stats->homeruns)
	{ $max_homeruns = $stats->homeruns;$max_homeruns_id = $spieler_id; }
	if($max_rbi < $stats->rbi)
	{ $max_rbi = $stats->rbi;$max_rbi_id = $spieler_id; }
	if($max_runs < $stats->runs)
	{ $max_runs = $stats->runs;$max_runs_id = $spieler_id; }
	if($max_walks < $stats->walks)
	{ $max_walks = $stats->walks;$max_walks_id = $spieler_id; }
	if($max_k < $stats->so)
	{ $max_k = $stats->so;$max_k_id = $spieler_id; }
	if($max_sac < $stats->sh)
	{ $max_sac = $stats->sh;$max_sac_id = $spieler_id; }
	if($max_hbp < $stats->hbp)
	{ $max_hbp = $stats->hbp;$max_hbp_id = $spieler_id; }
	if($max_sb < $stats->sb)
	{ $max_sb = $stats->sb;$max_sb_id = $spieler_id; }
	if($max_cs < $stats->cs)
	{ $max_cs = $stats->cs;$max_cs_id = $spieler_id; }
	
	if(($stats->ab + $stats->walks + $stats->hbp + $stats->sh)  != 0 ) {
		$obp = ($stats->hits+$stats->doubles+$stats->triples+$stats->homeruns+$stats->walks+$stats->hbp)/($stats->ab+$stats->walks+$stats->hbp+$stats->sh);
		if($max_obp < $obp && $games >= $min_games) {
			$max_obp_id = $spieler_id;$max_obp = $obp;
		}
	}else {
		$obp = 0;
	}
	if(($stats->ab)  != 0 ) {
		$slg = (($stats->hits)+(2*$stats->doubles)+(3*$stats->triples)+(4*$stats->homeruns))/$stats->ab;
		$avg = ($stats->hits+$stats->doubles+$stats->triples+$stats->homeruns)/$stats->ab;
		if($max_slg < $slg && $games >= $min_games) {
			$max_slg_id = $spieler_id;$max_slg = $slg;
		}
		if($max_avg < $avg && $games >= $min_games) {
			$max_avg_id = $spieler_id;$max_avg = $avg;
		}
	}else {
		$avg = 0;
		$slg = 0;
	}
}
foreach($team as $spieler)
{
	$spieler_id = $spieler->id;
	$stats = $wpdb->get_row("SELECT SUM(stats.gespielt), SUM(stats.ab) as ab, SUM(stats.hits) as hits, SUM(stats.doubles) as doubles, SUM(stats.triples) as triples, SUM(stats.homeruns) as homeruns, SUM(stats.walks) as walks, SUM(stats.sac) as sh, SUM(stats.hbp) as hbp, SUM(stats.sb) as sb, SUM(stats.so) as so, SUM(stats.rbi) as rbi, SUM(stats.runs) as runs, SUM(stats.cs) as cs FROM ".$wpdb->prefix."statistiken as stats WHERE stats.player_id = ".$spieler_id."");
	$games = $wpdb->get_var("SELECT SUM(stats.gespielt) FROM ".$wpdb->prefix."statistiken as stats WHERE stats.gespielt = 'Y' AND stats.player_id = ".$spieler_id."");
	if($games == "")
	{
		$games = 0;
	}
	##print_r($stats);
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
	$hits = ($stats->hits)+($stats->doubles)+($stats->triples)+($stats->homeruns);
	$statistik .= '<tr><td class="border">';
	$statistik .= '<a href="../spieler/#'.$spieler->id.'">'.$spieler->vorname.' '.substr($spieler->name,0,1).'. (#'.$spieler->nummer.')</td>';
	$statistik .= '<td class="border">'.$games.'</td>';
	$statistik .= '<td>'.$pa.'</td>';
	$statistik .= '<td class="border">'.$stats->ab.'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_hits_id)?'<b>'.$hits.'</b>':$hits).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_doubles_id)?'<b>'.$stats->doubles.'</b>':$stats->doubles).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_triples_id)?'<b>'.$stats->triples.'</b>':$stats->triples).'</td>';
	$statistik .= '<td class="border">'.(($spieler_id == $max_homeruns_id)?'<b>'.$stats->homeruns.'</b>':$stats->homeruns).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_rbi_id)?'<b>'.$stats->rbi.'</b>':$stats->rbi).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_runs_id)?'<b>'.$stats->runs.'</b>':$stats->runs).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_walks_id)?'<b>'.$stats->walks.'</b>':$stats->walks).'</td>';
	$statistik .= '<td class="border">'.(($spieler_id == $max_k_id)?'<b>'.$stats->so.'</b>':$stats->so).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_sac_id)?'<b>'.$stats->sh.'</b>':$stats->sh).'</td>';
	$statistik .= '<td class="border">'.(($spieler_id == $max_hbp_id)?'<b>'.$stats->hbp.'</b>':$stats->hbp).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_sb_id)?'<b>'.$stats->sb.'</b>':$stats->sb).'</td>';
	$statistik .= '<td class="border">'.(($spieler_id == $max_cs_id)?'<b>'.$stats->cs.'</b>':$stats->cs).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_avg_id)?'<b>'.number_format($avg,3,".",",").'</b>':number_format($avg,3,".",",")).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_obp_id)?'<b>'.number_format($obp,3,".",",").'</b>':number_format($obp,3,".",",")).'</td>';
	$statistik .= '<td>'.(($spieler_id == $max_slg_id)?'<b>'.number_format($slg,3,".",",").'</b>':number_format($slg,3,".",",")).'</td>';
	$statistik .= '</tr>';
}
$statistik .= "</table>";

$statistik .= '<h2>Defense</h2><table class="statistik" border="0" cellpadding="0" cellspacing="0">';
$statistik .= '<tr><th>Spieler</th><th>G</th><th>PO</th><th>A</th><th>E</th><th>Fld. %</th></tr>';
foreach($team as $spieler)
{
	$spieler_id = $spieler->id;
	$stats = $wpdb->get_row("SELECT SUM(stats.po) as po, SUM(stats.assists) as assists, SUM(stats.errors) as errors FROM ".$wpdb->prefix."statistiken as stats WHERE stats.player_id = ".$spieler_id."");
	$games = $wpdb->get_var("SELECT SUM(stats.gespielt) FROM ".$wpdb->prefix."statistiken as stats WHERE stats.gespielt = 'Y' AND stats.player_id = ".$spieler_id."");
	if($games == "")
	{
		$games = 0;
	}
	##print_r($stats);
	if($stats->po+$stats->assists+$stats->errors > 0)
	{
		$fielding = ($stats->po+$stats->assists) / ($stats->po+$stats->assists+$stats->errors);
	}else {
		$fielding = 0;
	}
	$statistik .= '<tr><td>';
	$statistik .= '<a href="../spieler/#'.$spieler->id.'">'.$spieler->vorname.' '.substr($spieler->name,0,1).'. (#'.$spieler->nummer.')</td>';
	$statistik .= '<td>'.$games.'</td>';
	$statistik .= '<td>'.$stats->po.'</td>';
	$statistik .= '<td>'.$stats->assists.'</td>';
	$statistik .= '<td>'.$stats->errors.'</td>';
	$statistik .= '<td>'.number_format($fielding,3,".",",").'</td>';
	$statistik .= '</tr>';
}
$statistik .= "</table>";