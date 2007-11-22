<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/protfolio/minimeta/
Description: Mini Verson of the WP Meta Widget with differnt logon types and some additional admin links
Author: Daniel H&uuml;sken
Version: 2.8.0
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
   Version 2.8.0    Better support for K2 SBM
                           Plasing the widget up to 9 times
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
    
    function widget_minimeta($args,$number=1) {
        global $user_identity;	
        //defaults
        $options[$number]= array('login'=>'link','logout' =>'1','profilelinkadmin' => '0','registerlink' =>'1','seiteadmin' =>'1','rememberme' =>'1',
                        'rsslink' =>'1','rsscommentlink' =>'1','wordpresslink' =>'1','lostpwlink' =>'','newpostslink' =>'',
                        'newpageslink' =>'','commentsadminlink' =>'','pluginsadminlink' =>'','usersadminlink' =>'',
                        'showadminhierarchy' =>'','showwpmeta' =>'1','displayidentity'=>'','usewpadminlinks'=>'');
        //load options
        if (get_option('k2sidebarmanager') == '1') {
         $getoptions = sbm_get_option('widget_minimeta');
        } else {
         $getoptions = get_option('widget_minimeta');
        }
        //overwrite def. options with loadet options
        $options[$number]=array_merge($options[$number], (array)$getoptions[$number]);
        //Show a standart Title if empty
        if (empty($options[$number]['title'])) $options[$number]['title']=__('Meta');
        //title compatibility for SBM
        if (!empty($args['title'])) $options[$number]['title']=$args['title'];

		//Shown part of Widget
        echo $args["before_widget"];
        if(is_user_logged_in()) {
            if ($options[$number]['displayidentity'] and !empty($user_identity)) $options[$number]['title']=$user_identity;
            if($options[$number]['profilelink'] and current_user_can('read')) {
                echo $args['before_title'] ."<a href=\"".get_bloginfo('wpurl')."/wp-admin/profile.php\" title=\"".__('Your Profile')."\">". $options[$number]['title'] ."</a>". $args['after_title']; 
            } else {
            echo $args['before_title'] . $options[$number]['title'] . $args['after_title']; 
            }
            //For Wp-Admin Links if supportet
            echo "<ul>";
            if (function_exists(wp_admin_links) and $options[$number]['usewpadminlinks']) { 
                wp_admin_links('','','',true);
            } else  {
                if($options[$number]['seiteadmin']) {wp_register();}
                    if($options[$number]['showadminhierarchy'] and ($options[$number]['newpageslink'] or $options[$number]['newpostslink'] or $options[$number]['profilelink'] or $options[$number]['logout'])) echo "<ul class=\"children\">"; 
                    if($options[$number]['logout']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=logout&amp;redirect_to=".$_SERVER['REQUEST_URI']."\" title=\"".__('Logout')."\">".__('Logout')."</a></li>"; 
                    if($options[$number]['profilelinkadmin']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/profile.php\" title=\"".__('Your Profile')."\">".__('Your Profile')."</a></li>"; 
                    if($options[$number]['newpostslink'] and current_user_can('edit_posts')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/post-new.php\" title=\"".__('Write Post')."\">".__('Write Post')."</a></li>";
                    if($options[$number]['newpageslink'] and current_user_can('edit_pages')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/page-new.php\" title=\"".__('Write Page')."\">".__('Write Page')."</a></li>";			
                        if($options[$number]['showadminhierarchy'] and ($options[$number]['usersadminlink'] or $options[$number]['commentsadminlink'] or $options[$number]['pluginsadminlink'])) echo "<ul class=\"children\">";
                        if($options[$number]['usersadminlink'] and current_user_can('edit_users')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/users.php\" title=\"".__('Users')."\">".__('Users')."</a></li>";
                        if($options[$number]['commentsadminlink'] and current_user_can('edit_posts')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/edit-comments.php\" title=\"".__('Comments')."\">".__('Comments')."</a></li>";
                        if($options[$number]['pluginsadminlink'] and current_user_can('activate_plugins')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/plugins.php\" title=\"".__('Plugins')."\">".__('Plugins')."</a></li>";
                        if($options[$number]['showadminhierarchy'] and ($options[$number]['usersadminlink'] or $options[$number]['commentsadminlink'] or $options[$number]['pluginsadminlink'])) echo "</ul>"; 
                    if($options[$number]['showadminhierarchy'] and ($options[$number]['newpageslink'] or $options[$number]['newpostslink'] or $options[$number]['profilelink'] or $options[$number]['logout'])) echo "</ul>";
            } 
        } else {
			echo $args['before_title'] . $options[$number]['title']. $args['after_title'];
            if($options[$number]['login']=='form') {?>
				<form name="loginform" id="loginform" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
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
                        <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Login'); ?> &raquo;" tabindex="100" />
                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                    </p>
				</form><?php
			}
            echo "<ul>";
			if($options[$number]['login']=='link') echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=login&amp;redirect_to=".$_SERVER['REQUEST_URI']."\" title=\"".__('Login')."\">".__('Login')."</a></li>";
			if($options[$number]['lostpwlink']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=lostpassword\" title=\"".__('Password Lost and Found')."\">".__('Lost your password?')."</a></li>";
			if($options[$number]['registerlink']) wp_register();
		} 

		if($options[$number]['rsslink']) echo "<li><a href=\"".get_bloginfo('rss2_url')."\" title=\"".attribute_escape(__('Syndicate this site using RSS 2.0'))."\">".__('Entries <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options[$number]['rsscommentlink']) echo "<li><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".attribute_escape(__('The latest comments to all posts in RSS'))."\">".__('Comments <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options[$number]['wordpresslink']) echo "<li><a href=\"http://wordpress.org/\" title=\"".attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\">WordPress.org</a></li>";
		if($options[$number]['showwpmeta']) wp_meta();
		echo "</ul>";
		echo $args['after_widget'];
	}
			
	function widget_minimeta_control($number=1) {
		//load options
        if (get_option('k2sidebarmanager') == '1') {
         $options[$number] = $newoptions[$number] = sbm_get_option('widget_minimeta');
        } else {
         $options = $newoptions = get_option('widget_minimeta');
        }
        
        //get post options
		if ( $_POST['minimeta-submit-'.$number] or $_POST['minimeta-login-'.$number]) {
            $newoptions[$number]['title'] = strip_tags(stripslashes($_POST['minimeta-title-'.$number]));
			$newoptions[$number]['login'] = strip_tags(stripslashes($_POST['minimeta-login-'.$number]));
			$newoptions[$number]['logout'] = isset($_POST['minimeta-logout-'.$number]);
            $newoptions[$number]['registerlink'] = isset($_POST['minimeta-registerlink-'.$number]);
            $newoptions[$number]['seiteadmin'] = isset($_POST['minimeta-seiteadmin-'.$number]);
			$newoptions[$number]['rememberme'] = isset($_POST['minimeta-rememberme-'.$number]);
			$newoptions[$number]['rsslink'] = isset($_POST['minimeta-rsslink-'.$number]);
			$newoptions[$number]['rsscommentlink'] = isset($_POST['minimeta-rsscommentlink-'.$number]);
			$newoptions[$number]['wordpresslink'] = isset($_POST['minimeta-wordpresslink-'.$number]);
			$newoptions[$number]['lostpwlink'] = isset($_POST['minimeta-lostpwlink-'.$number]);
			$newoptions[$number]['newpostslink'] = isset($_POST['minimeta-newpostslink-'.$number]);
			$newoptions[$number]['newpageslink'] = isset($_POST['minimeta-newpageslink-'.$number]);
            $newoptions[$number]['pluginsadminlink'] = isset($_POST['minimeta-pluginsadminlink-'.$number]);
            $newoptions[$number]['commentsadminlink'] = isset($_POST['minimeta-commentsadminlink-'.$number]);
            $newoptions[$number]['usersadminlink'] = isset($_POST['minimeta-usersadminlink-'.$number]);
			$newoptions[$number]['profilelink'] = isset($_POST['minimeta-profilelink-'.$number]);
            $newoptions[$number]['profilelinkadmin'] = isset($_POST['minimeta-profilelinkadmin-'.$number]);
			$newoptions[$number]['showadminhierarchy'] = isset($_POST['minimeta-showadminhierarchy-'.$number]);
            $newoptions[$number]['showwpmeta'] = isset($_POST['minimeta-showwpmeta-'.$number]);
            $newoptions[$number]['displayidentity'] = isset($_POST['minimeta-displayidentity-'.$number]);
            $newoptions[$number]['usewpadminlinks'] = isset($_POST['minimeta-usewpadminlinks-'.$number]);         
		}
       
		//safe options only when changed 
        if (get_option('k2sidebarmanager') == '1') {
         if ( $options[$number] != $newoptions[$number] ) {
			 $options[$number] = $newoptions[$number];
             //Update K2 SBM Options
             sbm_update_option('widget_minimeta', $options[$number]);
         }
        } else {
         if ( $options != $newoptions ) {
			  $options = $newoptions;
              update_option('widget_minimeta', $options);
		 }
        }
		//def. options
        $checkoptions[$number]= array('title'=>__('Meta'),'loginLink'=>'checked="checked"','loginForm'=>'','loginOff'=>'','logout' =>'checked="checked"','registerlink' =>'checked="checked"','seiteadmin' =>'checked="checked"','rememberme' =>'checked="checked"',
                'rsslink' =>'checked="checked"','rsscommentlink' =>'checked="checked"','wordpresslink' =>'checked="checked"','lostpwlink' =>'','newpostslink' =>'',
                'newpageslink' =>'','commentsadminlink' =>'','pluginsadminlink' =>'','usersadminlink' =>'','profilelinkadmin' => '',
                'showadminhierarchy' =>'','showwpmeta' =>'checked="checked"','displayidentity'=>'','usewpadminlinks'=>'');

		//set checked for aktivatet options
		if (!K2_USING_SBM) $checkoptions[$number]['title'] = attribute_escape($options[$number]['title']);
		if (isset($options[$number]['login'])) { 
			$checkoptions[$number]['login'] = htmlspecialchars($options[$number]['login'], ENT_QUOTES);
			$checkoptions[$number]['loginLink'] = $checkoptions[$number]['login'] == 'link' ? 'checked="checked"' : '';
			$checkoptions[$number]['loginForm'] = $checkoptions[$number]['login'] == 'form' ? 'checked="checked"' : '';
			$checkoptions[$number]['loginOff'] = $checkoptions[$number]['login'] == 'off' ? 'checked="checked"' : '';
		} 
		if (isset($options[$number]['logout'])) $checkoptions[$number]['logout'] = $options[$number]['logout'] ? 'checked="checked"' : '';
        if (isset($options[$number]['registerlink'])) $checkoptions[$number]['registerlink'] = $options[$number]['registerlink'] ? 'checked="checked"' : '';
        if (isset($options[$number]['seiteadmin'])) $checkoptions[$number]['seiteadmin'] = $options[$number]['seiteadmin'] ? 'checked="checked"' : '';
		if (isset($options[$number]['rememberme'])) $checkoptions[$number]['rememberme'] = $options[$number]['rememberme'] ? 'checked="checked"' : '';
		if (isset($options[$number]['rsslink'])) $checkoptions[$number]['rsslink'] = $options[$number]['rsslink'] ? 'checked="checked"' : '';
		if (isset($options[$number]['rsscommentlink'])) $checkoptions[$number]['rsscommentlink'] = $options[$number]['rsscommentlink'] ? 'checked="checked"' : '';
		if (isset($options[$number]['wordpresslink'])) $checkoptions[$number]['wordpresslink'] = $options[$number]['wordpresslink'] ? 'checked="checked"' : '';
		if (isset($options[$number]['lostpwlink'])) $checkoptions[$number]['lostpwlink'] = $options[$number]['lostpwlink'] ? 'checked="checked"' : '';
		if (isset($options[$number]['newpostslink'])) $checkoptions[$number]['newpostslink'] = $options[$number]['newpostslink'] ? 'checked="checked"' : '';
		if (isset($options[$number]['newpageslink'])) $checkoptions[$number]['newpageslink'] = $options[$number]['newpageslink'] ? 'checked="checked"' : '';
        if (isset($options[$number]['pluginsadminlink'])) $checkoptions[$number]['pluginsadminlink'] = $options[$number]['pluginsadminlink'] ? 'checked="checked"' : '';
        if (isset($options[$number]['commentsadminlink'])) $checkoptions[$number]['commentsadminlink'] = $options[$number]['commentsadminlink'] ? 'checked="checked"' : '';
        if (isset($options[$number]['usersadminlink'])) $checkoptions[$number]['usersadminlink'] = $options[$number]['usersadminlink'] ? 'checked="checked"' : '';
		if (isset($options[$number]['profilelink'])) $checkoptions[$number]['profilelink']= $options[$number]['profilelink'] ? 'checked="checked"' : '';
        if (isset($options[$number]['profilelinkadmin'])) $checkoptions[$number]['profilelinkadmin']= $options[$number]['profilelinkadmin'] ? 'checked="checked"' : '';
		if (isset($options[$number]['showadminhierarchy'])) $checkoptions[$number]['showadminhierarchy'] = $options[$number]['showadminhierarchy'] ? 'checked="checked"' : '';
        if (isset($options[$number]['showwpmeta'])) $checkoptions[$number]['showwpmeta'] = $options[$number]['showwpmeta'] ? 'checked="checked"' : '';
        if (isset($options[$number]['displayidentity'])) $checkoptions[$number]['displayidentity'] = $options[$number]['displayidentity'] ? 'checked="checked"' : '';
        if (isset($options[$number]['usewpadminlinks'])) $checkoptions[$number]['usewpadminlinks'] = $options[$number]['usewpadminlinks'] ? 'checked="checked"' : '';
		
		//displaying options
		if (get_option('k2sidebarmanager') != '1') {?><p><label for="minimeta-title-<?php echo $number; ?>"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title-<?php echo $number; ?>" name="minimeta-title-<?php echo $number; ?>" type="text" value="<?php echo $checkoptions[$number]['title']; ?>" /></label></p><?php } ?>
		<table style="width:100%;border:none"><tr><td valign="top" style="text-align:left;">
        <span style="font-weight:bold;"><?php _e('Show when logget out:','MiniMetaWidget');?></span><br />
         <label for="minimeta-login-<?php echo $number; ?>"><?php _e('Login Type:','MiniMetaWidget');?><br /><input type="radio" name="minimeta-login-<?php echo $number; ?>" id="minimeta-login-link-<?php echo $number; ?>" value="link" <?php echo $checkoptions[$number]['loginLink']; ?> />&nbsp;<?php _e('Link','MiniMetaWidget');?>&nbsp;&nbsp;<input type="radio" name="minimeta-login-<?php echo $number; ?>" id="minimeta-login-form-<?php echo $number; ?>" value="form" <?php echo $checkoptions[$number]['loginForm']; ?> />&nbsp;<?php _e('Form','MiniMetaWidget');?>&nbsp;&nbsp;<input type="radio" name="minimeta-login-<?php echo $number; ?>" id="minimeta-login-off-<?php echo $number; ?>" value="off" <?php echo $checkoptions[$number]['loginOff']; ?> />&nbsp;<?php _e('Off','MiniMetaWidget');?>&nbsp</label><br />
         <label for="minimeta-rememberme-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['rememberme']; ?> id="minimeta-rememberme-<?php echo $number; ?>" name="minimeta-rememberme-<?php echo $number; ?>" />&nbsp;<?php _e('Remember me');?></label><br />
		 <label for="minimeta-lostpwlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['lostpwlink']; ?> id="minimeta-lostpwlink-<?php echo $number; ?>" name="minimeta-lostpwlink-<?php echo $number; ?>" />&nbsp;<?php _e('Lost your password?');?></label><br />
		 <label for="minimeta-registerlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['registerlink']; ?> id="minimeta-registerlink-<?php echo $number; ?>" name="minimeta-registerlink-<?php echo $number; ?>" />&nbsp;<?php _e('Register');?></label><br />
        <br />
        <span style="font-weight:bold;"><?php _e('Show allways:','MiniMetaWidget');?></span><br />
		 <label for="minimeta-rsslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['rsslink']; ?> id="minimeta-rsslink-<?php echo $number; ?>" name="minimeta-rsslink-<?php echo $number; ?>" />&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-rsscommentlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['rsscommentlink']; ?> id="minimeta-rsscommentlink-<?php echo $number; ?>" name="minimeta-rsscommentlink-<?php echo $number; ?>" />&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-wordpresslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['wordpresslink']; ?> id="minimeta-wordpresslink-<?php echo $number; ?>" name="minimeta-wordpresslink-<?php echo $number; ?>" />&nbsp;<?php _e('Link to WordPress.org','MiniMetaWidget');?></label><br />
		 <label for="minimeta-showwpmeta-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['showwpmeta']; ?> id="minimeta-showwpmeta-<?php echo $number; ?>" name="minimeta-showwpmeta-<?php echo $number; ?>" />&nbsp;<?php _e('wp_meta Plugin hooks','MiniMetaWidget');?></label><br />
        </td><td style="text-align:right;">
        <span style="font-weight:bold;"><?php _e('Show when logget in:','MiniMetaWidget');?></span><br />
         <label for="minimeta-logout-<?php echo $number; ?>"><?php _e('Logout');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['logout']; ?> id="minimeta-logout-<?php echo $number; ?>" name="minimeta-logout-<?php echo $number; ?>" /></label><br />
         <label for="minimeta-profilelinkadmin-<?php echo $number; ?>"><?php _e('Your Profile');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['profilelinkadmin']; ?> id="minimeta-profilelinkadmin-<?php echo $number; ?>" name="minimeta-profilelinkadmin-<?php echo $number; ?>" /></label><br />
         <label for="minimeta-seiteadmin-<?php echo $number; ?>"><?php _e('Site Admin');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['seiteadmin']; ?> id="minimeta-seiteadmin-<?php echo $number; ?>" name="minimeta-seiteadmin-<?php echo $number; ?>" /></label><br />
		 <label for="minimeta-displayidentity-<?php echo $number; ?>"><?php _e('Disply user Identity as title','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['displayidentity']; ?> id="minimeta-displayidentity-<?php echo $number; ?>" name="minimeta-displayidentity-<?php echo $number; ?>" /></label><br />
         <label for="minimeta-profilelink-<?php echo $number; ?>"><?php _e('Link to Your Profile in title','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['profilelink']; ?> id="minimeta-profilelink-<?php echo $number; ?>" name="minimeta-profilelink-<?php echo $number; ?>" /></label><br />
         <span style="font-style:italic;"><?php _e('Admin Tools:','MiniMetaWidget');?></span><br />
         <?PHP if (function_exists(wp_admin_links)) { ?>
          <label for="minimeta-usewpadminlinks-<?php echo $number; ?>" title="<?php _e('Use WP Admin Links Plugin and not the admin links from MiniMeta Widget','MiniMetaWidget');?>"><?php _e('Use WP Admin Links Plugin','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['usewpadminlinks']; ?> id="minimeta-usewpadminlinks-<?php echo $number; ?>" name="minimeta-usewpadminlinks-<?php echo $number; ?>" /></label><br />
         <?PHP } ?>
         <label for="minimeta-showadminhierarchy-<?php echo $number; ?>"><?php _e('Make admin tools hierarchy','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['showadminhierarchy']; ?> id="minimeta-showadminhierarchy-<?php echo $number; ?>" name="minimeta-showadminhierarchy-<?php echo $number; ?>" /></label><br />
         <label for="minimeta-newpostslink-<?php echo $number; ?>"><?php _e('Write Post');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['newpostslink']; ?> id="minimeta-newpostslink-<?php echo $number; ?>" name="minimeta-newpostslink-<?php echo $number; ?>" /></label><br />
		 <label for="minimeta-newpageslink-<?php echo $number; ?>"><?php _e('Write Page');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['newpageslink']; ?> id="minimeta-newpageslink-<?php echo $number; ?>" name="minimeta-newpageslink-<?php echo $number; ?>" /></label><br />
         <label for="minimeta-pluginsadminlink-<?php echo $number; ?>"><?php _e('Plugins');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['pluginsadminlink']; ?> id="minimeta-pluginsadminlink-<?php echo $number; ?>" name="minimeta-pluginsadminlink-<?php echo $number; ?>" /></label><br />
         <label for="minimeta-commentsadminlink-<?php echo $number; ?>"><?php _e('Comments');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['commentsadminlink']; ?> id="minimeta-commentsadminlink-<?php echo $number; ?>" name="minimeta-commentsadminlink-<?php echo $number; ?>" /></label><br />
         <label for="minimeta-usersadminlink-<?php echo $number; ?>"><?php _e('Users');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions[$number]['usersadminlink']; ?> id="minimeta-usersadminlink-<?php echo $number; ?>" name="minimeta-usersadminlink-<?php echo $number; ?>" /></label><br />
        </td></tr></table>
        <?PHP if (get_option('k2sidebarmanager') != '1') {?><input type="hidden" id="minimeta-submit-<?php echo $number; ?>" name="minimeta-submit-<?php echo $number; ?>" value="1" /><?php } ?>
		<?php
	}
	
    
    function widget_minimeta_setup() {
        $options = $newoptions = get_option('widget_minimeta');
        if ( isset($_POST['minimeta-number-submit']) ) {
            $number = (int) $_POST['minimeta-number'];
            if ( $number > 9 ) $number = 9;
            if ( $number < 1 ) $number = 1;
            $newoptions['number'] = $number;
        }
        if ( $options != $newoptions ) {
            $options = $newoptions;
            update_option('widget_minimeta', $options);
            widget_minimeta_register();
        }
    }
    
    
    function widget_minimeta_page() {
        $options = $newoptions = get_option('widget_minimeta');
        ?>
        <div class="wrap">
            <form method="POST">
                <h2>MiniMeta Widgets</h2>
                <p style="line-height: 30px;"><?php _e('How many MiniMeta widgets would you like?','MiniMetaWidget'); ?>
                <select id="minimeta-number" name="minimeta-number" value="<?php echo $options['number']; ?>">
                <?php for ( $i = 1; $i < 10; ++$i ) echo "<option value='$i' ".($options['number']==$i ? "selected='selected'" : '').">$i</option>"; ?>
                </select>
                <span class="submit"><input type="submit" name="minimeta-number-submit" id="minimeta-number-submit" value="<?php _e('Save'); ?>" /></span></p>
            </form>
        </div>
        <?php
    }   
    
    //copy action login_head to wp-head if login form enabeld for plugin hooks
	function widget_minimeta_login_head_to_wp_head() {
        if (get_option('k2sidebarmanager') == '1') {
            //can't find out is lofin form active in K2 modules thats why its ollways on.
            do_action('login_head'); //do action from login had
        } else {
            $options = get_option('widget_minimeta');
            //find out is a ligon form in any MiniMeta Widegt activatet
            if($options[1]['login']=='form' or $options[2]['login']=='form' or $options[3]['login']=='form' or
               $options[4]['login']=='form' or $options[5]['login']=='form' or $options[6]['login']=='form' or
               $options[7]['login']=='form' or $options[8]['login']=='form' or $options[9]['login']=='form') {
             do_action('login_head'); //do action from login had
            }
        }    
    }
    
    
    function widget_minimeta_register() { 
 	 // This registers our widget and  widget control form for K2 SBM Widgets
	 $options = get_option('widget_minimeta');
     if (get_option('k2sidebarmanager') == '1') {
      register_sidebar_module('MiniMeta Widget', 'widget_minimeta');
      register_sidebar_module_control('MiniMeta Widget', 'widget_minimeta_control');
     } else { // This registers our widget and  widget control form for WordPress Widgets
	  $number = $options['number'];
	  if ( $number < 1 ) $number = 1;
	  if ( $number > 9 ) $number = 9;
	  for ($i = 1; $i <= 9; $i++) {
		$name = array('MiniMeta Widget %s', null, $i);
		register_sidebar_widget($name, $i <= $number ? 'widget_minimeta' : /* unregister */ '', $i);
		register_widget_control($name, $i <= $number ? 'widget_minimeta_control' : /* unregister */ '', 400, 280, $i);
	  }
	  add_action('sidebar_admin_setup', 'widget_minimeta_setup');
	  add_action('sidebar_admin_page', 'widget_minimeta_page');
     }
     if (!is_user_logged_in()) add_action('wp_head', 'widget_minimeta_login_head_to_wp_head');
    }
    
    
 widget_minimeta_register();
}
add_action('init', 'widget_minnimeta_init');

/**
* Deactivate plugin
*
* Function used when this plugin is deactivated in Wordpress.
* Delete all Options
*/
function widget_minnimeta_deactivate() {
	if (K2_USING_SBM) {
     sbm_delete_option('widget_minimeta');
    } 
    delete_option('widget_minimeta');
}

add_action('deactivate_'.dirname(plugin_basename(__FILE__)).'/minimeta-widget.php','widget_minnimeta_deactivate');
?>
