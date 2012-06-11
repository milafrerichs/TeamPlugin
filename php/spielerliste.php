<?
function returnPlayersWithTeamId($teamId) {
	global $wpdb;
	
	$players = $wpdb->get_results("SELECT spieler.* FROM ".$wpdb->prefix."player_teams as pt LEFT JOIN ".$wpdb->prefix."team_members as spieler ON spieler.id = pt.player_id WHERE pt.team_id = ".$teamId." ORDER BY spieler.name");
	return $players;	
}

function returnRandomOrderedPlayersArray($players) {
	return shuffle($players);	
}

function returnPlayerId($player) {
	return $player->id;
}
function returnPlayerName($player) {
	return $player->vorname." ".$player->name;
}
function returnPlayerNumber($player) {
	return $player->nummer;
}
function returnPlayerPositions($player) {
	return $player->positionen;
}
function returnPlayerPlayingSince($player) {
	return $player->spielt_seit;
}
function returnPlayerThrowingArm($player) {
	return $player->wirft;
}
function returnPlayerBatPosition($player) {
	return $player->schlaegt;
}
function returnPlayerClubs($player) {
	return $player->vereine;
}

function playerHasImage($playerId) {
	if(file_exists(PLAYER_DIR."/player_".$playerId.".jpg")) {
		return true;
	}
	elseif(file_exists(PLAYER_DIR."/player_".$playerId.".gif")) {
		return true;
	}
	elseif(file_exists(PLAYER_DIR."/player_".$playerId.".png")) {
		return true;
	}
	return false;		
}
function returnPlayerImageSrc($playerId) {
	if(file_exists(PLAYER_DIR."/player_".$playerId.".jpg")) {
		return WP_CONTENT_URL.'/players/player_'.$playerId.'.jpg';
	}
	elseif(file_exists(PLAYER_DIR."/player_".$playerId.".gif")) {
		return WP_CONTENT_URL.'/players/player_'.$playerId.'.gif';
	}
	elseif(file_exists(PLAYER_DIR."/player_".$playerId.".png")) {
		return WP_CONTENT_URL.'/players/player_'.$playerId.'.png';
	}
}


function generatePlayerCard($spieler) {
	
	$playerId = returnPlayerId($spieler);
	
	$playingSince = generateHTMLTag("td","","",returnPlayerPlayingSince($spieler));
	$headPlayingSince = generateHTMLTag("td","head","","Spielt seit: ");
	$htmlcolumnPlayingSince = generateHTMLTag("tr","","",$headPlayingSince.$playingSince);
	
	$throwingArm = generateHTMLTag("td","","",returnPlayerThrowingArm($spieler));
	$headThrowingArm = generateHTMLTag("td","head","","Wirft: ");
	$htmlcolumnThrowingArm = generateHTMLTag("tr","","",$headThrowingArm.$throwingArm);
	
	$batPosition = generateHTMLTag("td","","",returnPlayerBatPosition($spieler));
	$headBatPosition = generateHTMLTag("td","head","","Schlägt: ");
	$htmlcolumnBatPosition = generateHTMLTag("tr","","",$headBatPosition.$batPosition);
	
	$clubs = generateHTMLTag("td","","",returnPlayerClubs($spieler));
	$headClubs = generateHTMLTag("td","head","","Verine: ");
	$htmlcolumnClubs = generateHTMLTag("tr","","",$headClubs.$clubs);
	
	$playerTableHTML = generateHTMLTag("table","","",$htmlcolumnPlayingSince.$htmlcolumnThrowingArm.$htmlcolumnBatPosition.$htmlcolumnClubs,array("border"=>"0"));
	
	$definitionBodyPlayer = generateHTMLTag("dd","","",$playerTableHTML);
	
	$definitionBodyPlayerPosition = generateHTMLTag("dd","position","",returnPlayerPositions($spieler));
	
	$playerNumberContent = " (#".returnPlayerNumber($spieler).")";
	$playerNumberHTML = generateHTMLTag("span","","",$playerNumberContent);
	$playerName = returnPlayerName($spieler);
	$defitionTitlePlayerData = generateHTMLTag("dt","","",$playerName.$playerNumberHTML);
	
	
	$defitionListData = $defitionTitlePlayerData.$definitionBodyPlayerPosition.$definitionBodyPlayer;
	$definitionListPlayerData = generateHTMLTag("dl","spieler","",$defitionListData);
	
	if(playerHasImage($playerId)) {
		$playerImageSrc = returnPlayerImageSrc($playerId);
	}else {
		$playerImageSrc = WP_CONTENT_URL.'/players/keinbild.jpg';
	}
	
	$playerImage = generateHTMLTag("img","","","",array("src"=>$playerImageSrc,"width"=>"250"));
	$playerCard = generateHTMLTag("li","","",$playerImage.$definitionListPlayerData);	
	
	return $playerCard;
	
}

$teamId = get_post_meta($post_id, '_spielerliste', true);


$players = "";

$team = returnPlayersWithTeamId($teamId);
#$team = returnRandomOrderedPlayersArray($team);

foreach($team as $spieler)
{
			
	$players .= generatePlayerCard($spieler);
	
}

$spielerliste = generateHTMLTag("ul","spielerliste","",$players);



