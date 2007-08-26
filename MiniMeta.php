<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/protfolio/minimeta/
Description: Mini Verson of the WP Meta Widget with differnt logon types and some additional admin links
Author: Daniel H&uuml;sken
Version: 2.6.0
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
 */


// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_minnimeta_init() {
	//Loads languga files
	load_plugin_textdomain('minimeta', 'wp-content/plugins/'.dirname(plugin_basename(__FILE__)));
	
	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_minimeta($args) {
		extract($args);
		$options = get_option('widget_minimeta');
		$title = empty($options['title']) ? __('Meta') : $options['title'];
		//defaults
        $login ='link';
        $logout ='1';        
		$registerlink ='1';
        $seiteadmin ='1';        
		$rememberme ='1';
		$rsslink ='1';
		$rsscommentlink ='1';
		$wordpresslink ='1';
		$lostpwlink ='';
		$nespostsink ='';
		$newpageslink ='';
        $commentsadminlink ='';
        $pluginsadminlink ='';
        $usersadminlink ='';
		$showadminhierarchy ='';
        $showwpmeta ='1';
        
		if (isset($options['login'])) $login =$options['login'];
        if (isset($options['loginlogout']) and !isset($options['login'])) $login =$options['loginlogout']; //four old options
        if (isset($options['logout'])) $loginlogout =$options['logout'];       
		if (isset($options['registerlink'])) $registerlink =$options['registerlink'];
        if (isset($options['seiteadmin'])) $seiteadmin =$options['seiteadmin'];     
		if (isset($options['rememberme'])) $rememberme =$options['rememberme'];
		if (isset($options['rsslink'])) $rsslink =$options['rsslink'];
		if (isset($options['rsscommentlink'])) $rsscommentlink =$options['rsscommentlink'];
		if (isset($options['wordpresslink'])) $wordpresslink =$options['wordpresslink'];
		if (isset($options['lostpwlink'])) $lostpwlink =$options['lostpwlink'];
		if (isset($options['nespostsink'])) $nespostsink =$options['nespostsink'];
		if (isset($options['newpageslink'])) $newpageslink =$options['newpageslink'];
        if (isset($options['commentsadminlink'])) $commentsadminlink =$options['commentsadminlink'];
        if (isset($options['pluginsadminlink'])) $pluginsadminlink =$options['pluginsadminlink'];
        if (isset($options['usersadminlink'])) $usersadminlink =$options['usersadminlink'];      
		if (isset($options['profilelink'])) $profilelink =$options['profilelink'];
		if (isset($options['showadminhierarchy'])) $showadminhierarchy =$options['showadminhierarchy'];
        if (isset($options['showwpmeta'])) $showwpmeta =$options['showwpmeta'];
		?>
		
        
        <?php echo $before_widget; ?>
		<?php echo $before_title . $title . $after_title; ?>
		
		<?php if(is_user_logged_in()) { ?>
            <ul>
			<?php if($seiteadmin) {wp_register();} ?>
                <?php if($showadminhierarchy and ($newpageslink or $nespostsink or $profilelink or $logout)) {?><ul class="children"><?php }?>
                <?php if($logout) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=logout&amp;redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>" title="<?php _e('Logout') ?>"><?php _e('Logout') ?></a></li><?php }?>
                <?php if($profilelink and current_user_can('read')) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/profile.php" title="<?php _e('Your Profile') ?>"><?php _e('Your Profile') ?></a></li><?php }?>
                <?php if($nespostsink and current_user_can('edit_posts')) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/post-new.php" title="<?php _e('Write Post') ?>"><?php _e('Write Post') ?></a></li><?php }?>
                <?php if($newpageslink and current_user_can('edit_pages')) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/page-new.php" title="<?php _e('Write Page') ?>"><?php _e('Write Page') ?></a></li><?php }?>			
                    <?php if($showadminhierarchy and ($usersadminlink or $commentsadminlink or $pluginsadminlink)) {?><ul class="children"><?php }?>
                    <?php if($usersadminlink and current_user_can('edit_users')) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/users.php" title="<?php _e('Users') ?>"><?php _e('Users') ?></a></li><?php }?>
                    <?php if($commentsadminlink and current_user_can('edit_posts')) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/edit-comments.php" title="<?php _e('Comments') ?>"><?php _e('Comments') ?></a></li><?php }?>
                    <?php if($pluginsadminlink and current_user_can('activate_plugins')) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/plugins.php" title="<?php _e('Plugins') ?>"><?php _e('Plugins') ?></a></li><?php }?>
                    <?php if($showadminhierarchy and ($usersadminlink or $commentsadminlink or $pluginsadminlink)) {?></ul><?php }?>
                <?php if($showadminhierarchy and ($newpageslink or $nespostsink or $profilelink or $logout)) {?></ul><?php }?>
		<?php } else { ?>
			<?php if($login=='form') {?>
				<?php 
				$cookie_login = wp_get_cookie_login();
				if ( ! empty($cookie_login) ) {
					$user_login = $cookie_login['login'];
				}
				?>
				<form name="loginform" id="loginform" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
				<label><?php _e('Username:') ?><br />
				<input type="text" name="log" id="user_login" class="input" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" size="20" tabindex="10" /></label><br />
				<label><?php _e('Password:') ?><br />
				<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label><br />
				<?php if($rememberme) {?><label><input name="rememberme" id="rememberme" type="checkbox" value="forever" tabindex="90" /> <?php _e('Remember me'); ?></label><?php } ?>
				<div align="center"><input type="submit" id="wp-submit" name="wp-submit" value="<?php _e('Login'); ?> &raquo;" tabindex="100" /></div>
				<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
				</form>
			<?php }?>
            <ul>
			<?php if($login=='link') {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=login&amp;redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>" title="<?php _e('Login') ?>"><?php _e('Login') ?></a></li><?php }?>
			<?php if($lostpwlink) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=lostpassword" title="<?php _e('Password Lost and Found') ?>"><?php _e('Lost your password?') ?></a></li><?php }?>
			<?php if($registerlink) {wp_register();} ?>
		<?php } ?>

		<?php if($rsslink) {?><li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo attribute_escape(__('Syndicate this site using RSS 2.0')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li><?php }?>
		<?php if($rsscommentlink) {?><li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo attribute_escape(__('The latest comments to all posts in RSS')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li><?php }?>
		<?php if($wordpresslink) {?><li><a href="http://wordpress.org/" title="<?php echo attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.')); ?>">WordPress.org</a></li><?php }?>
		<?php if($showwpmeta) { wp_meta(); } ?>
		</ul>
		<?php echo $after_widget; ?>
		<?php
	}
			
	function widget_minimeta_control() {
		$options = $newoptions = get_option('widget_minimeta');
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
			$newoptions['nespostsink'] = isset($_POST['minimeta-nespostsink']);
			$newoptions['newpageslink'] = isset($_POST['minimeta-newpageslink']);
            $newoptions['pluginsadminlink'] = isset($_POST['minimeta-pluginsadminlink']);
            $newoptions['commentsadminlink'] = isset($_POST['minimeta-commentsadminlink']);
            $newoptions['usersadminlink'] = isset($_POST['minimeta-usersadminlink']);
			$newoptions['profilelink'] = isset($_POST['minimeta-profilelink']);
			$newoptions['showadminhierarchy'] = isset($_POST['minimeta-showadminhierarchy']);
            $newoptions['showwpmeta'] = isset($_POST['minimeta-showwpmeta']);
		}
			if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_minimeta', $options);
		}
		
		$loginLink ='checked="checked"';
		$loginForm ='';
		$loginOff ='';
        $logout ='checked="checked"';
		$registerlink ='checked="checked"';
        $seiteadmin ='checked="checked"';
		$rememberme ='checked="checked"';
		$rsslink ='checked="checked"';
		$rsscommentlink ='checked="checked"';
		$wordpresslink ='checked="checked"';
		$lostpwlink ='';
		$nespostsink ='';
		$newpageslink ='';
        $pluginsadminlink ='';
        $commentsadminlink ='';
        $usersadminlink ='';
		$profilelink ='';
		$showadminhierarchy ='';
        $showwpmeta='checked="checked"';
		
		$title = attribute_escape($options['title']);
		if (isset($options['login'])) { 
			$login = htmlspecialchars($options['login'], ENT_QUOTES);
			$loginLink = $login == 'link' ? 'checked="checked"' : '';
			$loginForm = $login == 'form' ? 'checked="checked"' : '';
			$loginOff = $login == 'off' ? 'checked="checked"' : '';
		} elseif (isset($options['loginlogout']) and !isset($options['login'])) {  //for old options
			$login = htmlspecialchars($options['loginlogout'], ENT_QUOTES);
			$loginLink = $login == 'link' ? 'checked="checked"' : '';
			$loginForm = $login == 'form' ? 'checked="checked"' : '';
			$loginOff = $login == 'off' ? 'checked="checked"' : '';
		}
		if (isset($options['logout'])) $logout = $options['logout'] ? 'checked="checked"' : '';
        if (isset($options['registerlink'])) $registerlink = $options['registerlink'] ? 'checked="checked"' : '';
        if (isset($options['seiteadmin'])) $seiteadmin = $options['seiteadmin'] ? 'checked="checked"' : '';
		if (isset($options['rememberme'])) $registerlink = $options['rememberme'] ? 'checked="checked"' : '';
		if (isset($options['rsslink'])) $rsslink = $options['rsslink'] ? 'checked="checked"' : '';
		if (isset($options['rsscommentlink'])) $rsscommentlink = $options['rsscommentlink'] ? 'checked="checked"' : '';
		if (isset($options['wordpresslink'])) $wordpresslink = $options['wordpresslink'] ? 'checked="checked"' : '';
		if (isset($options['lostpwlink'])) $lostpwlink = $options['lostpwlink'] ? 'checked="checked"' : '';
		if (isset($options['nespostsink'])) $nespostsink = $options['nespostsink'] ? 'checked="checked"' : '';
		if (isset($options['newpageslink'])) $newpageslink = $options['newpageslink'] ? 'checked="checked"' : '';
        if (isset($options['pluginsadminlink'])) $pluginsadminlink = $options['pluginsadminlink'] ? 'checked="checked"' : '';
        if (isset($options['commentsadminlink'])) $commentsadminlink = $options['commentsadminlink'] ? 'checked="checked"' : '';
        if (isset($options['usersadminlink'])) $usersadminlink = $options['usersadminlink'] ? 'checked="checked"' : '';
		if (isset($options['profilelink'])) $profilelink = $options['profilelink'] ? 'checked="checked"' : '';
		if (isset($options['showadminhierarchy'])) $showadminhierarchy = $options['showadminhierarchy'] ? 'checked="checked"' : '';
        if (isset($options['showwpmeta'])) $showwpmeta = $options['showwpmeta'] ? 'checked="checked"' : '';
		
		?>
		<p><label for="minimeta-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title" name="minimeta-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<table border="0" width="100%"><tr><td valign="top" style="text-align:left;margin-left:10px;">
        <p style="font-weight:bold;"><?php _e('Show when logget out:');?></p>
         <label for="minimeta-login"><?php _e('Login Type:');?><br /><input type="radio" name="minimeta-login" id="minimeta-login-link" value="link" <?php echo $loginLink; ?> />&nbsp;<?php _e('Link');?>&nbsp;&nbsp;<input type="radio" name="minimeta-login" id="minimeta-login-form" value="form" <?php echo $loginForm; ?> />&nbsp;<?php _e('Form');?>&nbsp;&nbsp;<input type="radio" name="minimeta-login" id="minimeta-login-off" value="off" <?php echo $loginOff; ?> />&nbsp;<?php _e('Off');?>&nbsp</label><br />
         <label for="minimeta-rememberme"><input class="checkbox" type="checkbox" <?php echo $rememberme; ?> id="minimeta-rememberme" name="minimeta-rememberme" />&nbsp;<?php _e('Remember me');?></label><br />
		 <label for="minimeta-lostpwlink"><input class="checkbox" type="checkbox" <?php echo $lostpwlink; ?> id="minimeta-lostpwlink" name="minimeta-lostpwlink" />&nbsp;<?php _e('Lost your password?');?></label><br />
		 <label for="minimeta-registerlink"><input class="checkbox" type="checkbox" <?php echo $registerlink; ?> id="minimeta-registerlink" name="minimeta-registerlink" />&nbsp;<?php _e('Register');?></label><br />
        </td><td style="text-align:right;margin-right:10px;">
        <p style="font-weight:bold;"><?php _e('Show when logget in:');?></p>
         <label for="minimeta-logout"><?php _e('Logout');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $logout; ?> id="minimeta-logout" name="minimeta-logout" /></label><br />
         <label for="minimeta-seiteadmin"><?php _e('Seite Admin');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $seiteadmin; ?> id="minimeta-seiteadmin" name="minimeta-seiteadmin" /></label><br />
		 <label for="minimeta-profilelink"><?php _e('Your Profile');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $profilelink; ?> id="minimeta-profilelink" name="minimeta-profilelink" /></label><br />
         <span style="font-weight:bold;"><?php _e('Admin Tools:');?>&nbsp;&nbsp;</span><br />
         <label for="minimeta-showadminhierarchy"><?php _e('Make admin tools hierarchy');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $showadminhierarchy; ?> id="minimeta-showadminhierarchy" name="minimeta-showadminhierarchy" /></label><br />
         <label for="minimeta-nespostsink"><?php _e('Write Post');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $nespostsink; ?> id="minimeta-nespostsink" name="minimeta-nespostsink" /></label><br />
		 <label for="minimeta-newpageslink"><?php _e('Write Page');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $newpageslink; ?> id="minimeta-newpageslink" name="minimeta-newpageslink" /></label><br />
         <label for="minimeta-pluginsadminlink"><?php _e('Plugins');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $pluginsadminlink; ?> id="minimeta-pluginsadminlink" name="minimeta-pluginsadminlink" /></label><br />
         <label for="minimeta-commentsadminlink"><?php _e('Comments');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $commentsadminlink; ?> id="minimeta-commentsadminlink" name="minimeta-commentsadminlink" /></label><br />
         <label for="minimeta-usersadminlink"><?php _e('Users');?>&nbsp;<input class="checkbox" type="checkbox" <?php echo $usersadminlink; ?> id="minimeta-usersadminlink" name="minimeta-usersadminlink" /></label><br />
        </td></tr><tr><td colspan="2" style="text-align:left;">
        <p style="font-weight:bold;"><?php _e('Show allways:');?></p>
		 <label for="minimeta-rsslink" style="margin-left:100px;"><input class="checkbox" type="checkbox" <?php echo $rsslink; ?> id="minimeta-rsslink" name="minimeta-rsslink" />&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-rsscommentlink" style="margin-left:100px;"><input class="checkbox" type="checkbox" <?php echo $rsscommentlink; ?> id="minimeta-rsscommentlink" name="minimeta-rsscommentlink" />&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-wordpresslink" style="margin-left:100px;"><input class="checkbox" type="checkbox" <?php echo $wordpresslink; ?> id="minimeta-wordpresslink" name="minimeta-wordpresslink" />&nbsp;<?php _e('Link to WordPress.org');?></label><br />
		 <label for="minimeta-showwpmeta" style="margin-left:100px;"><input class="checkbox" type="checkbox" <?php echo $showwpmeta; ?> id="minimeta-showwpmeta" name="minimeta-showwpmeta" />&nbsp;<?php _e('wp_meta Plugin hooks');?></label><br />
        </td></tr></table>
        <p style="text-align:right;font-size:10px"><a herf="http://danielhuesken.de/protfolio/minimeta/" target="new">MiniMeta Widget</a> by <a herf="http://danielhuesken.de" target="new">Daniel H&uuml;sken</a></p>
        <input type="hidden" id="minimeta-submit" name="minimeta-submit" value="1" />
		<?php
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Mini Meta', 'widgets'), 'widget_minimeta');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x380 pixel form.
	register_widget_control(array('Mini Meta', 'widgets'), 'widget_minimeta_control', 400, 390);
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
