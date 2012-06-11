<?php   
/* 
Plugin Name: Team Plugin 
Plugin URI: http://www.milafrerichs.de
Description: Saves me time. 
Version: 1.1 
Author: Mila Frerichs 
Author URI: http://www.milafrerichs.de
*/  

define("PLAYER_DIR", WP_CONTENT_DIR . '/players/'); 
define("TEAMS_DIR", WP_CONTENT_DIR . '/teams/'); 

define("TEAMIMAGES",WP_PLUGIN_URL.'/team/images/');

define( 'TEAMPLUGIN_PATH', plugin_dir_path(__FILE__) );

add_action('wp_enqueue_script','team_scripts');

function team_scripts(){
	wp_register_script('teamScript', WP_PLUGIN_URL . '/team/js/script.js',array('jquery'));
	wp_enqueue_script( 'flickr-ajax-request', plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ) );
	 
	// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
	wp_localize_script( 'flickr-ajax-request', 'Flickr', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
}



// embed the javascript file that makes the AJAX request
	
	

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

include_once ('functions.php');

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

include_once ('widgets/Spieltermine.php');
include_once ('widgets/Ergebnisse.php');
