<?PHP

/**
 * MiniMeta Widgets
 *
 * @package MiniMetaWPWidgets
 */
 
 
class MiniMetaWPWidgets {

// This registers our widget and  widget control for WP
function register() {
	if ( !$options = get_option('widget_minimeta') )
		$options = array();

	$widget_ops = array('description' => __('Displaying Meta links, Login Form and Admin Links','MiniMetaWidget'));
	$control_ops = array('width' => 500, 'height' => 550, 'id_base' => 'minimeta');
	$name = __('MiniMeta Widget');

	$registered = false;
	foreach ( array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['title']) ) // we used 'something' above in our exampple.  Replace with with whatever your real data are.
			continue;

		// $id should look like {$id_base}-{$o}
		$id = $control_ops['id_base']."-".$o; // Never never never translate an id
		$registered = true;
		wp_register_sidebar_widget( $id, $name, array('MiniMetaWPWidgets', 'display'), $widget_ops, array( 'number' => $o ) );
		wp_register_widget_control( $id, $name, array('MiniMetaWPWidgets', 'control'), $control_ops, array( 'number' => $o ) );
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$registered ) {
		wp_register_sidebar_widget( $control_ops['id_base'].'-1', $name, array('MiniMetaWPWidgets', 'display'), $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( $control_ops['id_base'].'-1', $name, array('MiniMetaWPWidgets', 'control'), $control_ops, array( 'number' => -1 ) );
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
			 $widget_option_names=MiniMetaFunctions::widget_options();
			 foreach ( (array) $widget_option_names as $option_name => $option_value ) {
				$options[$widget_number][$option_name] = isset($_POST['widget-minimeta'][$widget_number][$option_name]);
			 }
            unset($options[$widget_number]['adminlinks']);
            for ($i=0;$i<sizeof($_POST['widget-minimeta'][$widget_number]['adminlinks']);$i++) {
                $options[$widget_number]['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][$widget_number]['adminlinks'][$i]);
            }
			$options[$widget_number]['linksin']="";
			unset($options[$widget_number]['linksin']);
			for ($i=0;$i<sizeof($_POST['widget-minimeta'][$widget_number]['linksin']);$i++) {
				if (isset($_POST['widget-minimeta'][$widget_number]['linksin'][$i])) $options[$widget_number]['linksin'] .= $_POST['widget-minimeta'][$widget_number]['linksin'][$i].",";
			}
			$options[$widget_number]['linksin'] = substr($options[$widget_number]['linksin'], 0, -1);
			$options[$widget_number]['linksout']="";
			unset($options[$widget_number]['linksout']);
			for ($i=0;$i<sizeof($_POST['widget-minimeta'][$widget_number]['linksout']);$i++) {
				if (isset($_POST['widget-minimeta'][$widget_number]['linksout'][$i])) $options[$widget_number]['linksout'] .= $_POST['widget-minimeta'][$widget_number]['linksout'][$i].",";
			}
			$options[$widget_number]['linksout'] = substr($options[$widget_number]['linksout'], 0, -1);
		}

		update_option('widget_minimeta', $options);
		$updated = true; // So that we don't go through this more than once
	}
    

	// Here we echo out the form
	
	$widget_option_names=MiniMetaFunctions::widget_options();
	foreach ( (array) $widget_option_names as $option_name => $option_value ) {
		if (!isset($options[$number][$option_name])) $options[$number][$option_name]=$option_value;
		$options_form[$option_name] = $options[$number][$option_name] ? 'checked="checked"' : '';
	}
	// We echo out a template for a form which can be converted to a specific form later via JS
	if ( -1 == $number ) {
		$title = __('Meta');
		$number='%i%';
	} else {
		$title = attribute_escape($options[$number]['title']);
		$options_form['adminlinks']=$options[$number]['adminlinks'];
		$options_form['linksin']=$options[$number]['linksin'];
		$options_form['linksout']=$options[$number]['linksout'];
	}
	
	
	// The form has inputs with names like widget-minimeta[$number][something] so that all data for that instance of
	// the widget are stored in one $_POST variable: $_POST['widget-minimeta'][$number]
   
	//displaying options
	?><label for="minimeta-title-<?php echo $number; ?>"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" /></label><?php 
    include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widgetcontrol.php'); 
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
      
	include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/display/widget.php');
}




}


?>