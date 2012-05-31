<?php   
/* 
Plugin Name: Team Plugin 
Plugin URI: http://www.milafrerichs.de
Description: Saves me time. 
Version: 1.0 
Author: Mila Frerichs 
Author URI: http://www.milafrerichs.de
*/  

define("PLAYER_DIR", WP_CONTENT_DIR . '/players/'); 
define("TEAMS_DIR", WP_CONTENT_DIR . '/teams/'); 

define("TEAMIMAGES",WP_PLUGIN_URL.'/team/images/');

wp_register_script('teamScript', WP_PLUGIN_URL . '/team/js/script.js',array('jquery'));

// embed the javascript file that makes the AJAX request
	wp_enqueue_script( 'flickr-ajax-request', plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ) );
	 
	// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
	wp_localize_script( 'flickr-ajax-request', 'Flickr', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
	

if (!class_exists("TeamPlugin")) {
    class TeamPlugin {
        var $adminOptionsName = "DevloungePluginSeriesAdminOptions";
        function TeamPlugin() { //constructor
           
        }
   		function  init() {
			global $wpdb;
			
			$table_name = $wpdb->prefix . "teams";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
			{
				$sql = "CREATE TABLE " . $table_name . " (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  name varchar(255) NOT NULL,
					  description text NOT NULL,
					  saison int(11) NOT NULL,
					  UNIQUE KEY id (id)
					);";
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			$table_name_2 = $wpdb->prefix . "team_members";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name_2'") != $table_name_2) 
			{
				$sql = "CREATE TABLE " . $table_name_2 . " (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  team_id mediumint(9) NOT NULL,
					  name varchar(255) NOT NULL,
					  vorname varchar(255) NOT NULL,
					  gebdatum date NOT NULL,
					  nummer mediumint(9) NOT NULL,
					  positionen varchar(255) NOT NULL,
					  spielt_seit varchar(5) NOT NULL,
					  wirf enum('L','R') NOT NULL DEFAULT  'R',
					  schlaegt enum('L','R') NOT NULL DEFAULT  'R',
					  vereine text NOT NULL,
					  UNIQUE KEY id (id)
					);";
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			$table_name = $wpdb->prefix . "ligen";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
			{
				$sql = "CREATE TABLE " . $table_name . " (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  team_id int(11) NOT NULL,
					  name varchar(255) NOT NULL,
					  description text NOT NULL,
					  saison int(11) NOT NULL,
					  innings int(11) NOT NULL DEFAULT 9,
					  UNIQUE KEY id (id)
					);";
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			$table_name = $wpdb->prefix . "spiele";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
			{
				$sql = "CREATE TABLE " . $table_name . " (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  liga_id int(11) NOT NULL,
					  ort varchar(255) NOT NULL,
					  gegner text NOT NULL,
					  saison int(11) NOT NULL,
					  datum date NOT NULL,
					  uhrzeit varchar(5) NOT NULL,
					  score_heim int(3) NOT NULL,
					  score_gast int(3) NOT NULL,
					  UNIQUE KEY id (id)
					);";
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			$table_stats = $wpdb->prefix . "statistiken";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_stats'") != $table_stats) 
			{
				$sql = "CREATE TABLE " . $table_stats . " (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  player_id mediumint(9) NOT NULL,
					  saison int(11) NOT NULL,
					  game_id int(11) NOT NULL,
					  gespielt enum(Y,N) NOT NULL,
					  ab int(11) NOT NULL,
					  pa int(11) NOT NULL,
					  hits int(11) NOT NULL,
					  doubles int(11) NOT NULL,
					  triples int(11) NOT NULL,
					  homeruns int(11) NOT NULL,
					  th int(11) NOT NULL,
					  walks int(11) NOT NULL,
					  hbp int(11) NOT NULL,
					  sac int(11) NOT NULL,
					  so int(11) NOT NULL,
					  sb int(11) NOT NULL,
					  cs int(11) NOT NULL,
					  runs int(11) NOT NULL,
					  avg float(10) NOT NULL,
					  slg float(11) NOT NULL,
					  obs float(11) NOT NULL,
					  errors int(11) NOT NULL,					 
					  UNIQUE KEY id (id)
					);";
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			
   		}
   		function admin_init()
   		{
   			wp_register_script('teamScript', WP_PLUGIN_URL . '/team/js/script.js',array('jquery'));
   		
   		}
   		function main()
   		{
   			global $wpdb;
   			include ('php/team_list.php');
   		}
   		function main_spieler()
   		{
   			global $wpdb;
   			include ('php/spieler_list.php');
   		}
   		function main_stats()
   		{
   			global $wpdb;
   			include ('php/stats_list.php');
   		}
   		function main_liga()
   		{
   			global $wpdb;
   			include ('php/liga_list.php');
   		}
   		function admin_styles()
   		{
   			 ##wp_enqueue_script('teamScript', WP_PLUGIN_URL . '/team/js/script.js');
   			 wp_enqueue_script('jquery');
   			 wp_enqueue_script('jquery-form');
   		}
   		function new_team()
   		{
   			global $wpdb;
   			include ('php/new_team.php');
   		}
   		function new_spieler()
   		{
   			global $wpdb;
   			
   			include ('php/new_spieler.php');
   		}
   		function new_game()
   		{
   			global $wpdb;
   			include ('php/new_game.php');
   		}
   		function new_liga()
   		{
   			global $wpdb;
   			include ('php/new_liga.php');
   		}
   		function new_stats()
   		{
   			global $wpdb;
   			include ('php/new_stats.php');
   		}
   		function new_result()
   		{
   			global $wpdb;
   			include ('php/new_result.php');
   		}
   		function mainMenu()
   		{
   			add_menu_page("Teams Übersicht", "Teams", 'administrator', 'teams',array($this,'main'),'',2);
   			add_submenu_page('teams', 'Neues Team erstellen', 'Neues Team', 'administrator', 'new-team', array($this,'new_team'));
   			
   			add_menu_page("Spieler Übersicht", "Spieler", 'publish_posts', 'spieler',array($this,'main_spieler'),'',3);
   			add_submenu_page('spieler', 'Neuen Spieler erstellen', 'Neuer Spieler', 'publish_posts', 'new-player', array($this,'new_spieler'));
   			
   			add_menu_page("Spielplan", "Spielplan", 'publish_posts', 'games',array($this,'main_liga'),'',4);
   			add_submenu_page('games', 'Neue Liga erstellen', 'Neue Liga', 'administrator', 'new-league', array($this,'new_liga'));
   			add_submenu_page('games', 'Neues Spiel erstellen', 'Neues Spiel', 'publish_posts', 'new-game', array($this,'new_game'));
   			
   			add_submenu_page("games","Spieler Statistiken", "Statistiken", 'publish_posts', 'stats',array($this,'main_stats'));
   			add_submenu_page("games","Neue Spielstatistik", "Neue Spielstatistik", 'publish_posts', 'new-stats',array($this,'new_stats'));
   			add_submenu_page("games","Neues Ergebnis", "Neues Ergebnis", 'publish_posts', 'new-result',array($this,'new_result'));
   			
   			
   			##add_action('admin_print_scripts-' . $page, 'admin_styles');
   			if( function_exists("add_meta_box") ) {
   				add_meta_box("meta-box-players", "Spielerseiten", 'meta_box_players',"page", "normal","high");
   				add_meta_box("meta-box-games", "Spielverknüpfungen", 'meta_box_games',"post", "normal","high");
   			}
   				
   			add_action('save_post','save_page_meta');
   		}
   		function enque_styles()
   		{
   			wp_enqueue_style('team-styles',WP_PLUGIN_URL.'/team/css/team.css');
   		}
   		
   		
    }

} //End Class TeamPlugin

if( !is_dir( PLAYER_DIR ) )
{
	// Attempt to create thumbnail directory if non-existent
	if( !@mkdir( PLAYER_DIR ) )
	{
		function p75_WarningThumbnailFolder()
		{
			echo '<div class="updated fade"><p>Simple Post Thumbnails could not create the thumbnail folder. Please create a folder named &quot;thumbnails&quot; in the &quot;wp-content&quot; folder of your WordPress installation. Go to the <a href="http://www.press75.com/documentation-support/simple-post-thumbnails-setup-usage/">plugin support page</a> for setup information.</p></div>';
		}
		add_action('admin_notices', 'p75_WarningThumbnailFolder');
	}
}

// Check for thumbnail directory
if( !is_writable( PLAYER_DIR ) )
{
	function p75_WarningThumbnailFolderNotWritable()
	{
		echo '<div class="updated fade"><p>Simple Post Thumbnails cannot write to the thumbnail folder. This is probably due to permissions. Please make sure the <code>thumbnails</code> folder inside your <code>wp-content</code> folder is is writable. Go to the <a href="http://www.press75.com/documentation-support/simple-post-thumbnails-setup-usage/">plugin support page</a> for setup information.</p></div>';
	}
	add_action('admin_notices', 'p75_WarningThumbnailFolderNotWritable');
}

if (class_exists("TeamPlugin")) 
{
	$team_plugin = new TeamPlugin();
}

function meta_box_players()
{
	global $wpdb,$meta_box, $post;
	include ('php/meta_players.php');
}
function meta_box_games()
{
	global $wpdb,$meta_box, $post;
	include ('php/meta_games.php');
}
function save_page_meta($post_id)
{
	global $meta_box,$wpdb;
	$post_id = wp_is_post_revision($post_id);
	if(isset($_POST['spielerliste']) && $_POST['spielerliste'] == 'Y')
	{
		if(!update_post_meta($post_id, '_spielerliste', $_POST['team'])) {
			add_post_meta($post_id, '_spielerliste', $_POST['team']);
		}
	}
	if(isset($_POST['ergebnisliste']) && $_POST['ergebnisliste'] == 'Y')
	{
		if(!update_post_meta($post_id, '_ergebnisliste', $_POST['team_ergebnis'])) {
			add_post_meta($post_id, '_ergebnisliste', $_POST['team_ergebnis']);
		}
	}
	if(isset($_POST['spielbericht']) && $_POST['spielbericht'] == 'Y')
	{
		if(!update_post_meta($post_id, '_spielbericht', $_POST['spiel'])) {
			add_post_meta($post_id, '_spielbericht', $_POST['spiel']);
		}
	}
	if(isset($_POST['flickr']) && $_POST['flickr'] != '')
	{
		if(!update_post_meta($post_id, '_flickr', $_POST['flickr'])) {
			add_post_meta($post_id, '_flickr', $_POST['flickr']);
		}
	}
	if(isset($_POST['statistik']) && $_POST['statistik'] == 'Y')
	{
		if(!update_post_meta($post_id, '_statistik', $_POST['team_stats'])) {
			add_post_meta($post_id, '_statistik', $_POST['team_stats']);
		}
	}
}
function show_meta_data($content)
{
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

function has_spielerliste($postID)
{
	return (bool) get_post_meta($postID, '_spielerliste', true);
}
function has_ergebnisliste($postID)
{
	return (bool) get_post_meta($postID, '_ergebnisliste', true);
}
function has_stats($postID)
{
	return (bool) get_post_meta($postID, '_statistik', true);
}
function has_spielbericht($postID)
{
	return (bool) get_post_meta($postID, '_spielbericht', true);
}


function show_spielerliste($content,$post_id)
{
	global $wpdb;
	include ('php/spielerliste.php');
	
	return $content.$spielerliste;
}

function show_ergebnisliste($content,$post_id)
{
	global $wpdb;
	include ('php/ergebnisliste.php');
	
	return $content.$ergebnisliste;
}

function show_stats($content,$post_id)
{
	global $wpdb;
	include ('php/statistik.php');
	
	return $content.$statistik;
}
function show_game_wrap($content,$post_id)
{	
	global $wpdb;
	include ('php/gamewrap.php');
}

function getShortBericht($text, $limit)
   {
      $pattern = '/(<img.+?>)/';
      $text = preg_replace($pattern,"",$text);
      
      $array = explode(" ", $text, $limit+1);
       if (count($array) > $limit)
      {
         unset($array[$limit]);
      }
      return implode(" ", $array);
   }


//Actions and Filters	
if (isset($team_plugin)) {
	//Actions
	add_action('admin_init', array(&$team_plugin,'admin_init'));
	add_action('admin_menu', array(&$team_plugin,'mainMenu'));
	add_action( 'wp_print_styles', array(&$team_plugin,'enque_styles'));
	add_filter( "the_content", "show_meta_data" );
	add_action( 'widgets_init', create_function('', 'return register_widget("Spieltermine");') );
		add_action( 'widgets_init', create_function('', 'return register_widget("Ergebnisse");') );
	
	register_activation_hook(__FILE__,array(&$team_plugin, 'init'));
	//Filters
}

/* Widget */

class Spieltermine extends WP_Widget {
	function Spieltermine() {
		//Konstruktor
		parent::WP_Widget(false, $name='Spieltermine');
		
	}
 
	function widget($args, $instance) {
		// Ausgabefunktion
		global $post,$wpdb;
		$post_old = $post; // Save the post object.
		
		extract( $args );
		$all = false;
		$date = date('Y-m-d');
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
			$title = "Spieltermine";
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
			$spiele = $wpdb->get_results("SELECT spiele.id,spiele.datum, spiele.gegner,spiele.ort,spiele.uhrzeit,spiele.liga_id FROM ".$wpdb->prefix."spiele as spiele WHERE spiele.saison = '".$instance['saison']."' AND spiele.datum > '".$date."' ORDER BY spiele.datum, spiele.liga_id LIMIT ".$limit."");	
		}
		else
		{
			$liga_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."ligen WHERE team_id = %s", $instance['team']));
			$spiele = $wpdb->get_results("SELECT spiele.id,spiele.datum, spiele.gegner,spiele.ort,spiele.uhrzeit,spiele.liga_id FROM ".$wpdb->prefix."spiele as spiele WHERE spiele.liga_id = '".$liga_id."' AND spiele.datum > '".$date."' ORDER BY spiele.datum LIMIT ".$limit."");
		}
		include ('php/spieltermine_widget.php');
		
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
		include ('php/spieltermine_form.php');
	}
}

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
			$spiele = $wpdb->get_results("SELECT spiele.id,spiele.datum, spiele.gegner,spiele.ort,spiele.score_heim,spiele.score_gast,spiele.liga_id FROM ".$wpdb->prefix."spiele as spiele WHERE spiele.saison = '".$instance['saison']."' AND spiele.datum <= '".$date."' AND (spiele.score_heim != 0 AND spiele.score_gast != 0) ORDER BY spiele.datum DESC,spiele.liga_id LIMIT ".$limit."");	
		}
		else
		{
			$liga_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."ligen WHERE team_id = %s", $instance['team']));
			$spiele = $wpdb->get_results("SELECT spiele.id,spiele.datum, spiele.gegner,spiele.ort,spiele.score_heim,spiele.score_gast,spiele.liga_id FROM ".$wpdb->prefix."spiele as spiele WHERE spiele.liga_id = '".$liga_id."' AND spiele.datum <= '".$date."' AND (spiele.score_heim != 0 AND spiele.score_gast != 0) ORDER BY spiele.datum DESC LIMIT ".$limit."");
		}
		include ('php/ergebnisse_widget.php');
		
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
		include ('php/ergebnisse_form.php');
	}
}

