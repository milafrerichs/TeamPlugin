<li class="card">
<h2><?php 
	if(isset($link) && $link != "")
	{
		echo '<a href="/?p='.$link.'">'.$title.'</a>';
	}else {
		echo $title;	
	}
?></h2>
<ul class="ergebnisse">
	<?php
		$liga_first = true;
		foreach($spiele as $spiel)
		{
			$spiel_termine[$spiel->liga_id][] = $spiel;
		}
		$prev_liga = 0;
		foreach($spiel_termine as $liga_id=>$spiele_liga) {
		
		foreach($spiele_liga as $spiel)
		{
			$liga_id = $spiel->liga_id;
			
			$team = $wpdb->get_row("SELECT team.name FROM ".$wpdb->prefix."ligen as ligen LEFT JOIN ".$wpdb->prefix."teams as team ON(ligen.team_id = team.id) WHERE ligen.id = ".$liga_id."");
			
			$spielbericht_id = $wpdb->get_var("SELECT post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_spielbericht' AND meta_value = ".$spiel->id.' AND post_id != 0') ;
			
			
			if($prev_liga != $liga_id) {
				echo '<h4>'.$team->name.'</h4>';
			}
			list($jahr,$monat,$tag) = explode('-',$spiel->datum);			
			
			if($spiel->ort == "Münster")
			{
				echo '	<li>'.(($spielbericht_id)?'<a href="?p='.$spielbericht_id.'">':'').'<span>'.$tag.'.'.$monat.'; </a><span>'.$spiel->score_heim.":".$spiel->score_gast.'</span></span><br/>';
				echo ' vs. ';
				echo $spiel->gegner.'<br/>';
				
			}else 
			{
				echo '	<li><span>'.$tag.'.'.$monat.'; <span>'.$spiel->score_gast.":".$spiel->score_heim.'</span></span><br/>';
				echo ' @ ';
				echo $spiel->gegner.'<br/>';

			}
			
			echo '</li>';
			
			$prev_liga = $liga_id;
		}
		}
	
	?>
</ul>
</li>