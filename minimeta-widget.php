<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/protfolio/minimeta/
Description: Mini Version of the WP Meta Widget with different logon types and some additional admin links.
Author: Daniel H&uuml;sken
Version: 3.5.9
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
*/
 
//Display Widget 
function widget_minimeta($args,$widget_args = 1) {
    global $user_identity;	
    extract( $args, EXTR_SKIP );
    
    //load options
    if (K2_LOAD_SBM) {
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
        if ( !isset($options[$number]) )
            return;
    }
    
    //Don´t show Wiget if it have no links
    if ((!is_user_logged_in() and !$options[$number]['loginlink'] and !$options[$number]['loginform'] and !$options[$number]['registerlink'] and !$options[$number]['rememberme'] and !$options[$number]['lostpwlink'] and !$options[$number]['rsslink'] and !$options[$number]['rsscommentlink'] and !$options[$number]['wordpresslink'] and !($options[$number]['showwpmeta'] and has_action('wp_meta')) or
        (is_user_logged_in() and !$options[$number]['logout'] and !$options[$number]['seiteadmin'] and sizeof($options[$number]['adminlinks'])==0 and !$options[$number]['rsslink'] and !$options[$number]['rsscommentlink'] and !$options[$number]['wordpresslink'] and !($options[$number]['showwpmeta'] and has_action('wp_meta'))))) 
            return;
        
	//Shown part of Widget
    echo $before_widget;
        
        if(is_user_logged_in()) {
            if ($options[$number]['displayidentity'] and !empty($user_identity)) $options[$number]['title']=$user_identity;
            if($options[$number]['profilelink'] and current_user_can('read')) {
                echo $before_title ."<a href=\"".admin_url("/profile.php")."\" title=\"".__('Your Profile')."\">". $options[$number]['title'] ."</a>". $after_title; 
            } else {
            echo $before_title . $options[$number]['title'] . $after_title; 
            }
                echo "<ul>"; $endul=true;  
                if($options[$number]['seiteadmin']) echo "<li><a href=\"".admin_url()."\" class=\"minimeta-siteadmin\">".__('Site Admin')."</a></li>";
                if($options[$number]['logout'] and $options[$number]['redirect']) echo "<li><a href=\"".site_url('wp-login.php?action=logout&amp;redirect_to='.$_SERVER['REQUEST_URI'], 'login')."\" class=\"minimeta-logout\">".__('Log out')."</a></li>"; 
                if($options[$number]['logout'] and !$options[$number]['redirect']) echo "<li><a href=\"".site_url('wp-login.php?action=logout', 'login')."\" class=\"minimeta-logout\">".__('Log out')."</a></li>"; 
             
                if (sizeof($options[$number]['adminlinks'])>0) { //show only if a Admin Link is selectesd
                 if ($options[$number]['useselectbox']) {
                    echo "<li class=\"minimeta-adminlinks\"><select class=\"minimeta-adminlinks\" tabindex=\"95\" onchange=\"window.location = this.value\"><option selected=\"selected\">".__('Please select:','MiniMetaWidget')."</option>";
                 }
                 $adminlinks=get_option('widget_minimeta_adminlinks'); 
                 foreach ($adminlinks as $menu) {
                  $output="";
                  foreach ($menu as $submenu) {
                    if(current_user_can($submenu[1]) and is_array($submenu) and in_array(wp_specialchars($submenu[2]),$options[$number]['adminlinks'])) {
                      if ($options[$number]['useselectbox']) {
                       $output.= "<option value=\"".admin_url("/".$submenu[2])."\" class=\"minimeta-adminlinks\">".$submenu[0]."</option>";
                      } else {
                       $output.= "<li class=\"minimeta-adminlinks\"><a href=\"".admin_url("/".$submenu[2])."\" title=\"".$submenu[0]."\" class=\"minimeta-adminlinks\">".$submenu[0]."</a></li>";
                      }
                    }
                  }
                  if (!empty($output) and !$options[$number]['notopics']) {
                    if ($options[$number]['useselectbox']) {
                        echo "<optgroup label=\"".$menu['menu']."\" class=\"minimeta-adminlinks\">".$output."</optgroup>";
                    } else {
                        echo "<li class=\"minimeta-adminlinks_menu\">".$menu['menu']."<ul class=\"minimeta-adminlinks\">".$output."</ul></li>";
                    }
                   } else {
					  echo $output;
				   }    
                  } 
                  if ($options[$number]['useselectbox']) {
                    echo "</select></li>";
                  }
                }
         } else {
			echo $args['before_title'] . $options[$number]['title']. $args['after_title'];
            if($options[$number]['loginform']) {?>
				<form name="loginform" id="loginform" action="<?php echo site_url('wp-login.php', 'login_post') ?>" method="post">
                    <p>
                        <label><?php _e('Username') ?><br />
                        <input type="text" name="log" id="user_login" class="input" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" size="20" tabindex="10" /></label>
                    </p>
                    <p>
                        <label><?php _e('Password') ?><br />
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
                    </p>
                    <?php do_action('login_form'); ?>
                    <?php if($options[$number]['rememberme']) {?><p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> <?php _e('Remember Me'); ?></label></p><?php } ?>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Log in'); ?>" tabindex="100" />
                        <?php if($options[$number]['redirect']) {?><input type="hidden" name="redirect_to" value="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>" /><?php } ?>
                        <?php if($options[$number]['testcookie']) {?><input type="hidden" name="testcookie" value="1" /><?php } ?>
                    </p>
				</form><?php
			}
            $endul=false;
            if ($options[$number]['loginlink'] or $options[$number]['lostpwlink'] or ($options[$number]['registerlink'] and get_option('users_can_register')) or $options[$number]['rsslink'] or $options[$number]['rsscommentlink'] or $options[$number]['wordpresslink'] or ($options[$number]['showwpmeta'] and has_action('wp_meta'))) {
                echo "<ul>";
                $endul=true;
            }
            if($options[$number]['loginlink'] and $options[$number]['redirect']) echo "<li><a href=\"".site_url('wp-login.php?action=login&amp;redirect_to='.$_SERVER['REQUEST_URI'], 'login')."\" class=\"minimeta-login\">".__('Log in')."</a></li>";
			if($options[$number]['loginlink'] and !$options[$number]['redirect']) echo "<li><a href=\"".site_url('wp-login.php', 'login')."\" class=\"minimeta-login\">".__('Log in')."</a></li>";
            if($options[$number]['lostpwlink']) echo "<li><a href=\"".site_url('wp-login.php?action=lostpassword', 'login')."\" title=\"".__('Password Lost and Found')."\" class=\"minimeta-lostpw\">".__('Lost your password?')."</a></li>";
			if($options[$number]['registerlink'] and get_option('users_can_register')) echo "<li><a href=\"".site_url('wp-login.php?action=register', 'login')."\" class=\"minimeta-register\">" . __('Register') . "</a></li>";
		} 

		if($options[$number]['rsslink']) echo "<li><a href=\"".get_bloginfo('rss2_url')."\" title=\"".attribute_escape(__('Syndicate this site using RSS 2.0'))."\" class=\"minimeta-rss\">".__('Entries <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options[$number]['rsscommentlink']) echo "<li><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".attribute_escape(__('The latest comments to all posts in RSS'))."\" class=\"minimeta-commentsrss\">".__('Comments <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options[$number]['wordpresslink']) echo "<li><a href=\"http://wordpress.org/\" title=\"".attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\" class=\"minimeta-wporg\">WordPress.org</a></li>";
		if($options[$number]['showwpmeta'] and has_action('wp_meta')) do_action('wp_meta');
		if ($endul) 
            echo "</ul>";
		echo $after_widget;
}
			
