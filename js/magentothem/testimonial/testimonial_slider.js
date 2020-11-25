var $testimonialSidebar = jQuery.noConflict();				  
$testimonialSidebar(document).ready(function() {				
	$testimonialSidebar('#testimonialSidebar').bxSlider({					
		auto: true,					
		mode: 'fade',					
		speed: 500,pause:20000,					
		preloadImages: 'visible',					
		controls: false,						
		pager: true				  
	});			  
});	
