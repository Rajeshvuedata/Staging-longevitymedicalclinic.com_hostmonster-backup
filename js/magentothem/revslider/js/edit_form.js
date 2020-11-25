jQuery(document).ready(function() {
	var js_url = jQuery('#js_url').val();
	jQuery('#revslider_form_size').hide();
	jQuery('#revslider_form_general').hide();
	jQuery('#revslider_form_position').hide();
	jQuery('#revslider_form_appearance').hide();
	jQuery('#revslider_form_navigation').hide();
	jQuery('#revslider_form_Thumbnail').hide();
	jQuery('#revslider_form_firstslide').hide();
	jQuery('#revslider_form_mobile').hide();
	//toogle
	jQuery('.entry-edit-head').live('click',function() {
		jQuery(this).next().slideToggle(); 
	});
	// check slide layout 
	  
   jQuery('input[name="slide_layout"]').click(function() {
	   var checked =  jQuery('input[name=slide_layout]:checked').val();
		 if(checked == 'full') {
			jQuery('#fullscreen_offset_container').attr('disabled',false); 
			jQuery('#fullscreen_min_height').attr('disabled',false); 
			
		 } else {
			jQuery('#fullscreen_offset_container').attr('disabled',true); 
			jQuery('#fullscreen_min_height').attr('disabled',true); 
		 }
   });
   //background color 
	var image = js_url+"magentothem/revslider/images/";
	jQuery.fn.mColorPicker.defaults.imageFolder= image;
	jQuery("#bg_color").attr("data-hex", true).mColorPicker();

});