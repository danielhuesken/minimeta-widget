<?php
/*
Plugin Name: MiniMeta widget
Plugin URI: http://www.huesken-net.de/category/wordpress/minimeta-widget/
Description: Mini Verson dem Meta Widgits
Author: Daniel Huesken
Version: 1.0
Author URI: http://www.huesken-net.de
*/

/*  
	Copyright 2007  Daniel Hüsken  (email : daniel@huesken-net.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

/*
Change log:
  Version 1.1:
 */

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_minnimeta_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_minimeta($args) {
		extract($args);
		$options = get_option('widget_minimeta');
		$title = empty($options['title']) ? __('Meta') : $options['title'];
		?>
		<?php echo $before_widget; ?>
		<?php echo $before_title . $title . $after_title; ?>
		<ul>
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		<?php wp_meta(); ?>
		</ul>
		<?php echo $after_widget; ?>
		<?php
	}
			
	function widget_minimeta_control() {
		$options = $newoptions = get_option('widget_minimeta');
		if ( $_POST["minimeta-submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["minimeta-title"]));
		}
			if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_minimeta', $options);
		}
		$title = attribute_escape($options['title']);
		?>
		<p><label for="minimeta-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title" name="minimeta-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<input type="hidden" id="minimeta-submit" name="minimeta-submit" value="1" />
		<?php
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Mini Meta', 'widgets'), 'widget_minimeta');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Mini Meta', 'widgets'), 'widget_minimeta_control', 300, 190);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_minnimeta_init');

?>
