<?
$team_id = get_post_meta($post_id, '_ergebnisliste', true);

$monate = array("01"=>"Jan",
                "02"=>"Feb",
                "03"=>"Mrz",
                "04"=>"Apr",
                "05"=>"Mai",
                "06"=>"Jun",
                "07"=>"Jul",
                "08"=>"Aug",
                "09"=>"Sept",
                "10"=>"Okt",
                "11"=>"Nov",
                "12"=>"Dez");

$liga_id = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."ligen WHERE team_id = ".$team_id."");
$ergebnisse = $wpdb->get_results("SELECT spiele.* FROM ".$wpdb->prefix."spiele as spiele WHERE spiele.liga_id = ".$liga_id." ORDER BY spiele.datum");

$liste = '';

$termine = '<ul id="kalender_spieltermine">';
$first = ' class="first"';
foreach($ergebnisse as $ergebnis)
{
	$ergebnisse_overlay = "";
	$spielbericht_id = $wpdb->get_var("SELECT post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_spielbericht' AND meta_value = ".$ergebnis->id.' AND post_id != 0') ;
		
	if($ergebnis->ort == "Münster")
	{
		$heim = "Münster Cardinals";
		$gast = $ergebnis->gegner;
	}else 
	{
		$heim = $ergebnis->gegner;
		$gast = "Münster Cardinals";
	}
	$liste .= '<li>';
	$liste .= '<dl><dt class="datum">';
	$termine .= '<li'.$first.'><dl><dt>';
	$first = '';
	list($jahr,$monat,$tag) = explode('-',$ergebnis->datum);
	$datum = $tag.".".$monat.".".$jahr;
	
	if($spielbericht_id != ""){
		$liste .= '<a href="/?p='.$spielbericht_id.'">'.$datum.'</a>';
	}else{
		$liste .= $datum.'';
	}
	$termine .= $ergebnis->ort;
	$termine .= '</dt><dd>';
	
	$termine .= '<p class="tag">'.$tag.'</p>'.$monate[$monat].'<br/></dd>';
	if(!($ergebnis->score_heim == 0 && $ergebnis->score_gast == 0))
	{
		if($ergebnis->score_heim > $ergebnis->score_gast && $ergebnis->ort == "Münster")
		{
			$win = '<p class="win">W</p>';
		}
		elseif($ergebnis->score_heim < $ergebnis->score_gast && $ergebnis->ort != "Münster")
		{
			$win = '<p class="win">W</p>';
		}
		else
		{
			$win = '<p class="loss">L</p>';
		}
	}
	else
	{
		$win = '<p class="loss">&nbsp;</p>';
	}
	$termine .= '<dd class="ergebnis">'.$win.'</dd>';
	
	$termine .= '</dl></li>';
	$ergebnisse_overlay .= '<div id="spiel_'.$tag.$monat.$jahr.'" class="spiel_ergebnis"></div>';
	
	if(file_exists(TEAMS_DIR.'/logo_cards.png')) {
		$cardsbild = '<img src="'.WP_CONTENT_URL.'/teams/logo_cards.png" width="50" />';
	}
	
	
	
	if($ergebnis->ort == "Münster")
	{
		$heimbild = $cardsbild;
		$g_tmp = explode(" ",$gast);
		$gegner = strtolower($g_tmp[0]);
		$gastbild = '<img src="'.WP_CONTENT_URL.'/teams/blank.png" width="50" />';
		if(file_exists(TEAMS_DIR."/".$gegner.".jpg")) {
			$gastbild = '<img src="'.WP_CONTENT_URL.'/teams/'.$gegner.'.jpg" width="50" />';
		}
		if(file_exists(TEAMS_DIR."/".$gegner.".gif")) {
			$gastbild = '<img src="'.WP_CONTENT_URL.'/teams/'.$gegner.'.gif" width="50" />';
		}
		if(file_exists(TEAMS_DIR."/".$gegner.".png")) {
			$gastbild = '<img src="'.WP_CONTENT_URL.'/teams/'.$gegner.'.png" width="50" />';
		}
		
		
	}
	else
	{
		$gastbild = $cardsbild;
		$g_tmp = explode(" ",$heim);
		$gegner = strtolower($g_tmp[0]);
		$heimbild =  '<img src="'.WP_CONTENT_URL.'/teams/blank.png" width="50" />';
		if(file_exists(TEAMS_DIR."/".$gegner.".jpg")) {
			$heimbild = '<img src="'.WP_CONTENT_URL.'/teams/'.$gegner.'.jpg" width="50" />';
		}
		if(file_exists(TEAMS_DIR."/".$gegner.".gif")) {
			$heimbild = '<img src="'.WP_CONTENT_URL.'/teams/'.$gegner.'.gif" width="50" />';
		}
		if(file_exists(TEAMS_DIR."/".$gegner.".png")) {
			$heimbild = '<img src="'.WP_CONTENT_URL.'/teams/'.$gegner.'.png" width="50" />';
		}
		
	}
	$liste .= '</dt>';
	##$liste .= '<>'.$ergebnis->uhrzeit.':00 Uhr</td>';
	$liste .= '<dd><ul class="ergebnis_detail">';
	$liste .= '<li class="ort">'.$ergebnis->ort.'</li>';
	$liste .= '<li>'.$heimbild.'<p class="teamname">'.$heim.'</p><p class="score">'.$ergebnis->score_heim.'</p></li>';
	$liste .= '<li>'.$gastbild.'<p class="teamname">'.$gast.'</p><p class="score">'.$ergebnis->score_gast.'</p></li>';
	##$liste .= '<li>'..'-'.$ergebnis->score_gast.'</td></tr>';
	$liste .= '</ul><div class="wl">'.$win.'</div></dd></dl></li>';

}


$ergebnisliste = $termine.'</ul>';
$ergebnisliste .= '<div class="column_2_3"><ul id="ergebnis_liste">';
$ergebnisliste .= $liste;
##$ergebnisliste .= '<thead><tr><th>Datum</th><th>Uhrzeit</th><th>Heim</th><th>Gast</th><th>Ergebnis</th></tr>';


$ergebnisliste .= '</ul></div><div class="column_1_3"></div>';