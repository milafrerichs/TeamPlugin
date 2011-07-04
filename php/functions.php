<?
//Actions and Filters   
if (isset($team_plugin)) {
    //Actions
    add_action('wp_head', array(&$team_plugin, 'addHeaderCode'), 1);
    //Filters
}

