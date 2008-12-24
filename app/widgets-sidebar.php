<?PHP

/**
 * MiniMeta Fixed Widget
 *
 * @function MiniMetaWidgetSidebar
 */
 

function MiniMetaWidgetSidebar($name) {
	MiniMetaWidgetDisplay::display(wp_create_nonce($name),array('type'=>'PHP'));
}
 