<?
function meta_box_players() {
	global $wpdb,$meta_box, $post;
	include ('php/meta_players.php');
}
function meta_box_games() {
	global $wpdb,$meta_box, $post;
	include ('php/meta_games.php');
}
function save_page_meta($post_id) {
	global $meta_box,$wpdb;
	$post_id = wp_is_post_revision($post_id);
	if(isset($_POST['spielerliste']) && $_POST['spielerliste'] == 'Y') {
		if(!update_post_meta($post_id, '_spielerliste', $_POST['team'])) {
			add_post_meta($post_id, '_spielerliste', $_POST['team']);
		}
	}
	if(isset($_POST['ergebnisliste']) && $_POST['ergebnisliste'] == 'Y') {
		if(!update_post_meta($post_id, '_ergebnisliste', $_POST['team_ergebnis'])) {
			add_post_meta($post_id, '_ergebnisliste', $_POST['team_ergebnis']);
		}
	}
	if(isset($_POST['spielbericht']) && $_POST['spielbericht'] == 'Y') 	{
		if(!update_post_meta($post_id, '_spielbericht', $_POST['spiel'])) {
			add_post_meta($post_id, '_spielbericht', $_POST['spiel']);
		}
	}
	if(isset($_POST['flickr']) && $_POST['flickr'] != '') {
		if(!update_post_meta($post_id, '_flickr', $_POST['flickr'])) {
			add_post_meta($post_id, '_flickr', $_POST['flickr']);
		}
	}
	if(isset($_POST['statistik']) && $_POST['statistik'] == 'Y') {
		if(!update_post_meta($post_id, '_statistik', $_POST['team_stats'])) {
			add_post_meta($post_id, '_statistik', $_POST['team_stats']);
		}
	}
}
function show_meta_data($content) {
	global $post;
	if ( has_spielerliste($post->ID) )
		return show_spielerliste($content,$post->ID);
	elseif(has_ergebnisliste($post->ID))
		return show_ergebnisliste($content,$post->ID);
	elseif(has_stats($post->ID))
		return show_stats($content,$post->ID);
	elseif(has_spielbericht($post->ID))
		return show_game_wrap($content,$post->ID);
	else
		return $content;
}

function has_spielerliste($postID) {
	return (bool) get_post_meta($postID, '_spielerliste', true);
}
function has_ergebnisliste($postID) {
	return (bool) get_post_meta($postID, '_ergebnisliste', true);
}
function has_stats($postID) {
	return (bool) get_post_meta($postID, '_statistik', true);
}
function has_spielbericht($postID) {
	return (bool) get_post_meta($postID, '_spielbericht', true);
}


function show_spielerliste($content,$post_id) {
	global $wpdb;
	include ('php/spielerliste.php');
	echo $spielerliste; //toDo: nachschauen im aktuellen Plugin!!!!!
	return $content.$spielerliste;
}

function show_ergebnisliste($content,$post_id) {
	global $wpdb;
	include ('php/ergebnisliste.php');
	
	return $content.$ergebnisliste;
}

function show_stats($content,$post_id) {
	global $wpdb;
	include ('php/statistik.php');
	
	return $content.$statistik;
}
function show_game_wrap($content,$post_id) {	
	global $wpdb;
	include ('php/gamewrap.php');
}

function getShortBericht($text, $limit)    {
      $pattern = '/(<img.+?>)/';
      $text = preg_replace($pattern,"",$text);
      
      $array = explode(" ", $text, $limit+1);
       if (count($array) > $limit) {
         unset($array[$limit]);
      }
      return implode(" ", $array);
   }
   
   
   function generateHTMLTag($tagName, $class = "", $id = "",$content, $addParams= array()) {
   		$html = "<".$tagName;
   		if($class != "") {
   			$html .= ' class="'.$class.'"';
   		}
   		if($id != "") {
   			$html .= ' id="'.$id.'"';
   		}
   		if(!empty($addParams)) {
   			foreach($addParams as $attr=>$value) {
   				$html .= ' '.$attr.'="'.$value.'" ';
   			}
   		}
   		$html .= ">".$content."</".$tagName.">";
   		
   		return $html;
   }
