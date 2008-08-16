<?PHP

/**
 * MiniMeta K2
 *
 * @package MiniMetaK2
 */
 
 
class MiniMetaK2 {
	// This registers our widget and  widget control for K2 SBM 
	function register() {  
      register_sidebar_module('MiniMeta Widget', array('MiniMetaK2', 'display'));
      register_sidebar_module_control('MiniMeta Widget', array('MiniMetaK2', 'control'));
	}

	function control() {
		$number=1; //SBM dont need numbers set it to 1
		$options = sbm_get_option('widget_minimeta'); //load Options
		if ( $_POST['widget-minimeta'][$number]) {
			$widget_option_names=MiniMetaFunctions::widget_options();
			foreach ( (array) $widget_option_names as $option_name => $option_value ) {
				$options[$option_name] = isset($_POST['widget-minimeta'][$number][$option_name]);
			}
			unset($options['adminlinks']);
			for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['adminlinks']);$i++) {
				$options['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][$number]['adminlinks'][$i]);
			}
			sbm_update_option('widget_minimeta', $options); //save Options
		} 
    
	
		//make settings
		$widget_option_names=MiniMetaFunctions::widget_options();
		foreach ( (array) $widget_option_names as $option_name => $option_value ) {
			if (!isset($options[$option_name])) $options[$option_name]=$option_value;
			$options_form[$option_name] = $options[$option_name] ? 'checked="checked"' : '';
		}
		if (isset($options['adminlinks'])) $options_form['adminlinksset']=$options['adminlinks'];
	
		//displaying options
		include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widgetcontrol.php'); 
	}

	//Display Widget 
	function display($args) {
		global $user_identity;	
		extract($args, EXTR_SKIP );
		$number=1;
		//load options
		$options[$number] = sbm_get_option('widget_minimeta');
		//title compatibility for K2SBM
		$options[$number]['title']=$title;
		//Includ widget display
		include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widget.php');
	}


}



?>