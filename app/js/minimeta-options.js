jQuery(document).ready(function() {
	//switsh style for Minimta boxes
	jQuery('.minimetabox h3').click( function() {
		jQuery(jQuery(this).parent()).toggleClass('closed');
	});
	// close postboxes that should be closed
	jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');
	
	//Remove delte boxes when js is activaded
	jQuery('#WidgetOptDelete').remove();			
	
	//Swits style and add link for itm boxes
	jQuery('.widget-logout-item, .widget-login-item, .widget-general-item').find('div').parent('li').children('h4').children('span').after(' <span style="float: right;" class="widget-edit-item">'+MiniMetaL10n.edit+'<\/span>');
	
	jQuery('.widget-edit-item').click( function() {
		jQuery(jQuery(this).parent().parent()).toggleClass('closed');
	});
	jQuery('.widget-login-item, .widget-logout-item, .widget-general-item').toggleClass('closed');
});
