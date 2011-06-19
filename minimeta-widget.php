<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/portfolio/minimeta/
Description: WordPress (Mini)Meta Widget with different logon types (form,link) and additional admin links. All links can enabeld/disabeld.
Author: Daniel H&uuml;sken
Version: 5.0-Beta
Author URI: http://danielhuesken.de
Text Domain: MiniMetaWidget
Domain Path: /lang/
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

// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

//Ste Plugin Version
define('WP_MINMETA_VERSION', '5.0-Beta');
//global $wp_version;
//load Text Domain
load_plugin_textdomain('minimeta-widget', false, dirname(plugin_basename(__FILE__)).'/lang');	 //TextDomain 
//Load functions file
require_once(dirname(__FILE__).'/minimeta-functions.php');
//Version check
if (version_compare($wp_version, '3.2-RC1', '<')) { // Let only Activate on WordPress Version 3.2 or heiger
	add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, MiniMeta Widget works only under WordPress 3.2 or higher','minimeta-widget') . '</strong></p></div>\';'));
} else {
	//add menu
	add_action('admin_menu', 'minimeta_menu_entry');
	//add action forward for login
	if (has_action('login_head'))
		add_action('wp_head', 'minimeta_head_login',1);
	//add Admin Bar menu
	add_action('admin_bar_menu', 'minimeta_add_adminbar',100);
	//Additional links on the plugin page
	add_filter('plugin_action_links_'.dirname(plugin_basename(__FILE__)).'/minimeta-widget.php', 'minimeta_plugins_options_link');		
	add_filter('plugin_row_meta', 'minimeta_plugin_links',10,2);
	//Generate Adminlinks on plugin page
	add_action('load-plugins.php','minimeta_generate_adminlinks',1); //Generate Adminlinks on plugins page
	//Support for Wordpress Widgets
	add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_MiniMeta");'));
	//Widget Parts
	require_once(dirname(__FILE__).'/widget-parts.php');
}
?>
