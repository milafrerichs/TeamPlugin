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
	$liste .= '<tr>';
	$liste .= '<td>';
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
	
	$liste .= '</td>';
	$liste .= '<td>'.$ergebnis->uhrzeit.':00 Uhr</td>';
	$liste .= '<td>'.$heim.'</td>';
	$liste .= '<td>'.$gast.'</td>';
	$liste .= '<td>'.$ergebnis->score_heim.'-'.$ergebnis->score_gast.'</td></tr>';
	

}


$ergebnisliste = $termine.'</ul>';
$ergebnisliste .= '<div class="column_2_3"><table class="ergebnisliste" cellpadding="0" cellspacing="0">';
$ergebnisliste .= $liste;
$ergebnisliste .= '<thead><tr><th>Datum</th><th>Uhrzeit</th><th>Heim</th><th>Gast</th><th>Ergebnis</th></tr>';


$ergebnisliste .= '</table></div><div class="column_1_3"></div>';