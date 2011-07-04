<?php

echo 'test';

$setId = $_POST['setId'];

$setdata_url = "http://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=9801af141e16d759d62a9a6b2c4c361e&photoset_id=72157626960079992&format=php_serial";
if($resource_setdata = fopen($setdata_url,"r"))
{
	##$content_set_data = stream_get_contents($resource_setdata);
	
	$sets = unserialize($content_set_data);
	
	$set = $sets['photoset'];
	
	foreach($photo in $set)
	{
		$foto_id = $photo['id'];
		$foto_url = "http://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key=3a67c3aef4333ea7ea1ce31a82f42c85&photo_id=".$foto_id."&format=php_serial";
		
		if($res = fopen($foto_url,'r'))
		{
			##$foto_content = stream_get_contents($res);
			$foto_c = unserialize($foto_content);
			$img = $foto_c['sizes']['size'][0];
			$img_url = $img['source'];
		}
		
		$titel = $set["title"]["_content"];
		$gallery .=  '<dl><dd><a href="'.$user_url.'sets/'.$id.'/"><img src="'.$img_url.'" rel="lightbox"/></a></dd><dt><a href="'.$user_url.'sets/'.$id.'/" align="center">'.$titel.'</a></dt></dl>';
		
	
	}
}
echo $gallery;
?>