function widget_minimeta_control($widget_args = 1) {
   if (K2_LOAD_SBM) {
    $number=1; //SBM dont need numbers set it to 1
    $options = sbm_get_option('widget_minimeta'); //load Options
    if ( $_POST['widget-minimeta'][$number]) {
		$options['loginlink'] = isset($_POST['widget-minimeta'][$number]['loginlink']);
		$options['loginform'] = isset($_POST['widget-minimeta'][$number]['loginform']);
        $options['logout'] = isset($_POST['widget-minimeta'][$number]['logout']);
        $options['registerlink'] = isset($_POST['widget-minimeta'][$number]['registerlink']);
        $options['testcookie'] = isset($_POST['widget-minimeta'][$number]['testcookie']);
        $options['redirect'] = isset($_POST['widget-minimeta'][$number]['redirect']);
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
        unset($options['adminlinks']);
        for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['adminlinks']);$i++) {
            $options['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][$number]['adminlinks'][$i]);
        }
        sbm_update_option('widget_minimeta', $options); //save Options
    } 
    //make settings
    if (!isset($options['loginlink'])) {
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
    } else {
		$loginform = $options['loginform'] ? 'checked="checked"' : '';
		$loginlink = $options['loginlink'] ? 'checked="checked"' : '';
		$logout = $options['logout'] ? 'checked="checked"' : '';
        $registerlink = $options['registerlink'] ? 'checked="checked"' : '';
        $testcookie = $options['testcookie'] ? 'checked="checked"' : '';
        $redirect = $options['redirect'] ? 'checked="checked"' : '';
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
        $adminlinksset=$options['adminlinks'];
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
   }
	// The form has inputs with names like widget-minimeta[$number][something] so that all data for that instance of
	// the widget are stored in one $_POST variable: $_POST['widget-minimeta'][$number]
   
		//displaying options
		if (!K2_LOAD_SBM) {?><label for="minimeta-title-<?php echo $number; ?>"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" /></label><?php } ?>
		<table style="width:100%;border:neone"><tr><td style="text-align:left;vertical-align:top;">
        <span style="font-weight:bold;"><?php _e('Show when logged out:','MiniMetaWidget');?></span><br />
         <label for="minimeta-loginlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $loginlink; ?> id="minimeta-loginlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][loginlink]" />&nbsp;<?php _e('Login Link','MiniMetaWidget');?></label><br />
         <label for="minimeta-loginform-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $loginform; ?> id="minimeta-loginform-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][loginform]" />&nbsp;<?php _e('Login Form','MiniMetaWidget');?></label><br />
         <label for="minimeta-testcookie-<?php echo $number; ?>" title="<?php _e('Enable WordPress Cookie Test for login Form','MiniMetaWidget');?>">&nbsp;&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo $testcookie; ?> id="minimeta-testcookie-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][testcookie]" />&nbsp;<?php _e('Enable Cookie Test','MiniMetaWidget');?></label><br />
         <label for="minimeta-rememberme-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $rememberme; ?> id="minimeta-rememberme-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rememberme]" />&nbsp;<?php _e('Remember me');?></label><br />
		 <label for="minimeta-lostpwlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $lostpwlink; ?> id="minimeta-lostpwlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][lostpwlink]" />&nbsp;<?php _e('Lost your password?');?></label><br />
		 <label for="minimeta-registerlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $registerlink; ?> id="minimeta-registerlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][registerlink]" />&nbsp;<?php _e('Register');?></label><br />
        <br />
        <span style="font-weight:bold;"><?php _e('Show allways:','MiniMetaWidget');?></span><br />
		 <label for="minimeta-redirect-<?php echo $number; ?>" title="<?php _e('Enable WordPress redirect function on Login/out','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo $redirect; ?> id="minimeta-redirect-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][redirect]" />&nbsp;<?php _e('Enable Login/out Redirect','MiniMetaWidget');?></label><br />
         <label for="minimeta-rsslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $rsslink; ?> id="minimeta-rsslink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rsslink]" />&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-rsscommentlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $rsscommentlink; ?> id="minimeta-rsscommentlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rsscommentlink]" />&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-wordpresslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $wordpresslink; ?> id="minimeta-wordpresslink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][wordpresslink]" />&nbsp;<?php _e('Link to WordPress.org','MiniMetaWidget');?></label><br />
		 <label for="minimeta-showwpmeta-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $showwpmeta; ?> id="minimeta-showwpmeta-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][showwpmeta]" />&nbsp;<?php _e('wp_meta Plugin hooks','MiniMetaWidget');?></label><br />
        </td><td style="text-align:right;vertical-align:top;">
        <span style="font-weight:bold;"><?php _e('Show when logged in:','MiniMetaWidget');?></span><br />
         <label for="minimeta-logout-<?php echo $number; ?>"><?php _e('Logout');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $logout; ?> id="minimeta-logout-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][logout]" /></label><br />
         <label for="minimeta-seiteadmin-<?php echo $number; ?>"><?php _e('Site Admin');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $seiteadmin; ?> id="minimeta-seiteadmin-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][seiteadmin]" /></label><br />
		 <label for="minimeta-displayidentity-<?php echo $number; ?>"><?php _e('Disply user Identity as title','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $displayidentity; ?> id="minimeta-displayidentity-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][displayidentity]" /></label><br />
         <label for="minimeta-profilelink-<?php echo $number; ?>"><?php _e('Link to Your Profile in title','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $profilelink; ?> id="minimeta-profilelink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][profilelink]" /></label><br />
         <span style="font-style:italic;"><?php _e('Admin links:','MiniMetaWidget');?></span><br />
         <label for="minimeta-useselectbox-<?php echo $number; ?>" title="<?php _e('Use Select Box for Admin Links','MiniMetaWidget');?>"><?php _e('Use Select Box','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $useselectbox; ?> id="minimeta-useselectbox-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][useselectbox]" /></label><br />
         <label for="minimeta-notopics-<?php echo $number; ?>" title="<?php _e('Do not show Admin Links topics','MiniMetaWidget');?>"><?php _e('No Topics','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $notopics; ?> id="minimeta-notopics-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][notopics]" /></label><br />
         <label for="minimeta-adminlinks-<?php echo $number; ?>" title="<?php _e('Admin Links Selection','MiniMetaWidget');?>"><?php _e('Select Admin Links:','MiniMetaWidget');?> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-adminlinks-<?php echo $number; ?>'),true)" style="font-size:9px;"><?php _e('All'); ?></a> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-adminlinks-<?php echo $number; ?>'),false)" style="font-size:9px;"><?php _e('None'); ?></a><br />
         <select class="select" type="select" style="height:120px;" name="widget-minimeta[<?php echo $number; ?>][adminlinks][]" id="minimeta-adminlinks-<?php echo $number; ?>" multiple="multiple">
         <?PHP
            $adminlinks=get_option('widget_minimeta_adminlinks');
            foreach ($adminlinks as $menu) {
             echo "<optgroup label=\"".$menu['menu']."\">";
             foreach ($menu as $submenu) {
              if (is_array($submenu)) {
               $checkadminlinks="";
               if (in_array(wp_specialchars($submenu[2]),(array)$adminlinksset)) $checkadminlinks="selected=\"selected\"";
               echo "<option value=\"".$submenu[2]."\" ".$checkadminlinks.">".$submenu[0]."</option>";
              }
             }
             echo "</optgroup>";
            }        
        ?>  
         </select></label><br />
        </td></tr></table>
        <?PHP if (!K2_LOAD_SBM) {?><input type="hidden" id="minimeta-submit-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][submit]" value="1" /><?php } ?>
		<?php
}

//JS Admin Header for All/None Selection
function widget_minimeta_admin_head() { 
    ?>
    <script type="text/javascript">
	 function selectAll_widget_minimeta(selectBox,selectAll) {
	  for (var i = 0; i < selectBox.options.length; i++) selectBox.options[i].selected = selectAll;
	 }
	</script>    
    <?PHP
}
    
//WP-Head hooks high Priority
function widget_minimeta_wp_head_login() {
    if (!is_user_logged_in()) {   //copy action login_head to wp-head if login form enabeld for plugin hooks 
        if (K2_LOAD_SBM) {
            do_action('login_head'); //do action from login had
        } else {
            $options = get_option('widget_minimeta');
            //find out is a ligon form in any MiniMeta Widegt activatet
            foreach ( (array) $options as $widget_number => $widget_minmeta ) {
                if($widget_minmeta['loginform']) 
                    do_action('login_head'); //do action from login had
            }
        }
    }        
}

//WP-Head hooks low Priority
function widget_minimeta_wp_head() {
    //Set Style sheet
    if (file_exists(WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)).'/custom/minimeta-widget.css')) {
        echo "<link rel=\"stylesheet\" href=\"".plugins_url("/".dirname(plugin_basename(__FILE__))."/custom/minimeta-widget.css")."\" type=\"text/css\" media=\"screen\" />";
    } elseif (file_exists(WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)).'/minimeta-widget.css')) {
        echo "<link rel=\"stylesheet\" href=\"".plugins_url("/".dirname(plugin_basename(__FILE__))."/minimeta-widget.css")."\" type=\"text/css\" media=\"screen\" />";
    }
}

