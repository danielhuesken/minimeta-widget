<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/protfolio/minimeta/
Description: Mini Version of the WP Meta Widget with different logon types and some additional admin links.
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
   Version 4.0.0  
   
*/
 
// add all action and so on only if plugin loaded.
function widget_minimeta_init() {
    global $wp_version,$pagenow;
	
    // Pre-2.6 compatibility
    if ( !defined('WP_PLUGIN_DIR') )
        define( 'WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins' );
	if (!function_exists("site_url")) {
	    function site_url($path = '', $scheme = null) { 
			return get_bloginfo('wpurl').'/'.$path;
		}
	}
	if (!function_exists("admin_url")) {
	    function admin_url($path = '') {
			return get_bloginfo('wpurl').'/wp-admin/'.$path;
		}
	}
	if (!function_exists("plugins_url")) {
	    function plugins_url($path = '') { 
			return get_option('siteurl') . '/wp-content/plugins/'.$path;
		}
	}
    
	//Set plugin dirname
	define('WP_MINMETA_PLUGIN_DIR', dirname(plugin_basename(__FILE__)));
	
    //Loads language files
	load_plugin_textdomain('MiniMetaWidget', PLUGINDIR.'/'.WP_MINMETA_PLUGIN_DIR.'/lang');
	
    // Let only Activate on WordPress Version 2.5 or heiger
    if (version_compare($wp_version, '2.5', '<')) {
        add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, MiniMeta Widget works only under WordPress 2.5 or higher',"MiniMetaWidget") . '</strong></p></div>\';'));
	    return;
    }
    
	require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/minimeta.php');
	if (has_action('login_head')) add_action('wp_head', array('MiniMetaFunctions', 'head_login'),1);
	add_action('wp_head', array('MiniMetaFunctions', 'wp_head'));
	add_action('admin_init',array('MiniMetaFunctions', 'generate_adminlinks'),1);
	
	require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/options.php');
	MiniMetaOptions::init();

    //find out if K2 and his SBM is activatet and set K2_LOAD_SBM Konstant if it not set
    if (!defined('K2_LOAD_SBM')) 
            define('K2_LOAD_SBM',false);
 
    //Only add actions and so on if Plugin is Activaded
	if (function_exists('register_sidebar_widget')) {
		if (K2_LOAD_SBM) { //K2 SBM only
			require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/k2.php');
			MiniMetaK2::register();
			//add_action('admin_head-themes_page_k2-sbm-manager', 'widget_minimeta_admin_head'); //dont work anymore
			if ("themes.php"==$pagenow) add_action('admin_head', array('MiniMetaFunctions', 'admin_head'));
		} else { //WP only
			require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widgets.php');
			add_action('widgets_init', array('MiniMetaWidgets', 'register'));
			//add_action('admin_head-themes_page_widgets', 'widget_minimeta_admin_head'); //dont work
			if ("widgets.php"==$pagenow) add_action('admin_head', array('MiniMetaFunctions', 'admin_head'));
		}
	}
}   
add_action('init', 'widget_minimeta_init',1); //1 must because WP widgets_init don't work

// Deactivate plugin -Delete all Options
function widget_minimeta_deactivate() {
    delete_option('widget_minimeta');
    delete_option('widget_minimeta_adminlinks');
}
register_deactivation_hook(__FILE__,'widget_minimeta_deactivate');
?>
