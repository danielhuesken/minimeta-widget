<?PHP

/**
 * MiniMeta Widgets
 *
 * @package MiniMetaWidgets
 */
 
 
class MiniMetaWidgets {

// This registers our widget and  widget control for WP
function register() {
	if ( !$options = get_option('widget_minimeta') )
		$options = array();

	$widget_ops = array('description' => __('Displaying Meta links, Login Form and Admin Links','MiniMetaWidget'));
	$control_ops = array('width' => 450, 'height' => 550, 'id_base' => 'minimeta');
	$name = __('MiniMeta Widget');

	$registered = false;
	foreach ( array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['title']) ) // we used 'something' above in our exampple.  Replace with with whatever your real data are.
			continue;

		// $id should look like {$id_base}-{$o}
		$id = $control_ops['id_base']."-".$o; // Never never never translate an id
		$registered = true;
		wp_register_sidebar_widget( $id, $name, array('MiniMetaWidgets', 'display'), $widget_ops, array( 'number' => $o ) );
		wp_register_widget_control( $id, $name, array('MiniMetaWidgets', 'control'), $control_ops, array( 'number' => $o ) );
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$registered ) {
		wp_register_sidebar_widget( $control_ops['id_base'].'-1', $name, array('MiniMetaWidgets', 'display'), $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( $control_ops['id_base'].'-1', $name, array('MiniMetaWidgets', 'control'), $control_ops, array( 'number' => -1 ) );
	}
}

