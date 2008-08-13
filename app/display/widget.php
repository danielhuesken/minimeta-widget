<?PHP
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
?>