<?
class Ergebnisse extends WP_Widget {
	function Ergebnisse() {
		//Konstruktor
		parent::WP_Widget(false, $name='Ergebnisse');
		
	}
 
	function widget($args, $instance) {
		// Ausgabefunktion
		global $post,$wpdb;
		$post_old = $post; // Save the post object.
		extract( $args );
		$all = false;
		$date = date('Y-m-d');//,mktime(date("H"),date("i"),date("s"),date("n"),date("j")-7,date("Y")));
		if(!$instance["team"] || $instance['team'] == 0)
		{
			$all = true;
		}
		if(!$instance['limit'])
		{
			$limit = 3;
		}else {
			$limit = $instance['limit'];
		}
		if(!$instance['title'])
		{
			$title = "Ergebnisse";
		}else {
			$title = $instance['title'];
		}
		if(!$instance['link'])
		{
			
		}else {
			$link = $instance['link'];
		}
		if($all)
		{
			$spiele = $wpdb->get_results("SELECT spiele.id,spiele.datum, spiele.gegner,spiele.ort,spiele.score_heim,spiele.score_gast,spiele.liga_id FROM ".$wpdb->prefix."spiele as spiele WHERE spiele.saison = '".$instance['saison']."' AND spiele.datum <= '".$date."' AND (spiele.score_heim XOR spiele.score_gast) ORDER BY spiele.datum DESC,spiele.liga_id LIMIT ".$limit."");	
		}
		else
		{
			$liga_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."ligen WHERE team_id = %s", $instance['team']));
			$spiele = $wpdb->get_results("SELECT spiele.id,spiele.datum, spiele.gegner,spiele.ort,spiele.score_heim,spiele.score_gast,spiele.liga_id FROM ".$wpdb->prefix."spiele as spiele WHERE spiele.liga_id = '".$liga_id."' AND spiele.datum <= '".$date."' AND (spiele.score_heim XOR spiele.score_gast) ORDER BY spiele.datum DESC LIMIT ".$limit."");
		}
		include ('templates/ergebnisse_widget.php');
		
		$post = $post_old; // Restore the post object.
	}
 
	function update($new_instance, $old_instance) {
		//Speichern des Widgets
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']); 
		$instance['limit'] = strip_tags($new_instance['limit']); 
		$instance['team'] = strip_tags($new_instance['team']);
		$instance['saison'] = strip_tags($new_instance['saison']);
		$instance['link'] = strip_tags($new_instance['link']);
		return $instance;
	}
 
	function form($instance) {
		//Widgetform im Backend
		global $wpdb;
		include ('forms/ergebnisse_form.php');
	}
}
