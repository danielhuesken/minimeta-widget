<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/portfolio/minimeta/
Description: WordPress (Mini)Meta Widget with different logon types (form,link) and additional admin links. All links can enabeld/disabeld.
Author: Daniel H&uuml;sken
Version: 4.0.0
Author URI: http://danielhuesken.de
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
  Version 1.0.0: 	Inital Release
  Version 2.0.0:	enable/disable links
			Different Login Types
			Login/Logoff with redirect
 Version 2.5.0:	Added links for New Page/Post
			Added Translation functionality
			Added deleting options on deactivateing plugin
  Version 2.5.1       Small Bug fix in new post entry
  Version 2.6.0      More Admin Links Plugin/comments/User
                              All links can now enabled/disabeld for login/logoff
                              Cleand up Options page
   Version 2.6.1     Added Update check
   Version 2.6.2     Added User Identity function to Title
                               Removed Your Profile form Links and add the link to Title
   Version 2.6.3     Code Cleanup
                               Fixed some bugs
   Version 2.6.4    Comatibility for Sidebar Modules and K2 SBM
                              Added German Translation (Only not in WordPress strings)
   Version 2.6.5    removed Update check because its integratet in WP 2.3
   Version 2.7.0    Support for WP Admin Links (http://wordpress.org/extend/plugins/wp-admin-links/)
                              Some more Code Cleanup
                              Changed MinMeta.php to minimeta-widget.php
                              Added plugin hooks for login form
                                    Testet with:    Semisecure Login (http://wordpress.org/extend/plugins/semisecure-login/)
                                                            Raz-Captcha (http://wordpress.org/extend/plugins/raz-captcha/) must delete seite dependings
                                                            Chap Secure Login (http://wordpress.org/extend/plugins/chap-secure-login/)
   Version 2.7.1    Grammer fixes (thx Joe)
                             Updatet German Localisation (thx Joe)
                             readded link to Your Profile
   Version 3.0.0    Better/full support for K2 SBM
                           Plasing the widget up to 9 times
                           removed WP-Admin Links Plugin Support
                           Integrated owen Admin Links
                           Style Sheet Support
                           Support for own Style Sheet and Admin Links
    Version 3.0.1    Bugfix </Optiongroup>
                            Bugfix wron <li> (thx David Potter)
                            Grammer fixes
                            Cookie handlind for login fix
                            cusom style not lod fix
                            CSS syle fix for thems
    Version 3.5.0    Full Compatibility to WP 2.5
                             Added Opten to disable topics for Admin Links
                             Added enable/disable Wordpress Cookie test
                             Added enable/disable login/ou redirect
                             <ul> xhtml fixes
                            Loginform an Link at same time
                            Atomatik Admin Links creation as Adminon Plugins Tab. minimeta-adminlinks.php no more nedded.
   Version 3.5.1   Add message to notify when WP is lower than 2.5
                          Fix bug at adminlinks selection
   Version 3.5.2    Now Hopfull complite bug fix at adminlinks selection
   Version 3.5.3    Adminlinks creation for menus with empty submenus and menus without same submenu link
   Version 3.5.4    admin_head corections
                           adminlink generation improfments
                           more <ul> xhtml fixes
   Version 3.5.5    Fixes for K2 1.0-RC6
                              WP 2.6 Plugin dir copatibilty
   Version 3.5.6  Lang Path fix
   Version 3.5.7  K2RC7 Copatibility older SBM don't work
		       https path copatibility
   Version 3.5.8  Path fix for wp 2.5
   Version 3.5.9 Bug fix for no topics
		      New language file selection    
   Version 4.0.0  New Plugin file strucktur
		      Support for Sidebar Widget with function
		       Unistall function as form and for WP 2.7
	                  Best K2SBM detection
		       Display own Links from Blog Links
		       Complete new Optionspage
 */


//Set plugin dirname
define('WP_MINMETA_PLUGIN_DIR', dirname(plugin_basename(__FILE__)));

$minimeta_plugin_load=true;
//Version checks
if (version_compare($wp_version, '2.5', '<')) { // Let only Activate on WordPress Version 2.5 or heiger
	add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, MiniMeta Widget works only under WordPress 2.5 or higher',"MiniMetaWidget") . '</strong></p></div>\';'));
	$minimeta_plugin_load=false;
} elseif (version_compare($wp_version, '2.6', '<')) {   // Pre-2.6 compatibility
	define( 'WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins' );
	define( 'WP_PLUGIN_URL', get_option( 'siteurl' ) . 'wp-content/plugins' );
	if (!function_exists('site_url')) {
		function site_url($path = '', $scheme = null) { 
			return get_bloginfo('wpurl').'/'.$path;
		}
	}
	if (!function_exists('admin_url')) {
		function admin_url($path = '') {
			return get_bloginfo('wpurl').'/wp-admin/'.$path;
		}
	}
	if (!function_exists('plugins_url')) {
		function plugins_url($path = '') { 
			return get_option('siteurl') . '/wp-content/plugins/'.$path;
		}
	}
} 

if ($minimeta_plugin_load) {
	//Load fuction file
	require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/functions.php');

	//Plugin init	
	add_action('plugins_loaded', array('MiniMetaFunctions', 'plugins_textdomain'),9); //lod bevor init
	add_action('plugins_loaded', array('MiniMetaFunctions', 'init'));
	
	//install
	register_activation_hook(__FILE__, array('MiniMetaFunctions', 'install'));
	//uninstall for 2.7
	if ( function_exists('register_uninstall_hook') )
		register_uninstall_hook(__FILE__, array('MiniMetaFunctions', 'uninstall'));
}
?>