//function to generate admin links    
function widget_minimeta_generate_adminlinks() { 
 global $menu,$submenu,$pagenow;
 if (!(current_user_can(10) and ("widgets.php"==$pagenow or (K2_LOAD_SBM and "themes.php"==$pagenow)))) 
    return;   
 //let orginal Variables unchanged
 $tempmenu=$menu;
 $tempsubmenu=$submenu;
 //scan the menu
 foreach ( $tempmenu as $key => $item ) {
    if ($item[2]=="edit-comments.php") //Overwrite for Comments menu without number since wp 2.5
        $item[0] = __('Comments');
	if ($item[2]=="plugins.php") //Overwrite for Plugins menu without number since wp 2.6
        $item[0] = __('Plugins');
    $adminlinks[$key]['menu']=wp_specialchars(strip_tags($item[0])); //write Menues
    if (is_array($tempsubmenu[$item[2]])) { //look if submenu existing to crate submenu on men if they downt exists
        $menulink=false; unset($lowestkey);
        foreach ($tempsubmenu[$item[2]] as $keysub => $itemsub) { // Find submenu wisout an existing menu link
            if(!isset($lowestkey) or $lowestkey>$keysub) $lowestkey=$keysub;
            if ($itemsub[2]==$item[2])
                $menulink=true;
        }
        if (!$menulink) {
            $lowestkey--;
            $tempsubmenu[$item[2]][$lowestkey] = array($item[0], $item[1], $item[2]); //create submenu
            ksort($tempsubmenu[$item[2]]);
        }
    } else {
        $tempsubmenu[$item[2]][0] = array($item[0], $item[1], $item[2]); //create submenu
    }
    foreach ($tempsubmenu[$item[2]] as $keysub => $itemsub) { //Crate submenus and links
        $adminlinks[$key][$keysub][0]=wp_specialchars(strip_tags($itemsub[0]));
        $adminlinks[$key][$keysub][1]=$itemsub[1];
        $menu_hook = get_plugin_page_hook($itemsub[2], $item[2]);       
        if (file_exists(ABSPATH . PLUGINDIR . "/".$itemsub[2]) || ! empty($menu_hook)) {
            if (!in_array($item[2],array('index.php','page-new.php','edit.php','themes.php','edit-comments.php','options-general.php','plugins.php','users.php','profile.php')) )
                $adminlinks[$key][$keysub][2]= "admin.php?page=".$itemsub[2];
            else
                $adminlinks[$key][$keysub][2]= $item[2]."?page=".$itemsub[2];
        } else {
            $adminlinks[$key][$keysub][2]= $itemsub[2];
        }
        if ($adminlinks[$key][$keysub][2]=="edit-comments.php?page=akismet-admin") //Overwrite for Akismet Spam menu without number
            $adminlinks[$key][$keysub][0] = __('Akismet Spam');
    }   
 }
 update_option('widget_minimeta_adminlinks', $adminlinks);
}

