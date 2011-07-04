<?

if(isset($_POST['ajax']) && $_POST['ajax'] == 'true')
{
	switch($_POST['option'])
	{
		case 'fileUpload' : fileUpload();break;
		
		default : break;
	}

}


function fileUpload()
{
	global $_POST,$wpdb,$_FILES;
	
	if(!empty($_FILES['img']['tmp_name']))
	{
		if(!empty($_FILES['img']['name']))
		{
			preg_match("/(\.(?:jpg|jpeg|png|gif))$/i", $_FILES['img']['name'], $matches);
		}else {
			return "Fehler beim Upload";
		}
		if(count($matches) > 0) {
			if(isset($_POST['id']) && $_POST['id'] != "") {
				$fileName = "player_".$_POST['id'].strtolower($matches[0]);
			}else {
				$fileName = "player_tmp".strtolower($matches[0]);
			}
			
			$loc = $_POST['playerdir'] . $fileName;
		
			$uploaded = false;
		}else {
			echo "Falsches Format";
		}
		
		if ( !empty($_FILES['img']['tmp_name']) && move_uploaded_file($_FILES['img']['tmp_name'], $loc) )
			$uploaded = true;
			
			chmod($loc, 0755);
			
		if($uploaded)
		{
			$file = $_POST['content_url']."/players/".$fileName;
			echo '<img src="'.$file.'" alt="" />';
			/*
			$new_width = 250;
			$new_height = 200;

			echo $image = open_image($_FILES['img']['tmp_name']);
			$width = imagesx($image);
			$height = imagesy($image);
			$canvas = imagecreatetruecolor( $new_width, $new_height );
			imagealphablending($canvas, false);
			// Create a new transparent color for image
			$color = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
			// Completely fill the background of the new image with allocated color.
			imagefill($canvas, 0, 0, $color);
			// Restore transparency blending
			imagesavealpha($canvas, true);
			
			imagecopyresampled( $canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			
			
			imagejpeg($canvas);
			*/
		}	
	}
}
?>