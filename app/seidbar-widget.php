<?PHP

/**
 * MiniMeta Fixed Widget
 *
 * @package MiniMetaSiedbarWidget
 */
 

function MiniMetaSiedbarWidget($before_title='<h2>',$after_title='</h2>',$before_widget='',$after_widget='',$number=1) {
	MiniMetaSiedbarWidget::display(array('before_widget'=>$before_widget,'after_widget'=>$after_widget,'before_title'=>$before_title,'after_title'=>$after_title),$number);
}
 
class MiniMetaSiedbarWidget {
	//Display Widget 
	function display($args,$number = 1) {
		global $user_identity;	
		extract($args, EXTR_SKIP);
		//load options
		$options = get_option('widget_minimeta_fixed_widget');
     
		include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widget.php');
	}
}