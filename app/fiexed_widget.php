<?PHP

/**
 * MiniMeta Fixed Widget
 *
 * @package MiniMetaFiexedWidget
 */
 

function MiniMetaWidget($before_title='<h4>',$after_title='</h4>',$before_widget='<div id="minimeta-fixed" class="minimeta-fixed">',$after_widget='</div>',$number=1) {
	MiniMetaFiexedWidget::display(array('before_widget'=>$before_widget,'after_widget'=>$after_widget,'before_title'=>$before_title,'after_title'=>$after_title),$number);
}
 
class MiniMetaFiexedWidget {
	//Display Widget 
	function display($args,$number = 1) {
		global $user_identity;	
		extract($args, EXTR_SKIP);
		//load options
		$options = get_option('widget_minimeta_fixed_widget');
     
		include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widget.php');
	}
}