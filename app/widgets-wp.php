<?PHP

/**
 * MiniMeta Widgets
 *
 * @package MiniMetaWidgetWP
 */
 
 
class MiniMetaWidgetWP {

// This registers our widget and  widget control for WP
function register() {
	if ( !$options = get_option('minimeta_widget_wp') )
		$options = array();

	$widget_ops = array('description' => __('Displaying Meta links, Login Form and Admin Links','MiniMetaWidget'));
	$control_ops = array('id_base' => 'minimeta');
	$name = __('MiniMeta Widget');

	$registered = false;
	foreach ( array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['title']) ) // we used 'something' above in our exampple.  Replace with with whatever your real data are.
			continue;

		// $id should look like {$id_base}-{$o}
		$id = $control_ops['id_base']."-".$o; // Never never never translate an id
		$registered = true;
		wp_register_sidebar_widget( $id, $name, array('MiniMetaWidgetWP', 'display'), $widget_ops, array( 'number' => $o ) );
		wp_register_widget_control( $id, $name, array('MiniMetaWidgetWP', 'control'), $control_ops, array( 'number' => $o ) );
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$registered ) {
		wp_register_sidebar_widget( $control_ops['id_base'].'-1', $name, array('MiniMetaWidgetWP', 'display'), $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( $control_ops['id_base'].'-1', $name, array('MiniMetaWidgetWP', 'control'), $control_ops, array( 'number' => -1 ) );
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
	$options = get_option('minimeta_widget_wp');
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

		foreach ( (array) $_POST['widget-minimeta'] as $widget_number => $widget_many_instance ) {
			// compile data from $widget_many_instance
			if ( !isset($widget_many_instance['optionset']) && isset($options[$widget_number]) ) // user clicked cancel
				continue;
			$title = wp_specialchars( $widget_many_instance['title'] );
			$optionset = wp_specialchars( $widget_many_instance['optionset'] );
			$options[$widget_number] = array( 'title' => $title, 'optionset' => $optionset );  // Even simple widgets should store stuff in array, rather than in scalar
		}
		
		update_option('minimeta_widget_wp', $options);
		
		$updated = true; // So that we don't go through this more than once
	}
    

	// Here we echo out the form
	
	// We echo out a template for a form which can be converted to a specific form later via JS
	if ( -1 == $number ) {
		$title = __('Meta');
		$optionset='';
		$number='%i%';
	} else {
		$title = attribute_escape($options[$number]['title']);
		$optionset = attribute_escape($options[$number]['optionset']);
	}
	
	
	// The form has inputs with names like widget-minimeta[$number][something] so that all data for that instance of
	// the widget are stored in one $_POST variable: $_POST['widget-minimeta'][$number]
   
	//displaying options
	?>
	<p><label for="minimeta-title-<?php echo $number; ?>"><?php _e('Title:'); ?><input class="widefat" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" /></label><p>
		<label for="minimeta-optionset-<?php echo $number; ?>" title="<?php _e('Select a Widget Config','MiniMetaWidget');?>"><?php _e('Widget Config:','MiniMetaWidget');?> 
         <?PHP 
		 $options_widgets = get_option('minimeta_widget_options');
		 if (is_array($options_widgets)) {
		 ?>
		 <select class="widefat" name="widget-minimeta[<?php echo $number; ?>][optionset]" id="minimeta-optionset-<?php echo $number; ?>"><?PHP
		 echo '<option value="">'.__('default','MiniMetaWidget').'</option>';
			foreach ($options_widgets as $name => $values) {
				?> <option value="<?PHP echo $name;?>"<?PHP selected($optionset,$name);?>><?PHP echo $values['optionname'];?></option>'; <?PHP
			}?>
         </select></label>
		 <?PHP } else { ?>
			<span style="color:red;font-face:italic;"><?PHP _e('default','MiniMetaWidget'); ?></span>
		 <?PHP } ?>
		 <br />
		 <span class="setting-description"><?php _e('To make/change a Widget Config go to <a href="admin.php?page=minimeta-widget">MiniMeta Widget</a>','MiniMetaWidget'); ?></span>
	<input type="hidden" id="minimeta-submit-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][submit]" value="1" /><?php 
}

//Display Widget 
function display( $args, $widget_args = 1 ) {
	
	extract( $args, EXTR_SKIP );
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	// Data should be stored as array:  array( number => data for that instance of the widget, ... )
	$options = get_option('minimeta_widget_wp');
	if ( !isset($options[$number]) )
		return;
	
	//Set options to disply
	$args['title']=$options[$number]['title'];
	MiniMetaWidgetDisplay::display($options[$number]['optionset'],$args);
}


}


?>