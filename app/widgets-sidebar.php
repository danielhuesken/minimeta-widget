<?PHP

/**
 * MiniMeta Fixed Widget
 *
 * @function MiniMetaWidgetSidebar
 */
 

function MiniMetaWidgetSidebar($number='0') {
	//Set otions to disply
	$options = get_option('minimeta_widget_sidebar');
	//set def.
	$options[0]['before_widget']='<div class="MiniMetaWidgetSiedbar">';
	$options[0]['title']=__('Meta');
	$options[0]['after_widget']='</div>';
	$options[0]['before_title']='<h4>';
	$options[0]['after_title']='</h4>';
	$options[0]['optionset']='default';
	$options[0]['style']='';
	//Generate Args
	$args=array('before_widget'=>$options[$number]['before_widget'],'title'=>$options[$number]['title'],'after_widget'=>$options[$number]['after_widget'],'before_title'=>$options[$number]['before_title'],'after_title'=>$options[$number]['after_title']);
	
	MiniMetaWidgetDisplay::display($args,$options[$number]['optionset'],$options[$number]['style']);
}
 