<?PHP

/**
 * MiniMeta K2
 *
 * @package MiniMetaWidgetK2SBM
 */
 
 
class MiniMetaWidgetK2SBM {
	// This registers our widget and  widget control for K2 SBM 
	function register() {  
      register_sidebar_module('MiniMeta Widget', array('MiniMetaWidgetK2SBM', 'display'));
      register_sidebar_module_control('MiniMeta Widget', array('MiniMetaWidgetK2SBM', 'control'));
	}

	function control() {
		$number=1; //SBM dont need numbers set it to 1
		$options = sbm_get_option('minimeta_widget'); //load Options
		if ( $_POST['widget-minimeta'][$number]) {
			$options['optionset'] = wp_specialchars($_POST['widget-minimeta'][$number]['optionset']);
			sbm_update_option('minimeta_widget', $options); //save Options
		} 
	
		//make settings
		$optionset=$options['optionset'];
	
		//displaying options
		include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widgetcontrol.php'); 
	}

	//Display Widget 
	function display($args) {
		global $user_identity;	
		extract($args, EXTR_SKIP );
		//load options
		$options = sbm_get_option('minimeta_widget');
		
		//Set options to disply
		$optionset = get_option('minimeta_widget_options');
		if (isset($optionset[$options['optionset']])) {
			$optionsetname = $options['optionset'];
		} else {
			$optionsetname = 'default';
		} 
		$optionset[$optionsetname]['title']=$title;

		//Includ widget display
		include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widget.php');
	}


}



?>