function control($widget_args = 1) {
    global $wp_registered_widgets;
	static $updated = false; // Whether or not we have already updated the data after a POST submit

	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	// Data should be stored as array:  array( number => data for that instance of the widget, ... )
	$options = get_option('widget_minimeta');
	if ( !is_array($options) )
		$options = array();
    
    	// We need to update the data
	if ( !$updated && !empty($_POST['sidebar']) ) {
		// Tells us what sidebar to put the data in
		$sidebar = (string) $_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach ( $this_sidebar as $_widget_id ) {
			// Remove all widgets of this type from the sidebar.  We'll add the new data in a second.  This makes sure we don't get any duplicate data
			// since widget ids aren't necessarily persistent across multiple updates
			if ( 'widget_minimeta' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( "minimeta-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed. "many-$widget_number" is "{id_base}-{widget_number}
					unset($options[$widget_number]);
			}
		}

		foreach ( (array) $_POST['widget-minimeta'] as $widget_number => $widget_minmeta_instance ) {
            // compile data from $widget_minmeta
			$options[$widget_number]['title'] = wp_specialchars($widget_minmeta_instance['title']);
			$options[$widget_number]['loginlink'] = isset($widget_minmeta_instance['loginlink']);
            $options[$widget_number]['loginform'] = isset($widget_minmeta_instance['loginform']);
			$options[$widget_number]['logout'] = isset($widget_minmeta_instance['logout']);
            $options[$widget_number]['registerlink'] = isset($widget_minmeta_instance['registerlink']);
            $options[$widget_number]['testcookie'] = isset($widget_minmeta_instance['testcookie']);
            $options[$widget_number]['redirect'] = isset($widget_minmeta_instance['redirect']);
            $options[$widget_number]['seiteadmin'] = isset($widget_minmeta_instance['seiteadmin']);
			$options[$widget_number]['rememberme'] = isset($widget_minmeta_instance['rememberme']);
			$options[$widget_number]['rsslink'] = isset($widget_minmeta_instance['rsslink']);
			$options[$widget_number]['rsscommentlink'] = isset($widget_minmeta_instance['rsscommentlink']);
			$options[$widget_number]['wordpresslink'] = isset($widget_minmeta_instance['wordpresslink']);
			$options[$widget_number]['lostpwlink'] = isset($widget_minmeta_instance['lostpwlink']);
			$options[$widget_number]['profilelink'] = isset($widget_minmeta_instance['profilelink']);
            $options[$widget_number]['showwpmeta'] = isset($widget_minmeta_instance['showwpmeta']);
            $options[$widget_number]['displayidentity'] = isset($widget_minmeta_instance['displayidentity']);
            $options[$widget_number]['useselectbox'] = isset($widget_minmeta_instance['useselectbox']);          
            $options[$widget_number]['notopics'] = isset($widget_minmeta_instance['notopics']); 
            unset($options[$widget_number]['adminlinks']);
            for ($i=0;$i<sizeof($_POST['widget-minimeta'][$widget_number]['adminlinks']);$i++) {
                $options[$widget_number]['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][$widget_number]['adminlinks'][$i]);
            }
		}

		update_option('widget_minimeta', $options);
		$updated = true; // So that we don't go through this more than once
	}
    

	// Here we echo out the form
	if ( -1 == $number ) { // We echo out a template for a form which can be converted to a specific form later via JS
		$title = __('Meta');
        $loginlink='checked="checked"';
        $loginform='';
        $logout='checked="checked"';
        $registerlink='checked="checked"';
        $testcookie='';
        $redirect='';
        $seiteadmin='checked="checked"';
        $rememberme='checked="checked"';
        $rsslink='checked="checked"';
        $rsscommentlink='checked="checked"';
        $wordpresslink='checked="checked"';
        $lostpwlink='';
        $profilelink='';
        $showwpmeta='checked="checked"';
        $displayidentity='';
        $useselectbox='';
        $notopics='';
		$number='%i%';
	} else {
		$title = attribute_escape($options[$number]['title']);
		$loginlink = $options[$number]['loginlink'] ? 'checked="checked"' : '';
        $loginform = $options[$number]['loginform'] ? 'checked="checked"' : '';
        $logout = $options[$number]['logout'] ? 'checked="checked"' : '';
        $registerlink = $options[$number]['registerlink'] ? 'checked="checked"' : '';
        $testcookie = $options[$number]['testcookie'] ? 'checked="checked"' : '';
        $redirect = $options[$number]['redirect'] ? 'checked="checked"' : '';
        $seiteadmin = $options[$number]['seiteadmin'] ? 'checked="checked"' : '';
		$rememberme = $options[$number]['rememberme'] ? 'checked="checked"' : '';
		$rsslink = $options[$number]['rsslink'] ? 'checked="checked"' : '';
		$rsscommentlink = $options[$number]['rsscommentlink'] ? 'checked="checked"' : '';
		$wordpresslink = $options[$number]['wordpresslink'] ? 'checked="checked"' : '';
		$lostpwlink = $options[$number]['lostpwlink'] ? 'checked="checked"' : '';
		$profilelink= $options[$number]['profilelink'] ? 'checked="checked"' : '';
        $showwpmeta = $options[$number]['showwpmeta'] ? 'checked="checked"' : '';
        $displayidentity = $options[$number]['displayidentity'] ? 'checked="checked"' : '';
        $useselectbox = $options[$number]['useselectbox'] ? 'checked="checked"' : '';
        $notopics = $options[$number]['notopics'] ? 'checked="checked"' : '';
        $adminlinksset = $options[$number]['adminlinks'];
  	}

	// The form has inputs with names like widget-minimeta[$number][something] so that all data for that instance of
	// the widget are stored in one $_POST variable: $_POST['widget-minimeta'][$number]
   
	//displaying options
	?><label for="minimeta-title-<?php echo $number; ?>"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" /></label><?php 
    include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/display/widgetcontrol.php'); 
	?><input type="hidden" id="minimeta-submit-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][submit]" value="1" /><?php 
}

//Display Widget 
function display($args,$widget_args = 1) {
    global $user_identity;	
    extract( $args, EXTR_SKIP );
    
    if ( is_numeric($widget_args) )
        $widget_args = array( 'number' => $widget_args );
    $widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
    extract( $widget_args, EXTR_SKIP );
    $options = get_option('widget_minimeta');
    if ( !isset($options[$number]) )
        return;
      
	include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/display/widget.php');
}




}


?>