<?php
/*
Plugin Name: MiniMeta Widget
Plugin URI: http://danielhuesken.de/protfolio/minimeta/
Description: Mini Verson of the WP Meta Widget with differnt logon types
Author: Daniel H&uuml;sken
Version: 2.0.0
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
 */

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_minnimeta_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_minimeta($args) {
		extract($args);
		$options = get_option('widget_minimeta');
		$title = empty($options['title']) ? __('Meta') : $options['title'];
		$loginlogout ='link';
		$registerlink ='1';
		$rememberme ='1';
		$rsslink ='1';
		$rsscommentlink ='1';
		$wordpresslink ='1';
		$lostpwlink ='';
		if (isset($options['loginlogout'])) $loginlogout =$options['loginlogout'];
		if (isset($options['registerlink'])) $registerlink =$options['registerlink'];
		if (isset($options['rememberme'])) $rememberme =$options['rememberme'];
		if (isset($options['rsslink'])) $rsslink =$options['rsslink'];
		if (isset($options['rsscommentlink'])) $rsscommentlink =$options['rsscommentlink'];
		if (isset($options['wordpresslink'])) $wordpresslink =$options['wordpresslink'];
		if (isset($options['lostpwlink'])) $lostpwlink =$options['lostpwlink'];
		?>
		<?php echo $before_widget; ?>
		<?php echo $before_title . $title . $after_title; ?>
		
		<?php if($loginlogout!='form' or is_user_logged_in()) {?><ul><?php }?>
		<?php if(is_user_logged_in()) { ?>
			<?php if($loginlogout!='off') {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=logout&amp;redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>" title="<?php _e('Logout') ?>"><?php _e('Logout') ?></a></li><?php }?>
		<?php } else { ?>
			<?php if($loginlogout=='form') {?>
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
				<ul>
			<?php }?>
			<?php if($loginlogout=='link') {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=login&amp;redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>" title="<?php _e('Login') ?>"><?php _e('Login') ?></a></li><?php }?>
			<?php if($lostpwlink) {?><li><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=lostpassword" title="<?php _e('Password Lost and Found') ?>"><?php _e('Lost your password?') ?></a></li><?php }?>
		<?php } ?>
		<?php if($registerlink) {wp_register();} ?>
		<?php if($rsslink) {?><li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo attribute_escape(__('Syndicate this site using RSS 2.0')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li><?php }?>
		<?php if($rsscommentlink) {?><li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo attribute_escape(__('The latest comments to all posts in RSS')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li><?php }?>
		<?php if($wordpresslink) {?><li><a href="http://wordpress.org/" title="<?php echo attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.')); ?>">WordPress.org</a></li><?php }?>
		<?php wp_meta(); ?>
		</ul>
		<?php echo $after_widget; ?>
		<?php
	}
			
	function widget_minimeta_control() {
		$options = $newoptions = get_option('widget_minimeta');
		if ( $_POST["minimeta-submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["minimeta-title"]));
			$newoptions['loginlogout'] = strip_tags(stripslashes($_POST['minimeta-loginlogout']));
			$newoptions['registerlink'] = isset($_POST['minimeta-registerlink']);
			$newoptions['rememberme'] = isset($_POST['minimeta-rememberme']);
			$newoptions['rsslink'] = isset($_POST['minimeta-rsslink']);
			$newoptions['rsscommentlink'] = isset($_POST['minimeta-rsscommentlink']);
			$newoptions['wordpresslink'] = isset($_POST['minimeta-wordpresslink']);
			$newoptions['lostpwlink'] = isset($_POST['minimeta-lostpwlink']);
		}
			if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_minimeta', $options);
		}
		
		$loginlogoutLink ='checked="checked"';
		$loginlogoutForm ='';
		$loginlogoutOff ='';
		$registerlink ='checked="checked"';
		$rememberme ='checked="checked"';
		$rsslink ='checked="checked"';
		$rsscommentlink ='checked="checked"';
		$wordpresslink ='checked="checked"';
		$lostpwlink ='';
		
		$title = attribute_escape($options['title']);
		if (isset($options['loginlogout'])) { 
			$loginlogout = htmlspecialchars($options['loginlogout'], ENT_QUOTES);
			$loginlogoutLink = $loginlogout == 'link' ? 'checked="checked"' : '';
			$loginlogoutForm = $loginlogout == 'form' ? 'checked="checked"' : '';
			$loginlogoutOff = $loginlogout == 'off' ? 'checked="checked"' : '';
		}
		if (isset($options['registerlink'])) $registerlink = $options['registerlink'] ? 'checked="checked"' : '';
		if (isset($options['rememberme'])) $registerlink = $options['rememberme'] ? 'checked="checked"' : '';
		if (isset($options['rsslink'])) $rsslink = $options['rsslink'] ? 'checked="checked"' : '';
		if (isset($options['rsscommentlink'])) $rsscommentlink = $options['rsscommentlink'] ? 'checked="checked"' : '';
		if (isset($options['wordpresslink'])) $wordpresslink = $options['wordpresslink'] ? 'checked="checked"' : '';
		if (isset($options['lostpwlink'])) $lostpwlink = $options['lostpwlink'] ? 'checked="checked"' : '';
		
		?>
		<p><label for="minimeta-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="minimeta-title" name="minimeta-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<p style="text-align:right;margin-right:40px;"><label for="minimeta-loginlogout" style="text-align:right;"><?php _e('Login Type:');?>&nbsp;<input type="radio" name="minimeta-loginlogout" id="minimeta-loginlogout-link" value="link" <?php echo $loginlogoutLink; ?> />&nbsp;<?php _e('Link');?>&nbsp;&nbsp;<input type="radio" name="minimeta-loginlogout" id="minimeta-loginlogout-form" value="form" <?php echo $loginlogoutForm; ?> />&nbsp;<?php _e('Form');?>&nbsp;&nbsp;<input type="radio" name="minimeta-loginlogout" id="minimeta-loginlogout-off" value="off" <?php echo $loginlogoutOff; ?> />&nbsp;<?php _e('None');?>&nbsp</label></p>
		<p style="text-align:right;margin-right:40px;"><label for="minimeta-rememberme" style="text-align:right;"><?php _e('Show remember me:');?><input class="checkbox" type="checkbox" <?php echo $rememberme; ?> id="minimeta-rememberme" name="minimeta-rememberme" /></label></p>
		<p style="text-align:right;margin-right:40px;"><label for="minimeta-lostpwlink" style="text-align:right;"><?php _e('Show lost password:');?><input class="checkbox" type="checkbox" <?php echo $lostpwlink; ?> id="minimeta-lostpwlink" name="minimeta-lostpwlink" /></label></p>
		<p style="text-align:right;margin-right:40px;"><label for="minimeta-registerlink" style="text-align:right;"><?php _e('Show Register/Seite Admin:');?><input class="checkbox" type="checkbox" <?php echo $registerlink; ?> id="minimeta-registerlink" name="minimeta-registerlink" /></label></p>
		<p style="text-align:right;margin-right:40px;"><label for="minimeta-rsslink" style="text-align:right;"><?php _e('Show RSS2 Feed:');?><input class="checkbox" type="checkbox" <?php echo $rsslink; ?> id="minimeta-rsslink" name="minimeta-rsslink" /></label></p>
		<p style="text-align:right;margin-right:40px;"><label for="minimeta-rsscommentlink" style="text-align:right;"><?php _e('Show RSS2 Comments Feed:');?><input class="checkbox" type="checkbox" <?php echo $rsscommentlink; ?> id="minimeta-rsscommentlink" name="minimeta-rsscommentlink" /></label></p>
		<p style="text-align:right;margin-right:40px;"><label for="minimeta-wordpresslink" style="text-align:right;"><?php _e('Show link to Wordpress:');?><input class="checkbox" type="checkbox" <?php echo $wordpresslink; ?> id="minimeta-wordpresslink" name="minimeta-wordpresslink" /></label></p>
		<input type="hidden" id="minimeta-submit" name="minimeta-submit" value="1" />
		<?php
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Mini Meta', 'widgets'), 'widget_minimeta');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Mini Meta', 'widgets'), 'widget_minimeta_control', 300, 260);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_minnimeta_init');

?>
