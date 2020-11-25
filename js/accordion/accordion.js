jQuery(document).ready(function() {
	jQuery( window ).load(function() {
		jQuery('.accordion-section-1 .fa').toggleClass('fa-sort-asc');
  		jQuery('.accordion .accordion-section-1').addClass('active');
		jQuery('.accordion .accordion-section-content-1').slideDown(300).addClass('open');
	});
	function close_accordion_section() {
		jQuery('.accordion .accordion-section-title').removeClass('active');
		jQuery('.accordion .accordion-section-content').slideUp(300).removeClass('open');
	}
	/*jQuery('.accordion-section-1').click(function(e) {
		if(jQuery(".accordion-section-1").hasClass('active')) {
		  } else {
		jQuery('.accordion-section-1 .fa').toggleClass('fa-sort-asc');
		jQuery('.accordion-section-2 .fa').toggleClass('fa-sort-asc');
		jQuery('.accordion .accordion-section-1').addClass('active');
		jQuery('.accordion .accordion-section-content-1').slideDown(300).addClass('open');
		jQuery('.accordion .accordion-section-2').removeClass('active');
		jQuery('.accordion .accordion-section-content-2').slideUp(300).removeClass('open');
		}	
	}); */
	jQuery('#button-accordion-procced').click(function(e) {
	var radioValue = jQuery("input[name='shedule']:checked").val();
	 if(radioValue) {
		jQuery('.accordion-section-2 .fa').toggleClass('fa-sort-asc');
		jQuery('.accordion .accordion-section-2').addClass('active');
		jQuery('.accordion .accordion-section-content-2').slideDown(300).addClass('open');

		if(radioValue == 'Kirkland'){
			jQuery('#Kirkland-containt').show();
			jQuery('#Tacoma-containt').hide();
                        jQuery('#Lynnwood-containt').hide();
			jQuery('#no-option-containt').hide();
		} else if(radioValue == 'Tacoma') {
			jQuery('#Kirkland-containt').hide();
			jQuery('#Tacoma-containt').show();
                        jQuery('#Lynnwood-containt').hide();
			jQuery('#no-option-containt').hide();	
		} else if(radioValue == 'Lynnwood') {
			jQuery('#Kirkland-containt').hide();
			jQuery('#Tacoma-containt').hide();
                        jQuery('#Lynnwood-containt').show();
			jQuery('#no-option-containt').hide();
		}else {
			jQuery('#Kirkland-containt').hide();
			jQuery('#Tacoma-containt').hide();
                        jQuery('#Lynnwood-containt').hide();
			jQuery('##no-option-containt').show();
		}
	} else {
		alert('Please Select a Option');
		return false;
	}
	});
	jQuery('.accordion-section-3').click(function(e) {
		var radioValue = jQuery("input[name='shedule']:checked").val();
		if(radioValue) {
		if(jQuery(".accordion-section-3").hasClass('active')) {
		jQuery('.accordion .accordion-section-3').removeClass('active');
		jQuery('.accordion-section-3 .fa').toggleClass('fa-sort-asc');
		jQuery('.accordion .accordion-section-content-3').slideUp(300).removeClass('open');
		  } else {
		jQuery('.accordion-section-3 .fa').toggleClass('fa-sort-asc');
		jQuery('.accordion .accordion-section-3').addClass('active');
		jQuery('.accordion .accordion-section-content-3').slideDown(300).addClass('open');
		
		}
		}	
	}); 

});


