<?php

/**
 * MiniMeta Options
 *
 * @package MiniMetaOptions
 */

class MiniMetaOptions {

	function init() {
		if ( is_admin() ) {
			add_action('admin_menu', array('MiniMetaOptions', 'add_menu'));
		}
	}

	function add_menu() {
		$page = add_theme_page(__('MiniMeta Widget','MiniMetaWidget'), __('MiniMeta Widget','MiniMetaWidget'), 'edit_themes', "Minimeta-options", array('MiniMetaOptions', 'admin'));
	}

	function admin() {
		echo "<h2>Test Options</h2>";
	}


}

