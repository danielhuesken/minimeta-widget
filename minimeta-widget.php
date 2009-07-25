<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/portfolio/minimeta/
Description: WordPress (Mini)Meta Widget with different logon types (form,link) and additional admin links. All links can enabeld/disabeld.
Author: Daniel H&uuml;sken
Version: 4.2.2
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


//Set plugin dirname
define('WP_MINMETA_PLUGIN_DIR', dirname(plugin_basename(__FILE__)));
//Ste Plugin Version
define('WP_MINMETA_VERSION', '4.2.2');

//load Text Domain
if (!function_exists('wp_print_styles')) {
	load_plugin_textdomain('MiniMetaWidget', PLUGINDIR.'/'.WP_MINMETA_PLUGIN_DIR.'/lang');	
} else {
	load_plugin_textdomain('MiniMetaWidget', false, WP_MINMETA_PLUGIN_DIR.'/lang');	 //TextDomain for WP 2.6 and heiger
}	
// Load Pre-2.7 compatibility
if (version_compare($wp_version, '2.7', '<'))    
	require_once('app/compatibility.php');
//Load functions file
require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/functions.php');
//install
register_activation_hook(__FILE__, array('MiniMetaFunctions', 'install'));
//uninstall for 2.7
if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook(__FILE__, array('MiniMetaFunctions', 'uninstall'));

//Version checks
if (version_compare($wp_version, '2.5', '<')) { // Let only Activate on WordPress Version 2.5 or heiger
	add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, MiniMeta Widget works only under WordPress 2.5 or higher',"MiniMetaWidget") . '</strong></p></div>\';'));
} else {
	//Plugin init	
	add_action('plugins_loaded', array('MiniMetaFunctions', 'init'));
}
?>
