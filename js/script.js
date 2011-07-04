jQuery(document).ready(function(){
	if(jQuery('#file').length > 0)
	{
		jQuery('#file').change(function(){
			jQuery('#spieler_img_form').ajaxSubmit({target : "#player_image"});
		});
	}
	if(jQuery('#game_nav').length > 0)
	{
		setId = jQuery('#fotos_content').attr('title');
		jQuery.post(
		    // see tip #1 for how we declare global javascript variables
		    Flickr.ajaxurl,
		    {
		        // here we declare the parameters to send along with the request
		        // this means the following action hooks will be fired:
		        // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
		        action : 'myajax-submit',
		 
		        // other parameters can be added along with "action"
		        setId : ''+setId
		    },
		    function( response ) {
		        jQuery('#fotos_content').html( response );
		        jQuery('#loading').hide();
		        jQuery('#fotos_content a[rel=lightbox]').lightBox();
		    }
		);
		jQuery('a.bericht').click(function(){
			
			jQuery('#gamewrap').append(jQuery('#bericht_content').fadeIn());
			jQuery('#fotos_content').not(':hidden').fadeOut();
			jQuery('#pre_wrap').not(':hidden').fadeOut();
			jQuery('#statistik_content').not(':hidden').fadeOut();
		});
		jQuery('a.wrap').click(function(){
			
			jQuery('#gamewrap').append(jQuery('#pre_wrap').fadeIn());
			jQuery('#bericht_content').not(':hidden').fadeOut();
			jQuery('#fotos_content').not(':hidden').fadeOut();
			jQuery('#statistik_content').not(':hidden').fadeOut();
		});
		jQuery('a.fotos').click(function(){
			
			jQuery('#fotos_content').fadeIn();
			//jQuery('#pre_wrap').fadeOut();
			jQuery('#bericht_content').not(':hidden').fadeOut();
			jQuery('#pre_wrap').not(':hidden').fadeOut();
			jQuery('#statistik_content').not(':hidden').fadeOut();
			
//			jQuery('#fotos_content').load(wp_plugin_url+'/team/php/flickr_ajax.php?setId='+set);
			
			
			/*jQuery.ajax({
				data:"setId="+set,
				url: wp_plugin_url+"/team/php/flickr_ajax.php",
				success: function(data){
				    $('#fotos_content').html(data);
				  }
			});*/
			
		});
		jQuery('a.stats').click(function(){
			
			jQuery('#statistik_content').fadeIn();
			jQuery('#fotos_content').not(':hidden').fadeOut();
			jQuery('#bericht_content').not(':hidden').fadeOut();
			jQuery('#pre_wrap').not(':hidden').fadeOut();
		});
	}
});