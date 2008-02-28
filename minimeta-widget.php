<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/protfolio/minimeta/
Description: Mini Version of the WP Meta Widget with different logon types and some additional admin links.
Author: Daniel H&uuml;sken
Version: 3.1.0
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
    Version 3.1.0    Full Compatibility to WP 2.5
                              Added Opten to disable topics for Admin Links
                              Added enable/disable Wordpress Cookie test
*/


// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_minnimeta_init() {
	//Loads language files
	load_plugin_textdomain('MiniMetaWidget', 'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/lang');
	
	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;
    
    //find out if K2 is activatet and set K2_USING_SBM Konstant
    if (!defined(K2_CURRENT)) define('K2_USING_SBM',false);
    
    if (file_exists('custom/minimeta-adminlinks.php')) { //include Admi Links Data
      require('custom/minimeta-adminlinks.php');
    } else {
      require('minimeta-adminlinks.php');
    }   
    widget_minimeta_register();
}   
add_action('init', 'widget_minnimeta_init');

   
function widget_minimeta($args,$widget_args = 1) {
    global $user_identity;	
    extract( $args, EXTR_SKIP );
    
    //load options
    if (K2_USING_SBM) {
        $number=1;
        $options[$number] = sbm_get_option('widget_minimeta');
        //title compatibility for K2SBM
        $options[$number]['title']=$title;
    } else {
        if ( is_numeric($widget_args) )
            $widget_args = array( 'number' => $widget_args );
        $widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
        extract( $widget_args, EXTR_SKIP );
        $options = get_option('widget_minimeta');
    }
    
    //Don´t show Wiget if it have no links
    if (!((!is_user_logged_in() and $options[$number]['login']=='off' and !$options[$number]['registerlink'] and !$options[$number]['rememberme'] and !$options[$number]['lostpwlink'] and !$options[$number]['rsslink'] and !$options[$number]['rsscommentlink'] and !$options[$number]['wordpresslink'] and !$options[$number]['showwpmeta']) or
           (is_user_logged_in() and !$options[$number]['logout'] and !$options[$number]['seiteadmin'] and sizeof($options[$number]['adminlinks'])==0 and !$options[$number]['rsslink'] and !$options[$number]['rsscommentlink'] and !$options[$number]['wordpresslink'] and !$options[$number]['showwpmeta']))) {
        
	//Shown part of Widget
    echo $before_widget;
        
        if(is_user_logged_in()) {
            if ($options[$number]['displayidentity'] and !empty($user_identity)) $options[$number]['title']=$user_identity;
            if($options[$number]['profilelink'] and current_user_can('read')) {
                echo $before_title ."<a href=\"".get_bloginfo('wpurl')."/wp-admin/profile.php\" title=\"".__('Your Profile')."\">". $options[$number]['title'] ."</a>". $after_title; 
            } else {
            echo $before_title . $options[$number]['title'] . $after_title; 
            }
 
                echo "<ul>";
                if($options[$number]['seiteadmin']) {wp_register();}
                if($options[$number]['logout']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=logout&amp;redirect_to=".$_SERVER['REQUEST_URI']."\" title=\"".__('Logout')."\" class=\"minimeta-logout\">".__('Logout')."</a></li>"; 
             
                if (sizeof($options[$number]['adminlinks'])>0) { //show only if a Admin Link is selectesd
                 if ($options[$number]['useselectbox']) {
                    echo "</ul>";
                    echo "<select class=\"minimeta-adminlinks\" tabindex=\"95\" onchange=\"window.location = this.value\"><option selected=\"selected\">".__('Please select:','MiniMetaWidget')."</option>";
                 }
                 $adminlinks=minmeta_adminliks(); 
                 foreach ($adminlinks as $menu) {
                  $output="";
                  foreach ($menu as $submenu) {
                    if(current_user_can($submenu[1]) and is_array($submenu) and in_array($submenu[2],$options[$number]['adminlinks'])) {
                      if ($options[$number]['useselectbox']) {
                       $output.= "<option value=\"".get_bloginfo('wpurl')."/wp-admin/".$submenu[2]."\" class=\"minimeta-adminlinks\">".$submenu[0]."</option>";
                      } else {
                       $output.= "<li class=\"minimeta-adminlinks\"><a href=\"".get_bloginfo('wpurl')."/wp-admin/".$submenu[2]."\" title=\"".$submenu[0]."\" class=\"minimeta-adminlinks\">".$submenu[0]."</a></li>";
                      }
                    }
                  }
                  if (!empty($output) and !$options[$number]['notopics']) {
                    if ($options[$number]['useselectbox']) {
                        echo "<optgroup label=\"".$menu['menu']."\" class=\"minimeta-adminlinks\">";
                    } else {
                        echo "<li class=\"minimeta-adminlinks_menu\">".$menu['menu']."<ul class=\"minimeta-adminlinks\">";
                    }
                   }     
                   echo $output; //Put the Admin Links out
                   if (!empty($output) and !$options[$number]['notopics']) {
                    if ($options[$number]['useselectbox']) {
                        echo "</optgroup>";
                    } else {
                            echo "</ul></li>";
                    }
                   }
                  }

                  if ($options[$number]['useselectbox'])
                    echo "</select>";
                    echo "<ul>";
                }
         } else {
			echo $args['before_title'] . $options[$number]['title']. $args['after_title'];
            if($options[$number]['login']=='form') {?>
				<form name="loginform" id="loginform-<?php echo $number; ?>" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
					<p>
                        <label><?php _e('Username:') ?><br />
                        <input type="text" name="log" id="user_login" class="input" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" size="20" tabindex="10" /></label>
                    </p>
                    <p>
                        <label><?php _e('Password:') ?><br />
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
                    </p>
                    <?php do_action('login_form'); ?>
                    <?php if($options[$number]['rememberme']) {?><p><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> <?php _e('Remember me'); ?></label></p><?php } ?>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Log in'); ?>" tabindex="100" />
                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                        <?php if($options[$number]['testcookie']) {?><input type="hidden" name="testcookie" value="1" /><?php } ?>
                    </p>
				</form><?php
			}
            
            echo "<ul>";
            if($options[$number]['login']=='link') echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=login&amp;redirect_to=".$_SERVER['REQUEST_URI']."\" title=\"".__('Login')."\" class=\"minimeta-login\">".__('Login')."</a></li>";
			if($options[$number]['lostpwlink']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=lostpassword\" title=\"".__('Password Lost and Found')."\" class=\"minimeta-lostpw\">".__('Lost your password?')."</a></li>";
			if($options[$number]['registerlink']) wp_register();
		} 

		if($options[$number]['rsslink']) echo "<li><a href=\"".get_bloginfo('rss2_url')."\" title=\"".attribute_escape(__('Syndicate this site using RSS 2.0'))."\" class=\"minimeta-rss\">".__('Entries <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options[$number]['rsscommentlink']) echo "<li><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".attribute_escape(__('The latest comments to all posts in RSS'))."\" class=\"minimeta-commentsrss\">".__('Comments <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options[$number]['wordpresslink']) echo "<li><a href=\"http://wordpress.org/\" title=\"".attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\" class=\"minimeta-wporg\">WordPress.org</a></li>";
		if($options[$number]['showwpmeta']) wp_meta();
		echo "</ul>";
		echo $after_widget;
        }
}
			
function widget_minimeta_control($widget_args = 1) {
   if (K2_USING_SBM) {
    $number=1; //SBM dont need numbers set it to 1
    $options = sbm_get_option('widget_minimeta'); //load Options
    if ( $_POST['widget-minimeta'][$number]['login']) {
		$options['login'] = strip_tags(stripslashes($_POST['widget-minimeta'][$number]['login']));
		$options['logout'] = isset($_POST['widget-minimeta'][$number]['logout']);
        $options['registerlink'] = isset($_POST['widget-minimeta'][$number]['registerlink']);
        $options['testcookie'] = isset($_POST['widget-minimeta'][$number]['testcookie']);
        $options['seiteadmin'] = isset($_POST['widget-minimeta'][$number]['seiteadmin']);
        $options['rememberme'] = isset($_POST['widget-minimeta'][$number]['rememberme']);
		$options['rsslink'] = isset($_POST['widget-minimeta'][$number]['rsslink']);
		$options['rsscommentlink'] = isset($_POST['widget-minimeta'][$number]['rsscommentlink']);
		$options['wordpresslink'] = isset($_POST['widget-minimeta'][$number]['wordpresslink']);
		$options['lostpwlink'] = isset($_POST['widget-minimeta'][$number]['lostpwlink']);
		$options['profilelink'] = isset($_POST['widget-minimeta'][$number]['profilelink']);
        $options['showwpmeta'] = isset($_POST['widget-minimeta'][$number]['showwpmeta']);
        $options['displayidentity'] = isset($_POST['widget-minimeta'][$number]['displayidentity']);
        $options['useselectbox'] = isset($_POST['widget-minimeta'][$number]['useselectbox']);          
        $options['notopics'] = isset($_POST['widget-minimeta'][$number]['notopics']); 
        unset($adminlinks);
        if (strip_tags(stripslashes($_POST['widget-minimeta'][$number]['adminlinks'][0]))!="") {
            for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['adminlinks']);$i++) {
                  $options['adminlinks'][$i] = strip_tags(stripslashes($_POST['widget-minimeta'][$number]['adminlinks'][$i]));
            }
        }
        sbm_update_option('widget_minimeta', $options); //save Options
    } 
    //make settings
    if (empty($options['login'])) {
        $loginLink='checked="checked"';
        $loginForm='';
        $loginOff='';
        $logout='checked="checked"';
        $registerlink='checked="checked"';
        $testcookie='checked="checked"';
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
    } else {
		if (isset($options['login'])) { 
			$options['login'] = htmlspecialchars($options['login'], ENT_QUOTES);
			$loginLink = $options['login'] == 'link' ? 'checked="checked"' : '';
			$loginForm = $options['login'] == 'form' ? 'checked="checked"' : '';
			$loginOff = $options['login'] == 'off' ? 'checked="checked"' : '';
		} 
		$logout = $options['logout'] ? 'checked="checked"' : '';
        $registerlink = $options['registerlink'] ? 'checked="checked"' : '';
        $testcookie = $options['testcookie'] ? 'checked="checked"' : '';
        $seiteadmin = $options['seiteadmin'] ? 'checked="checked"' : '';
		$rememberme = $options['rememberme'] ? 'checked="checked"' : '';
		$rsslink = $options['rsslink'] ? 'checked="checked"' : '';
		$rsscommentlink = $options['rsscommentlink'] ? 'checked="checked"' : '';
		$wordpresslink = $options['wordpresslink'] ? 'checked="checked"' : '';
		$lostpwlink = $options['lostpwlink'] ? 'checked="checked"' : '';
		$profilelink= $options['profilelink'] ? 'checked="checked"' : '';
        $showwpmeta = $options['showwpmeta'] ? 'checked="checked"' : '';
        $displayidentity = $options['displayidentity'] ? 'checked="checked"' : '';
        $useselectbox = $options['useselectbox'] ? 'checked="checked"' : '';
        $notopics = $options['notopics'] ? 'checked="checked"' : '';
        $options[$number]['adminlinks']=$options['adminlinks'];
    }
   } else { // WP-Widgets
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
				unset($options[$widget_number]);
			}
		}

		foreach ( (array) $_POST['widget-minimeta'] as $widget_number => $widget_minmeta ) {
			// compile data from $widget_minmeta
			$options[$widget_number]['title'] = strip_tags(stripslashes($widget_minmeta['title']));
			$options[$widget_number]['login'] = strip_tags(stripslashes($widget_minmeta['login']));
			$options[$widget_number]['logout'] = isset($widget_minmeta['logout']);
            $options[$widget_number]['registerlink'] = isset($widget_minmeta['registerlink']);
            $options[$widget_number]['testcookie'] = isset($widget_minmeta['testcookie']);
            $options[$widget_number]['seiteadmin'] = isset($widget_minmeta['seiteadmin']);
			$options[$widget_number]['rememberme'] = isset($widget_minmeta['rememberme']);
			$options[$widget_number]['rsslink'] = isset($widget_minmeta['rsslink']);
			$options[$widget_number]['rsscommentlink'] = isset($widget_minmeta['rsscommentlink']);
			$options[$widget_number]['wordpresslink'] = isset($widget_minmeta['wordpresslink']);
			$options[$widget_number]['lostpwlink'] = isset($widget_minmeta['lostpwlink']);
			$options[$widget_number]['profilelink'] = isset($widget_minmeta['profilelink']);
            $options[$widget_number]['showwpmeta'] = isset($widget_minmeta['showwpmeta']);
            $options[$widget_number]['displayidentity'] = isset($widget_minmeta['displayidentity']);
            $options[$widget_number]['useselectbox'] = isset($widget_minmeta['useselectbox']);          
            $options[$widget_number]['notopics'] = isset($widget_minmeta['notopics']); 
            unset($adminlinks);
            if (strip_tags(stripslashes($_POST['widget-minimeta'][$widget_number]['adminlinks'][0]))!="") {
             for ($i=0;$i<sizeof($_POST['widget-minimeta'][$widget_number]['adminlinks']);$i++) {
              $options[$widget_number]['adminlinks'][$i] = strip_tags(stripslashes($_POST['widget-minimeta'][$widget_number]['adminlinks'][$i]));
             }
            }        
		}

		update_option('widget_minimeta', $options);
		$updated = true; // So that we don't go through this more than once
	}


	// Here we echo out the form
	if ( -1 == $number ) { // We echo out a template for a form which can be converted to a specific form later via JS
		$title = __('Meta');
        $loginLink='checked="checked"';
        $loginForm='';
        $loginOff='';
        $logout='checked="checked"';
        $registerlink='checked="checked"';
        $testcookie='';
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
		if (isset($options[$number]['login'])) { 
			$options[$number]['login'] = htmlspecialchars($options[$number]['login'], ENT_QUOTES);
			$loginLink = $options[$number]['login'] == 'link' ? 'checked="checked"' : '';
			$loginForm = $options[$number]['login'] == 'form' ? 'checked="checked"' : '';
			$loginOff = $options[$number]['login'] == 'off' ? 'checked="checked"' : '';
		} 
		$logout = $options[$number]['logout'] ? 'checked="checked"' : '';
        $registerlink = $options[$number]['registerlink'] ? 'checked="checked"' : '';
        $testcookie = $options[$number]['testcookie'] ? 'checked="checked"' : '';
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
  	}
   }
	// The form has inputs with names like widget-minimeta[$number][something] so that all data for that instance of
	// the widget are stored in one $_POST variable: $_POST['widget-minimeta'][$number]
   
		//displaying options
		if (!K2_USING_SBM) {?><p><label for="minimeta-title-<?php echo $number; ?>"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" /></label></p><?php } ?>
		<table style="width:100%;border:none"><tr><td valign="top" style="text-align:left;">
        <span style="font-weight:bold;"><?php _e('Show when logged out:','MiniMetaWidget');?></span><br />
         <label for="minimeta-login-<?php echo $number; ?>"><?php _e('Login Type:','MiniMetaWidget');?><br /><input type="radio" name="widget-minimeta[<?php echo $number; ?>][login]" id="minimeta-login-link-<?php echo $number; ?>" value="link" <?php echo $loginLink; ?> />&nbsp;<?php _e('Link','MiniMetaWidget');?>&nbsp;&nbsp;<input type="radio" name="widget-minimeta[<?php echo $number; ?>][login]" id="minimeta-login-form-<?php echo $number; ?>" value="form" <?php echo $loginForm; ?> />&nbsp;<?php _e('Form','MiniMetaWidget');?>&nbsp;&nbsp;<input type="radio" name="widget-minimeta[<?php echo $number; ?>][login]" id="minimeta-login-off-<?php echo $number; ?>" value="off" <?php echo $loginOff; ?> />&nbsp;<?php _e('Off','MiniMetaWidget');?>&nbsp</label><br />
         <label for="minimeta-testcookie-<?php echo $number; ?>" title="<?php _e('Enable WordPress Cookie Test for login Form','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo $testcookie; ?> id="minimeta-testcookie-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][testcookie]" />&nbsp;<?php _e('Enable Cookie Test','MiniMetaWidget');?></label><br />
         <label for="minimeta-rememberme-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $rememberme; ?> id="minimeta-rememberme-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rememberme]" />&nbsp;<?php _e('Remember me');?></label><br />
		 <label for="minimeta-lostpwlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $lostpwlink; ?> id="minimeta-lostpwlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][lostpwlink]" />&nbsp;<?php _e('Lost your password?');?></label><br />
		 <label for="minimeta-registerlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $registerlink; ?> id="minimeta-registerlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][registerlink]" />&nbsp;<?php _e('Register');?></label><br />
        <br />
        <span style="font-weight:bold;"><?php _e('Show allways:','MiniMetaWidget');?></span><br />
		 <label for="minimeta-rsslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $rsslink; ?> id="minimeta-rsslink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rsslink]" />&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-rsscommentlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $rsscommentlink; ?> id="minimeta-rsscommentlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rsscommentlink]" />&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-wordpresslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $wordpresslink; ?> id="minimeta-wordpresslink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][wordpresslink]" />&nbsp;<?php _e('Link to WordPress.org','MiniMetaWidget');?></label><br />
		 <label for="minimeta-showwpmeta-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $showwpmeta; ?> id="minimeta-showwpmeta-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][showwpmeta]" />&nbsp;<?php _e('wp_meta Plugin hooks','MiniMetaWidget');?></label><br />
        </td><td style="text-align:right;">
        <span style="font-weight:bold;"><?php _e('Show when logged in:','MiniMetaWidget');?></span><br />
         <label for="minimeta-logout-<?php echo $number; ?>"><?php _e('Logout');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $logout; ?> id="minimeta-logout-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][logout]" /></label><br />
         <label for="minimeta-seiteadmin-<?php echo $number; ?>"><?php _e('Site Admin');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $seiteadmin; ?> id="minimeta-seiteadmin-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][seiteadmin]" /></label><br />
		 <label for="minimeta-displayidentity-<?php echo $number; ?>"><?php _e('Disply user Identity as title','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $displayidentity; ?> id="minimeta-displayidentity-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][displayidentity]" /></label><br />
         <label for="minimeta-profilelink-<?php echo $number; ?>"><?php _e('Link to Your Profile in title','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $profilelink; ?> id="minimeta-profilelink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][profilelink]" /></label><br />
         <span style="font-style:italic;"><?php _e('Admin links:','MiniMetaWidget');?></span><br />
         <label for="minimeta-useselectbox-<?php echo $number; ?>" title="<?php _e('Use Select Box for Admin Links','MiniMetaWidget');?>"><?php _e('Use Select Box','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $useselectbox; ?> id="minimeta-useselectbox-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][useselectbox]" /></label><br />
         <label for="minimeta-notopics-<?php echo $number; ?>" title="<?php _e('Do not show Admin Links topics','MiniMetaWidget');?>"><?php _e('No Topics','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $notopics; ?> id="minimeta-notopics-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][notopics]" /></label><br />
         <label for="minimeta-adminlinks-<?php echo $number; ?>" title="<?php _e('Admin Links Selection','MiniMetaWidget');?>"><?php _e('Select Admin Links:','MiniMetaWidget');?> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-adminlinks-<?php echo $number; ?>'),true)" style="font-size:9px;"><?php _e('All'); ?></a> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-adminlinks-<?php echo $number; ?>'),false)" style="font-size:9px;"><?php _e('None'); ?></a><br />
         <select class="select" type="select" tabindex="95" size="7" name="widget-minimeta[<?php echo $number; ?>][adminlinks][]" id="minimeta-adminlinks-<?php echo $number; ?>" multiple="multiple">
         <?PHP
            $adminlinks=minmeta_adminliks();
            foreach ($adminlinks as $menu) {
             echo "<optgroup label=\"".$menu['menu']."\">";
             foreach ($menu as $submenu) {
              if (is_array($submenu)) {
               $checkadminlinks="";
               if (in_array($submenu[2],(array)$options[$number]['adminlinks'])) $checkadminlinks="selected=\"selected\"";
               echo "<option value=\"".$submenu[2]."\" ".$checkadminlinks.">".$submenu[0]."</option>";
              }
             }
             echo "</optgroup>";
            }        
        ?>  
         </select></label><br />
        </td></tr></table>
        <?PHP if (!K2_USING_SBM) {?><input type="hidden" id="minimeta-submit-<?php echo $number; ?>" name="minimeta-submit-<?php echo $number; ?>" value="1" /><?php } ?>
		<?php
	}
    
function widget_minimeta_admin_head() {
    ?>
    <script type="text/javascript">
	 function selectAll_widget_minimeta(selectBox,selectAll) {
	  for (var i = 0; i < selectBox.options.length; i++) selectBox.options[i].selected = selectAll;
	 }
	</script>    
    <?PHP
}
add_action('admin_head', 'widget_minimeta_admin_head',10);
//add_action('admin_head-themes_page_widgets', 'widget_minimeta_admin_head',10); //the hook don't works.
//add_action('admin_head-themes_page_k2-sbm-manager', 'widget_minimeta_admin_head',10);
    
//WP-Head hooks high Priority
function widget_minimeta_wp_head_login() {
    if (!is_user_logged_in()) {   //copy action login_head to wp-head if login form enabeld for plugin hooks 
        if (K2_USING_SBM) {
            do_action('login_head'); //do action from login had
        } else {
            $options = get_option('widget_minimeta');
            //find out is a ligon form in any MiniMeta Widegt activatet
            if($options[1]['login']=='form' or $options[2]['login']=='form' or $options[3]['login']=='form' or
               $options[4]['login']=='form' or $options[5]['login']=='form' or $options[6]['login']=='form' or
               $options[7]['login']=='form' or $options[8]['login']=='form' or $options[9]['login']=='form') 
                    do_action('login_head'); //do action from login had
        }
    }        
}

//WP-Head hooks low Priority
function widget_minimeta_wp_head() {
    //Set Style sheet
    if (file_exists('wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/custom/minimeta-widget.css')) {
        echo "<link rel=\"stylesheet\" href=\"".get_bloginfo('wpurl')."/wp-content/plugins/".dirname(plugin_basename(__FILE__))."/custom/minimeta-widget.css\" type=\"text/css\" media=\"screen\" />";
    } elseif (file_exists('wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/minimeta-widget.css')) {
        echo "<link rel=\"stylesheet\" href=\"".get_bloginfo('wpurl')."/wp-content/plugins/".dirname(plugin_basename(__FILE__))."/minimeta-widget.css\" type=\"text/css\" media=\"screen\" />";
    }
}
    
    
function widget_minimeta_register() { 
 	// This registers our widget and  widget control form for K2 SBM  
    if (K2_USING_SBM) {
      register_sidebar_module('MiniMeta Widget', 'widget_minimeta');
      register_sidebar_module_control('MiniMeta Widget', 'widget_minimeta_control');
    } else { // This registers our widget and  widget control form for WordPress Widgets
	  if ( !$options = get_option('widget_minimeta') )
		$options = array();
        
      $widget_ops = array('classname' => 'widget_minimeta', 'description' => __('Displaying Meta links, Login Form and Admin Links'));
	  $control_ops = array('width' => 400, 'height' => 340, 'id_base' => 'minimeta');
	  $name = __('MiniMeta Widget');
      // If there are none, we register the widget's existance with a generic template
	  if ( !$options ) {
		wp_register_sidebar_widget( $control_ops['id_base'].'-1', $name, 'widget_minimeta', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( $control_ops['id_base'].'-1', $name, 'widget_minimeta_control', $control_ops, array( 'number' => -1 ) );
	  }
	  foreach ( array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['title']))
			continue;
		$id = $control_ops['id_base']."-".$o; // Never never never translate an id
		wp_register_sidebar_widget($id, $name, 'widget_minimeta', $widget_ops, array( 'number' => $o ));
		wp_register_widget_control($id, $name, 'widget_minimeta_control', $control_ops, array( 'number' => $o ));
	  }
    }
    add_action('wp_head', 'widget_minimeta_wp_head_login',1);
    add_action('wp_head', 'widget_minimeta_wp_head',10);
}

/**
* Deactivate plugin
*
* Function used when this plugin is deactivated in Wordpress.
* Delete all Options
*/
function widget_minnimeta_deactivate() {
    delete_option('widget_minimeta');
}
add_action('deactivate_'.dirname(plugin_basename(__FILE__)).'/minimeta-widget.php','widget_minnimeta_deactivate');
?>
