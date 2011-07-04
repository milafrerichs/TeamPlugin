<?
$meta_team_id = get_post_meta($post->ID, '_spielerliste', true);
$meta_team_ergebnis_id = get_post_meta($post->ID, '_ergebnisliste', true);
$meta_team_stats_id = get_post_meta($post->ID, '_statistik', true);

?>
<p>Soll eine Spielerliste eingefügt werden?</p>
<input type="checkbox" name="spielerliste" value="Y" id="spielerliste" <?php echo ($meta_team_id != "")?'checked="checked"':'';?>/><label for="spielerliste">Ja</label><br/>
<select name="team">
<?
$teams = $wpdb->get_results("SELECT team.id,team.name,team.saison FROM ".$wpdb->prefix."teams as team");

foreach($teams as $team)
{
	if($team->id == $meta_team_id) {
		$selected = 'selected="selected"';
	}
	else {
		$selected = '';
	}
	echo '<option value="'.$team->id.'" '.$selected.'>'.$team->name.'('.$team->saison.')';
}
?>
</select>

<p>Soll eine Ergebnisliste eingefügt werden?</p>
<input type="checkbox" name="ergebnisliste" value="Y" id="ergebnisliste" <?php echo ($meta_team_ergebnis_id != "")?'checked="checked"':'';?>/><label for="ergebnisliste">Ja</label><br/>
<select name="team_ergebnis">
<?
foreach($teams as $team)
{
	if($team->id == $meta_team_ergebnis_id) {
		$selected = 'selected="selected"';
	}
	else {
		$selected = '';
	}
	echo '<option value="'.$team->id.'" '.$selected.'>'.$team->name.'('.$team->saison.')';
}
?>
</select>

<p>Soll die Statistik eingefügt werden?</p>
<input type="checkbox" name="statistik" value="Y" id="statistik" <?php echo ($meta_team_stats_id != "")?'checked="checked"':'';?>/><label for="statistik">Ja</label><br/>
<select name="team_stats">
<?
foreach($teams as $team)
{
	if($team->id == $meta_team_stats_id) {
		$selected = 'selected="selected"';
	}
	else {
		$selected = '';
	}
	echo '<option value="'.$team->id.'" '.$selected.'>'.$team->name.'('.$team->saison.')';
}
?>
</select>

