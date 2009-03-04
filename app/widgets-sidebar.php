<?PHP

/**
 * MiniMeta Fixed Widget
 *
 * @function MiniMetaWidgetSidebar
 */
 

function MiniMetaWidgetSidebar($name) {
	foreach (get_option('minimeta_widget_options') as $option => $optionvalue) {
		if (strtolower($optionvalue['optionname'])==strtolower($name)) $mmconfigid=$option;
	}
	MiniMetaWidgetDisplay::display($mmconfigid,array('type'=>'PHP'));
}
 