// This registers our widget and  widget control for K2 SBM 
function widget_minimeta_register_K2() {  
      register_sidebar_module('MiniMeta Widget', 'widget_minimeta');
      register_sidebar_module_control('MiniMeta Widget', 'widget_minimeta_control');
}

// This registers our widget and  widget control for WP
function widget_minimeta_register_WP() {
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
		wp_register_sidebar_widget( $id, $name, 'widget_minimeta', $widget_ops, array( 'number' => $o ) );
		wp_register_widget_control( $id, $name, 'widget_minimeta_control', $control_ops, array( 'number' => $o ) );
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$registered ) {
		wp_register_sidebar_widget( $control_ops['id_base'].'-1', $name, 'widget_minimeta', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( $control_ops['id_base'].'-1', $name, 'widget_minimeta_control', $control_ops, array( 'number' => -1 ) );
	}
}

// add all action and so on only if plugin loaded.
function widget_minimeta_init() {
    global $wp_version,$pagenow;

    if (version_compare($wp_version, '2.5', '<')) { // Let only Activate on WordPress Version 2.5 or heiger
		//Loads language files
		load_plugin_textdomain('MiniMetaWidget', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/lang');	
        add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, MiniMeta Widget works only under WordPress 2.5 or higher',"MiniMetaWidget") . '</strong></p></div>\';'));
	    return;
    } elseif (version_compare($wp_version, '2.6', '<')) {   // Pre-2.6 compatibility
        define( 'WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins' );
	    function site_url($path = '', $scheme = null) { 
			return get_bloginfo('wpurl').'/'.$path;
		}
	    function admin_url($path = '') {
			return get_bloginfo('wpurl').'/wp-admin/'.$path;
		}
	    function plugins_url($path = '') { 
			return get_option('siteurl') . '/wp-content/plugins/'.$path;
		}
		//Loads language files
		load_plugin_textdomain('MiniMetaWidget', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/lang');	
	} else { //hieger than WP 2.6
		//Loads language files
		load_plugin_textdomain('MiniMetaWidget', false, dirname(plugin_basename(__FILE__)).'/lang');		
	}

    // Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if (!function_exists('register_sidebar_widget'))
		return;
    
    //find out if K2 and his SBM is activatet and set K2_LOAD_SBM Konstant if it not set
    if (!defined('K2_LOAD_SBM')) 
            define('K2_LOAD_SBM',false);
	
	//Only add actions and so on if Plugin is Activaded
    if (K2_LOAD_SBM) { //K2 SBM only
        widget_minimeta_register_K2();
        //add_action('admin_head-themes_page_k2-sbm-manager', 'widget_minimeta_admin_head'); //dont work anymore
        if ("themes.php"==$pagenow) add_action('admin_head', 'widget_minimeta_admin_head');
    } else { //WP only
        add_action('widgets_init', 'widget_minimeta_register_WP');
        //add_action('admin_head-themes_page_widgets', 'widget_minimeta_admin_head'); //dont work
        if ("widgets.php"==$pagenow) add_action('admin_head', 'widget_minimeta_admin_head');
    }
    //WP and K2 SBM
    if (has_action('login_head')) add_action('wp_head', 'widget_minimeta_wp_head_login',1);
    add_action('wp_head', 'widget_minimeta_wp_head');
    add_action('admin_init','widget_minimeta_generate_adminlinks',1);
}   
add_action('init', 'widget_minimeta_init',1); //1 must because WP widgets_init don't work

// Deactivate plugin -Delete all Options
function widget_minimeta_deactivate() {
    delete_option('widget_minimeta');
    delete_option('widget_minimeta_adminlinks');
}
register_deactivation_hook(__FILE__,'widget_minimeta_deactivate');
?>
