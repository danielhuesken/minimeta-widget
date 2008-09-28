<?PHP

/**
 * MiniMeta Fixed Widget
 *
 * @package MiniMetaWidgetSidebar
 */
 

function MiniMetaWidgetSidebar($before_title='<h4>',$title='Meta',$after_title='</h4>',$before_widget='<div class="MiniMetaSiedbarWidget">',$after_widget='</div>',$name= 'default') {
	MiniMetaSiedbarWidget::display(array('before_widget'=>$before_widget,'title'=>$title,'after_widget'=>$after_widget,'before_title'=>$before_title,'after_title'=>$after_title),$name);
}
 
class MiniMetaWidgetSidebar {
	//Display Widget 
	function display($args,$name = 'default') {
		global $user_identity;	
		extract($args, EXTR_SKIP);

		//Set otions to disply
		$optionsetname=$name;
		$optionset = get_option('minimeta_widget_options');
		$optionset[$optionsetname]['title']=$title;
		     
		include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widget.php');
	}
}