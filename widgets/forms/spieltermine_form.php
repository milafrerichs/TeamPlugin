<?
	$teams =  $wpdb->get_results( "SELECT id, name,saison FROM ".$wpdb->prefix."teams" );
	if(empty($instance))
	{
		$title = "";
		$limit = 3;
		$saison = "";
		$team_id = 0;
	}else {
		if(!$instance['title']) {
			$title = "";
		}else {
			$title = esc_attr($instance["title"]);
		}
		if(!$instance['limit']) {
			$limit = "";
		}else {
			$limit = esc_attr($instance["limit"]);
		}
		if($instance['saison']) {
			$saison = esc_attr($instance["saison"]);
		}else {
			$saison = "";
		}
		if($instance['team']) {
			$team_id = esc_attr($instance["team"]);
		}else {
			$team_id = 0;
		}
		if($instance['link']) {
			$link_id = esc_attr($instance["link"]);
		}else {
			$link_id = 0;
		}
	}
?>
<p>
	<label for="<?php echo $this->get_field_id("title"); ?>">
		Titel:
		<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id("limit"); ?>">
		Anzahl:
		<input class="widefat" id="<?php echo $this->get_field_id("limit"); ?>" name="<?php echo $this->get_field_name("limit"); ?>" type="text" value="<?php echo $limit; ?>" />
	</label>
</p>


<p><label for="<?php echo $this->get_field_id("team"); ?>">
	Team: 	
	<select id="<?php echo $this->get_field_id("team"); ?>" name="<?php echo $this->get_field_name("team"); ?>">
			<option value="0">Alle</option>	
				<? foreach($teams as $team)
				{
					echo '<option value="'.$team->id.'" ';
						echo ($team->id == $team_id)?'selected="selected"':'';
					echo '>'.$team->name.'('.$team->saison.')</option>';
				}
				?>
	</select>
</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id("link"); ?>"></label>
	Link
	
		<?
			$args = array(
    			'depth'            => 0,
    			'child_of'         => 0,
    			'selected'         => $link_id,
    			'echo'             => 1,
    			'name'             => $this->get_field_name("link"),
    			'show_option_none' => 'Kein Link',
    			'exclude'          => 0,
    			'exclude_tree'     => '' );
			wp_dropdown_pages( $args );
		?>
	
</p>

<p <?php echo ($team_id != 0)?'class="hidden"':"";?>>
	<label for="<?php echo $this->get_field_id("saison"); ?>">
		Saison:
		<input class="widefat" id="<?php echo $this->get_field_id("saison"); ?>" name="<?php echo $this->get_field_name("saison"); ?>" type="text" value="<?php echo $saison; ?>" />
	</label>
</p>

