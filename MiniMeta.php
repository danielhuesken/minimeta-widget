<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/protfolio/minimeta/
Description: Mini Verson of the WP Meta Widget with differnt logon types and some additional admin links
Author: Daniel H&uuml;sken
Version: 2.7.0
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

	function widget_minimeta($args) {
        global $user_identity;		
		//defaults
        $options= array('login'=>'link','logout' =>'1','registerlink' =>'1','seiteadmin' =>'1','rememberme' =>'1',
                        'rsslink' =>'1','rsscommentlink' =>'1','wordpresslink' =>'1','lostpwlink' =>'','newpostslink' =>'',
                        'newpageslink' =>'','commentsadminlink' =>'','pluginsadminlink' =>'','usersadminlink' =>'',
                        'showadminhierarchy' =>'','showwpmeta' =>'1','displayidentity'=>'','usewpadminlinks'=>'');
        //load options
        $getoptions = get_option('widget_minimeta');
        //overwrite def. options with loadet options
        if(is_array($getoptions)) $options=array_merge($options, $getoptions);
        //Show a standart Title if empty
        if (empty($options['title'])) $options['title']=__('Meta');
        //title compatibility for SBM
        if (!empty($args['title'])) $options['title']=$args['title'];

		//Shown part of Widget
        echo $args["before_widget"];
        if(is_user_logged_in()) {
            if ($options['displayidentity'] and !empty($user_identity)) $options['title']=$user_identity;
            if($options['profilelink'] and current_user_can('read')) {
                echo $args['before_title'] ."<a href=\"".get_bloginfo('wpurl')."/wp-admin/profile.php\" title=\"".__('Your Profile')."\">". $options['title'] ."</a>". $args['after_title']; 
            } else {
            echo $args['before_title'] . $options['title'] . $args['after_title']; 
            }
            //For Wp-Admin Links if supportet
            echo "<ul>";
            if (function_exists(wp_admin_links) and $options['usewpadminlinks']) { 
                wp_admin_links('','','',true);
            } else  {
                if($options['seiteadmin']) {wp_register();}
                    if($options['showadminhierarchy'] and ($options['newpageslink'] or $options['newpostslink'] or $options['profilelink'] or $options['logout'])) echo "<ul class=\"children\">"; 
                    if($options['logout']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=logout&amp;redirect_to=".$_SERVER['REQUEST_URI']."\" title=\"".__('Logout')."\">".__('Logout')."</a></li>"; 
                    if($options['newpostslink'] and current_user_can('edit_posts')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/post-new.php\" title=\"".__('Write Post')."\">".__('Write Post')."</a></li>";
                    if($options['newpageslink'] and current_user_can('edit_pages')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/page-new.php\" title=\"".__('Write Page')."\">".__('Write Page')."</a></li>";			
                        if($options['showadminhierarchy'] and ($options['usersadminlink'] or $options['commentsadminlink'] or $options['pluginsadminlink'])) echo "<ul class=\"children\">";
                        if($options['usersadminlink'] and current_user_can('edit_users')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/users.php\" title=\"".__('Users')."\">".__('Users')."</a></li>";
                        if($options['commentsadminlink'] and current_user_can('edit_posts')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/edit-comments.php\" title=\"".__('Comments')."\">".__('Comments')."</a></li>";
                        if($options['pluginsadminlink'] and current_user_can('activate_plugins')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/plugins.php\" title=\"".__('Plugins')."\">".__('Plugins')."</a></li>";
                        if($options['showadminhierarchy'] and ($options['usersadminlink'] or $options['commentsadminlink'] or $options['pluginsadminlink'])) echo "</ul>"; 
                    if($options['showadminhierarchy'] and ($options['newpageslink'] or $options['newpostslink'] or $options['profilelink'] or $options['logout'])) echo "</ul>";
            } 
        } else {
			echo $args['before_title'] . $options['title']. $args['after_title'];
            if($options['login']=='form') {?>
				<form name="loginform" id="loginform" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
				<label><?php _e('Username:') ?><br />
				<input type="text" name="log" id="user_login" class="input" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" size="20" tabindex="10" /></label><br />
				<label><?php _e('Password:') ?><br />
				<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label><br />
				<?php if($options['rememberme']) {?><label><input name="rememberme" id="rememberme" type="checkbox" value="forever" tabindex="90" /> <?php _e('Remember me'); ?></label><?php } ?>
				<div align="center"><input type="submit" id="wp-submit" name="wp-submit" value="<?php _e('Login'); ?> &raquo;" tabindex="100" /></div>
				<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
				</form><?php
			}
            echo "<ul>";
			if($options['login']=='link') echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=login&amp;redirect_to=".$_SERVER['REQUEST_URI']."\" title=\"".__('Login')."\">".__('Login')."</a></li>";
			if($options['lostpwlink']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=lostpassword\" title=\"".__('Password Lost and Found')."\">".__('Lost your password?')."</a></li>";
			if($options['registerlink']) wp_register();
		} 

		if($options['rsslink']) echo "<li><a href=\"".get_bloginfo('rss2_url')."\" title=\"".attribute_escape(__('Syndicate this site using RSS 2.0'))."\">".__('Entries <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options['rsscommentlink']) echo "<li><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".attribute_escape(__('The latest comments to all posts in RSS'))."\">".__('Comments <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options['wordpresslink']) echo "<li><a href=\"http://wordpress.org/\" title=\"".attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\">WordPress.org</a></li>";
		if($options['showwpmeta']) wp_meta();
		echo "</ul>";
		echo $args['after_widget'];
	}
			
	function widget_minimeta_control() {
		//load options
        $options = $newoptions = get_option('widget_minimeta');
        //get post options
		if ( $_POST["minimeta-submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["minimeta-title"]));
			$newoptions['login'] = strip_tags(stripslashes($_POST['minimeta-login']));
			$newoptions['logout'] = isset($_POST['minimeta-logout']);
            $newoptions['registerlink'] = isset($_POST['minimeta-registerlink']);
            $newoptions['seiteadmin'] = isset($_POST['minimeta-seiteadmin']);
			$newoptions['rememberme'] = isset($_POST['minimeta-rememberme']);
			$newoptions['rsslink'] = isset($_POST['minimeta-rsslink']);
			$newoptions['rsscommentlink'] = isset($_POST['minimeta-rsscommentlink']);
			$newoptions['wordpresslink'] = isset($_POST['minimeta-wordpresslink']);
			$newoptions['lostpwlink'] = isset($_POST['minimeta-lostpwlink']);
			$newoptions['newpostslink'] = isset($_POST['minimeta-newpostslink']);
			$newoptions['newpageslink'] = isset($_POST['minimeta-newpageslink']);
            $newoptions['pluginsadminlink'] = isset($_POST['minimeta-pluginsadminlink']);
            $newoptions['commentsadminlink'] = isset($_POST['minimeta-commentsadminlink']);
            $newoptions['usersadminlink'] = isset($_POST['minimeta-usersadminlink']);
			$newoptions['profilelink'] = isset($_POST['minimeta-profilelink']);
			$newoptions['showadminhierarchy'] = isset($_POST['minimeta-showadminhierarchy']);
            $newoptions['showwpmeta'] = isset($_POST['minimeta-showwpmeta']);
            $newoptions['displayidentity'] = isset($_POST['minimeta-displayidentity']);
            $newoptions['usewpadminlinks'] = isset($_POST['minimeta-usewpadminlinks']);
		}
		//safe options only when changed
        if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_minimeta', $options);
		}
		//def. options
        $checkoptions= array('title'=>__('Meta'),'loginLink'=>'checked="checked"','loginForm'=>'','loginOff'=>'','logout' =>'checked="checked"','registerlink' =>'checked="checked"','seiteadmin' =>'checked="checked"','rememberme' =>'checked="checked"',
                'rsslink' =>'checked="checked"','rsscommentlink' =>'checked="checked"','wordpresslink' =>'checked="checked"','lostpwlink' =>'','newpostslink' =>'',
                'newpageslink' =>'','commentsadminlink' =>'','pluginsadminlink' =>'','usersadminlink' =>'',
                'showadminhierarchy' =>'','showwpmeta' =>'checked="checked"','displayidentity'=>'','usewpadminlinks'=>'');

		//set checked for aktivatet options
		$checkoptions['title'] = attribute_escape($options['title']);
		if (isset($options['login'])) { 
			$checkoptions['login'] = htmlspecialchars($options['login'], ENT_QUOTES);
			$checkoptions['loginLink'] = $checkoptions['login'] == 'link' ? 'checked="checked"' : '';
			$checkoptions['loginForm'] = $checkoptions['login'] == 'form' ? 'checked="checked"' : '';
			$checkoptions['loginOff'] = $checkoptions['login'] == 'off' ? 'checked="checked"' : '';
		} 
		if (isset($options['logout'])) $checkoptions['logout'] = $options['logout'] ? 'checked="checked"' : '';
        if (isset($options['registerlink'])) $checkoptions['registerlink'] = $options['registerlink'] ? 'checked="checked"' : '';
        if (isset($options['seiteadmin'])) $checkoptions['seiteadmin'] = $options['seiteadmin'] ? 'checked="checked"' : '';
		if (isset($options['rememberme'])) $checkoptions['rememberme'] = $options['rememberme'] ? 'checked="checked"' : '';
		if (isset($options['rsslink'])) $checkoptions['rsslink'] = $options['rsslink'] ? 'checked="checked"' : '';
		if (isset($options['rsscommentlink'])) $checkoptions['rsscommentlink'] = $options['rsscommentlink'] ? 'checked="checked"' : '';
		if (isset($options['wordpresslink'])) $checkoptions['wordpresslink'] = $options['wordpresslink'] ? 'checked="checked"' : '';
		if (isset($options['lostpwlink'])) $checkoptions['lostpwlink'] = $options['lostpwlink'] ? 'checked="checked"' : '';
		if (isset($options['newpostslink'])) $checkoptions['newpostslink'] = $options['newpostslink'] ? 'checked="checked"' : '';
		if (isset($options['newpageslink'])) $checkoptions['newpageslink'] = $options['newpageslink'] ? 'checked="checked"' : '';
        if (isset($options['pluginsadminlink'])) $checkoptions['pluginsadminlink'] = $options['pluginsadminlink'] ? 'checked="checked"' : '';
        if (isset($options['commentsadminlink'])) $checkoptions['commentsadminlink'] = $options['commentsadminlink'] ? 'checked="checked"' : '';
        if (isset($options['usersadminlink'])) $checkoptions['usersadminlink'] = $options['usersadminlink'] ? 'checked="checked"' : '';
		if (isset($options['profilelink'])) $checkoptions['profilelink']= $options['profilelink'] ? 'checked="checked"' : '';
		if (isset($options['showadminhierarchy'])) $checkoptions['showadminhierarchy'] = $options['showadminhierarchy'] ? 'checked="checked"' : '';
        if (isset($options['showwpmeta'])) $checkoptions['showwpmeta'] = $options['showwpmeta'] ? 'checked="checked"' : '';
        if (isset($options['displayidentity'])) $checkoptions['displayidentity'] = $options['displayidentity'] ? 'checked="checked"' : '';
        if (isset($options['usewpadminlinks'])) $checkoptions['usewpadminlinks'] = $options['usewpadminlinks'] ? 'checked="checked"' : '';
		
		//displaying options
		if (!class_exists('K2SBM') and !class_exists('SBM')) {?><p><label for="minimeta-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title" name="minimeta-title" type="text" value="<?php echo $checkoptions['title']; ?>" /></label></p><?php } ?>
		<table style="width:100%;border:none"><tr><td valign="top" style="text-align:left;">
        <span style="font-weight:bold;"><?php _e('Show when logget out:','MiniMetaWidget');?></span><br />
         <label for="minimeta-login"><?php _e('Login Type:','MiniMetaWidget');?><br /><input type="radio" name="minimeta-login" id="minimeta-login-link" value="link" <?php echo $checkoptions['loginLink']; ?> />&nbsp;<?php _e('Link','MiniMetaWidget');?>&nbsp;&nbsp;<input type="radio" name="minimeta-login" id="minimeta-login-form" value="form" <?php echo $checkoptions['loginForm']; ?> />&nbsp;<?php _e('Form','MiniMetaWidget');?>&nbsp;&nbsp;<input type="radio" name="minimeta-login" id="minimeta-login-off" value="off" <?php echo $checkoptions['loginOff']; ?> />&nbsp;<?php _e('Off','MiniMetaWidget');?>&nbsp</label><br />
         <label for="minimeta-rememberme"><input class="checkbox" type="checkbox" <?php echo $checkoptions['rememberme']; ?> id="minimeta-rememberme" name="minimeta-rememberme" />&nbsp;<?php _e('Remember me');?></label><br />
		 <label for="minimeta-lostpwlink"><input class="checkbox" type="checkbox" <?php echo $checkoptions['lostpwlink']; ?> id="minimeta-lostpwlink" name="minimeta-lostpwlink" />&nbsp;<?php _e('Lost your password?');?></label><br />
		 <label for="minimeta-registerlink"><input class="checkbox" type="checkbox" <?php echo $checkoptions['registerlink']; ?> id="minimeta-registerlink" name="minimeta-registerlink" />&nbsp;<?php _e('Register');?></label><br />
        <br />
        <span style="font-weight:bold;"><?php _e('Show allways:','MiniMetaWidget');?></span><br />
		 <label for="minimeta-rsslink"><input class="checkbox" type="checkbox" <?php echo $checkoptions['rsslink']; ?> id="minimeta-rsslink" name="minimeta-rsslink" />&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-rsscommentlink"><input class="checkbox" type="checkbox" <?php echo $checkoptions['rsscommentlink']; ?> id="minimeta-rsscommentlink" name="minimeta-rsscommentlink" />&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-wordpresslink"><input class="checkbox" type="checkbox" <?php echo $checkoptions['wordpresslink']; ?> id="minimeta-wordpresslink" name="minimeta-wordpresslink" />&nbsp;<?php _e('Link to WordPress.org','MiniMetaWidget');?></label><br />
		 <label for="minimeta-showwpmeta"><input class="checkbox" type="checkbox" <?php echo $checkoptions['showwpmeta']; ?> id="minimeta-showwpmeta" name="minimeta-showwpmeta" />&nbsp;<?php _e('wp_meta Plugin hooks','MiniMetaWidget');?></label><br />
        </td><td style="text-align:right;">
        <span style="font-weight:bold;"><?php _e('Show when logget in:','MiniMetaWidget');?></span><br />
         <label for="minimeta-logout"><?php _e('Logout');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['logout']; ?> id="minimeta-logout" name="minimeta-logout" /></label><br />
         <label for="minimeta-seiteadmin"><?php _e('Seite Admin');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['seiteadmin']; ?> id="minimeta-seiteadmin" name="minimeta-seiteadmin" /></label><br />
		 <label for="minimeta-displayidentity"><?php _e('Disply user Identity as title','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['displayidentity']; ?> id="minimeta-displayidentity" name="minimeta-displayidentity" /></label><br />
         <label for="minimeta-profilelink"><?php _e('Link to Your Profile in title','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['profilelink']; ?> id="minimeta-profilelink" name="minimeta-profilelink" /></label><br />
         <span style="font-style:italic;"><?php _e('Admin Tools:','MiniMetaWidget');?></span><br />
         <?PHP if (function_exists(wp_admin_links)) { ?>
          <label for="minimeta-usewpadminlinks" title="<?php _e('Use WP Admin Lings Plugin instat off the admin links from MiniMeta Widget','MiniMetaWidget');?>"><?php _e('Use WP Admin Lings Plugin','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['usewpadminlinks']; ?> id="minimeta-usewpadminlinks" name="minimeta-usewpadminlinks" /></label><br />
         <?PHP } ?>
         <label for="minimeta-showadminhierarchy"><?php _e('Make admin tools hierarchy','MiniMetaWidget');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['showadminhierarchy']; ?> id="minimeta-showadminhierarchy" name="minimeta-showadminhierarchy" /></label><br />
         <label for="minimeta-newpostslink"><?php _e('Write Post');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['newpostslink']; ?> id="minimeta-newpostslink" name="minimeta-newpostslink" /></label><br />
		 <label for="minimeta-newpageslink"><?php _e('Write Page');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['newpageslink']; ?> id="minimeta-newpageslink" name="minimeta-newpageslink" /></label><br />
         <label for="minimeta-pluginsadminlink"><?php _e('Plugins');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['pluginsadminlink']; ?> id="minimeta-pluginsadminlink" name="minimeta-pluginsadminlink" /></label><br />
         <label for="minimeta-commentsadminlink"><?php _e('Comments');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['commentsadminlink']; ?> id="minimeta-commentsadminlink" name="minimeta-commentsadminlink" /></label><br />
         <label for="minimeta-usersadminlink"><?php _e('Users');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $checkoptions['usersadminlink']; ?> id="minimeta-usersadminlink" name="minimeta-usersadminlink" /></label><br />
        </td></tr></table>
        <p style="text-align:right;font-size:x-small"><a herf="http://danielhuesken.de/protfolio/minimeta/" target="new">MiniMeta Widget</a> by <a herf="http://danielhuesken.de" target="new">Daniel H&uuml;sken</a></p>
        <input type="hidden" id="minimeta-submit" name="minimeta-submit" value="1" />
		<?php
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget('MiniMeta Widget', 'widget_minimeta');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 400x390 pixel form.
	register_widget_control('MiniMeta Widget', 'widget_minimeta_control', 380, 280);
    
}
add_action('init', 'widget_minnimeta_init');

/**
* Deactivate plugin
*
* Function used when this plugin is deactivated in Wordpress.
* Delete all Options
*/
function widget_minnimeta_deactivate() {
	delete_option('widget_minimeta');
}

add_action('deactivate_'.dirname(plugin_basename(__FILE__)).'/MiniMeta.php','widget_minnimeta_deactivate');
?>
