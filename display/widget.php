<?PHP
   //Don´t show Wiget if it have no links
    if ((!is_user_logged_in() and !$options['loginlink'] and !$options['loginform'] and !$options['registerlink'] and !$options['rememberme'] and !$options['lostpwlink'] and !$options['rsslink'] and !$options['rsscommentlink'] and !$options['wordpresslink'] and !($options['showwpmeta'] and has_action('wp_meta')) or
        (is_user_logged_in() and !$options['logout'] and !$options['seiteadmin'] and sizeof($options['adminlinks'])==0 and !$options['rsslink'] and !$options['rsscommentlink'] and !$options['wordpresslink'] and !($options['showwpmeta'] and has_action('wp_meta'))))) 
            return;

	//Shown part of Widget
    echo $before_widget;
        
        if(is_user_logged_in()) {
            if ($options['displayidentity'] and !empty($user_identity)) $options['title']=$user_identity;
            if($options['profilelink'] and current_user_can('read')) {
                echo $before_title ."<a href=\"".get_bloginfo('wpurl')."/wp-admin/profile.php\" title=\"".__('Your Profile')."\">". $options['title'] ."</a>". $after_title; 
            } else {
            echo $before_title . $options['title'] . $after_title; 
            }
                echo "<ul>"; $endul=true;  
                if($options['seiteadmin']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-admin/\" class=\"minimeta-siteadmin\">".__('Site Admin')."</a></li>";
                if($options['logout'] and $options['redirect']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=logout&amp;redirect_to=".$_SERVER['REQUEST_URI']."\" class=\"minimeta-logout\">".__('Log out')."</a></li>"; 
                if($options['logout'] and !$options['redirect']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=logout\" class=\"minimeta-logout\">".__('Log out')."</a></li>"; 
             
                if (sizeof($options['adminlinks'])>0) { //show only if a Admin Link is selectesd
                 if ($options['useselectbox']) {
                    echo "<li class=\"minimeta-adminlinks\"><select class=\"minimeta-adminlinks\" tabindex=\"95\" onchange=\"window.location = this.value\"><option selected=\"selected\">".__('Please select:','MiniMetaWidget')."</option>";
                 }
                 $adminlinks=get_option('widget_minimeta_adminlinks'); 
                 foreach ($adminlinks as $menu) {
                  $output="";
                  foreach ($menu as $submenu) {
                    if(current_user_can($submenu[1]) and is_array($submenu) and in_array(wp_specialchars($submenu[2]),$options['adminlinks'])) {
                      if ($options['useselectbox']) {
                       $output.= "<option value=\"".get_bloginfo('wpurl')."/wp-admin/".$submenu[2]."\" class=\"minimeta-adminlinks\">".$submenu[0]."</option>";
                      } else {
                       $output.= "<li class=\"minimeta-adminlinks\"><a href=\"".get_bloginfo('wpurl')."/wp-admin/".$submenu[2]."\" title=\"".$submenu[0]."\" class=\"minimeta-adminlinks\">".$submenu[0]."</a></li>";
                      }
                    }
                  }
                  if (!empty($output) and !$options['notopics']) {
                    if ($options['useselectbox']) {
                        echo "<optgroup label=\"".$menu['menu']."\" class=\"minimeta-adminlinks\">".$output."</optgroup>";
                    } else {
                        echo "<li class=\"minimeta-adminlinks_menu\">".$menu['menu']."<ul class=\"minimeta-adminlinks\">".$output."</ul></li>";
                    }
                   }     
                  }
                  if ($options['useselectbox']) {
                    echo "</select></li>";
                  }
                }
         } else {
			echo $args['before_title'] . $options['title']. $args['after_title'];
            if($options['loginform']) {?>
				<form name="loginform" id="loginform" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
                    <p>
                        <label><?php _e('Username') ?><br />
                        <input type="text" name="log" id="user_login" class="input" value="<?php echo $user_login; ?>" size="20" tabindex="10" /></label>
                    </p>
                    <p>
                        <label><?php _e('Password') ?><br />
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
                    </p>
                    <?php do_action('login_form'); ?>
                    <?php if($options['rememberme']) {?><p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> <?php _e('Remember Me'); ?></label></p><?php } ?>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Log in'); ?>" tabindex="100" />
                        <?php if($options['redirect']) {?><input type="hidden" name="redirect_to" value="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>" /><?php } ?>
                        <?php if($options['testcookie']) {?><input type="hidden" name="testcookie" value="1" /><?php } ?>
                    </p>
				</form><?php
			}
            $endul=false;
            if ($options['loginlink'] or $options['lostpwlink'] or ($options['registerlink'] and get_option('users_can_register')) or $options['rsslink'] or $options['rsscommentlink'] or $options['wordpresslink'] or ($options['showwpmeta'] and has_action('wp_meta'))) {
                echo "<ul>";
                $endul=true;
            }
            if($options['loginlink'] and $options['redirect']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=login&amp;redirect_to=".attribute_escape($_SERVER['REQUEST_URI'])."\" class=\"minimeta-login\">".__('Log in')."</a></li>";
			if($options['loginlink'] and !$options['redirect']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php\" class=\"minimeta-login\">".__('Log in')."</a></li>";
            if($options['lostpwlink']) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=lostpassword\" title=\"".__('Password Lost and Found')."\" class=\"minimeta-lostpw\">".__('Lost your password?')."</a></li>";
			if($options['registerlink'] and get_option('users_can_register')) echo "<li><a href=\"".get_bloginfo('wpurl')."/wp-login.php?action=register\" class=\"minimeta-register\">" . __('Register') . "</a></li>";
		} 

		if($options['rsslink']) echo "<li><a href=\"".get_bloginfo('rss2_url')."\" title=\"".attribute_escape(__('Syndicate this site using RSS 2.0'))."\" class=\"minimeta-rss\">".__('Entries <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options['rsscommentlink']) echo "<li><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".attribute_escape(__('The latest comments to all posts in RSS'))."\" class=\"minimeta-commentsrss\">".__('Comments <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($options['wordpresslink']) echo "<li><a href=\"http://wordpress.org/\" title=\"".attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\" class=\"minimeta-wporg\">WordPress.org</a></li>";
		if($options['showwpmeta'] and has_action('wp_meta')) do_action('wp_meta');
		if ($endul) 
            echo "</ul>";
		echo $after_widget;
?>