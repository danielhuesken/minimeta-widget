jQuery(document).ready(function() {
	//switsh style for Minimta boxes
	jQuery('.postbox h3').click( function() {
		jQuery(jQuery(this).parent()).toggleClass('closed');
	});
	// close postboxes that should be closed
	jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');
	
	//Remove delte boxes when js is activaded
	jQuery('#WidgetOptDelete').remove();			
	
	//Swits style and add link for part boxes
	//addd/remove linke when part box is active or not
	jQuery('.checkbox-active').change(function () {
        jQuery('input').parent('span').next('span').remove();
		jQuery('input:checked').parent('span').parent('h4').parent('li:has(div > input),').children('h4').children('span').after(' <span style="float: right;" class="widget-edit-item">'+MiniMetaL10n.edit+'<\/span>');
		jQuery(jQuery(this).parent().parent().parent()).addClass('closed');
		jQuery('.widget-edit-item').click( function() {
			jQuery(jQuery(this).parent().parent()).toggleClass('closed');
		});
    });
	//Start settings of selectet parts
	jQuery('.checkbox-active:checked').parent('span').parent('h4').parent('li:has(div > input),').children('h4').children('span').after(' <span style="float: right;" class="widget-edit-item">'+MiniMetaL10n.edit+'<\/span>');
	jQuery('.widget-edit-item').click( function() {
		jQuery(jQuery(this).parent().parent()).toggleClass('closed');
	});
	//extra link for general becouse click function was add to often
	jQuery('.widget-general-item').children('h4').children('span').after(' <span style="float: right;" class="widget-general-edit-item">'+MiniMetaL10n.edit+'<\/span>');
	jQuery('.widget-general-edit-item').click( function() {
		jQuery(jQuery(this).parent().parent()).toggleClass('closed');
	});	
	jQuery('.widget-login-item, .widget-logout-item, .widget-general-item').toggleClass('closed');
});
