<?
$team_id = get_post_meta($post_id, '_spielerliste', true);

$team = $wpdb->get_results("SELECT spieler.* FROM ".$wpdb->prefix."player_teams as pt LEFT JOIN ".$wpdb->prefix."team_members as spieler ON spieler.id = pt.player_id WHERE pt.team_id = ".$team_id." ORDER BY spieler.name");

shuffle($team);
$spielerliste = '<ul class="spielerliste" cellpadding="0" cellspacing="0" border="0">';
foreach($team as $spieler)
{
	$spielerliste .= '<li><a name="'.$spieler->id.'"></a>';
	if(file_exists(PLAYER_DIR."/player_".$spieler->id.".jpg")) {
		$spielerliste.= '<img src="'.WP_CONTENT_URL.'/players/player_'.$spieler->id.'.jpg" width="250" />';
	}
	elseif(file_exists(PLAYER_DIR."/player_".$spieler->id.".gif")) {
		$spielerliste.= '<img src="'.WP_CONTENT_URL.'/players/player_'.$spieler->id.'.gif" width="250" />';
	}
	elseif(file_exists(PLAYER_DIR."/player_".$spieler->id.".png")) {
		$spielerliste.= '<img src="'.WP_CONTENT_URL.'/players/player_'.$spieler->id.'.png" width="250" />';
	}
	else 
	{
		$spielerliste.= '<img src="'.WP_CONTENT_URL.'/players/keinbild.jpg" width="250" />';
	}
	$spielerliste .= '<dl class="spieler">';
	$spielerliste .= '<dt>';
	$spielerliste .= ''.$spieler->vorname.' '.$spieler->name.' <span>(#'.$spieler->nummer.')</span>';
	$spielerliste .= '</dt>';
	$spielerliste .= '<dd class="position">';
	$spielerliste .= $spieler->positionen;
	$spielerliste .= '</dd>';
	
	$spielerliste .= '<dd>';
	$spielerliste .= '<table border="0">';
	
	$spielerliste .= '<tr><td class="head">Spielt seit: </td>';
	$spielerliste .= '<td>'.$spieler->spielt_seit.'</td></tr>';
	
	$spielerliste .= '<tr><td class="head">Wirft: </td>';
	$spielerliste .= '<td>'.$spieler->wirft.'</td></tr>';
	
	$spielerliste .= '<tr><td class="head">Schlägt: </td>';
	$spielerliste .= '<td>'.$spieler->schlaegt.'</td></tr>';
	
	$spielerliste .= '<tr><td class="head">Vereine: </td>';
	$spielerliste .= '<td>'.$spieler->vereine.'</td></tr>';
	
	$spielerliste .= '</table></dd></dl>';

}

$spielerliste .= '</ul>';