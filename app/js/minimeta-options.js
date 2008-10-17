jQuery(document).ready(function() {
	//switsh style for Minimta boxes
	jQuery('.minimetabox h3').before('<span class="togbox">+<\/span> ');
	jQuery('.minimetabox h3, .minimetabox span.togbox').click( function() {
		jQuery(jQuery(this).parent()).toggleClass('closed');
	});
	jQuery('.minimetabox').toggleClass('closed');
	
	//Remove delte boxes when js is activaded
	jQuery('#WidgetOptDelete, #WidgetStyleDelete').remove();			
	
	//Swits style and add link for itm boxes
	jQuery('.widget-login-item, .widget-logout-item').find('div').parent('li').children('h4').children('span').after(' <span style="float: right;" class="widget-edit-item">'+MiniMetaL10n.edit+'<\/span>');
	jQuery('.widget-edit-item').click( function() {
		jQuery(jQuery(this).parent().parent()).toggleClass('closed');
	});
	jQuery('.widget-login-item, .widget-logout-item').toggleClass('closed');
		
});