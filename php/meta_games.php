<?
$meta_game_id = get_post_meta($post->ID, '_spielbericht', true);
$meta_flickr_set = get_post_meta($post->ID, '_flickr', true);

?>
<p>Soll eine Verknüpfung mit einem Spiel erstellt werden?</p>
<input type="checkbox" name="spielbericht" value="Y" id="spielbericht" <?php echo ($meta_game_id != "")?'checked="checked"':'';?>/><label for="spielbericht">Ja</label><br/>
<select name="spiel">
<?
$games = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."spiele as spiele order by spiele.datum DESC");

foreach($games as $game)
{
	if($game->id == $meta_game_id) {
		$selected = 'selected="selected"';
	}
	else {
		$selected = '';
	}
	echo '<option value="'.$game->id.'" '.$selected.'>'.$game->datum.'('.$game->gegner.')';
}
?>
</select>
<p>Sollen Bilder eines Flickr-Albums angezeigt werden?</p>
<label for="flickr">Set-ID:</label>
<input type="text" name="flickr" id="flickr" value="<? echo $meta_flickr_set;?>" />