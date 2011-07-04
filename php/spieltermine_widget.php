<li class="card">
<h2><?php 
	if(isset($link) && $link != "")
	{
		echo '<a href="/?p='.$link.'">'.$title.'</a>';
	}else {
		echo $title;	
	}
	?></h2>
<ul class="spieltermine">
	<?php
	if(count($spiele) >= 1)
	{
		foreach($spiele as $spiel)
		{
			
			$spiel_termine[$spiel->liga_id][] = $spiel;
		}
		$prev_liga_id = 0;
		foreach($spiel_termine as $liga_id=>$spiele_liga) {
			foreach($spiele_liga as $spiel) {
			
			$team = $wpdb->get_row("SELECT team.name FROM ".$wpdb->prefix."ligen as ligen LEFT JOIN ".$wpdb->prefix."teams as team ON(ligen.team_id = team.id) WHERE ligen.id = ".$liga_id."");
			if($prev_liga_id != $liga_id) {
				echo '<h4>'.$team->name.'</h4>';
			}
			list($jahr,$monat,$tag) = explode('-',$spiel->datum);			
			echo '	<li><span>'.$tag.'.'.$monat.'.'.$jahr.', '.$spiel->uhrzeit.' Uhr</span><br/><p class="gegner">';
			if($spiel->ort == "Münster")
			{
				echo ' vs. ';
			}else 
			{
				echo ' @ ';
			}
			echo $spiel->gegner;
			echo '</p></li>';
			$prev_liga_id = $liga_id;		
			
		}
	}
	}else
	{
		echo '<h4>Keine Spiele mehr in dieser Saison</h4>';
	}
	?>
</ul>
</li>