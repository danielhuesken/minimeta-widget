<?PHP
    //Don´t show Wiget if it have no links
    if ((!is_user_logged_in() and !$optionset[$optionsetname]['loginlink'] and !$optionset[$optionsetname]['loginform'] and !$optionset[$optionsetname]['registerlink'] and !$optionset[$optionsetname]['rememberme'] and !$optionset[$optionsetname]['lostpwlink'] and !$optionset[$optionsetname]['rsslink'] and !$optionset[$optionsetname]['rsscommentlink'] and !$optionset[$optionsetname]['wordpresslink'] and !($optionset[$optionsetname]['showwpmeta'] and has_action('wp_meta')) or
        (is_user_logged_in() and !$optionset[$optionsetname]['logout'] and !$optionset[$optionsetname]['seiteadmin'] and sizeof($optionset[$optionsetname]['adminlinks'])==0 and !$optionset[$optionsetname]['rsslink'] and !$optionset[$optionsetname]['rsscommentlink'] and !$optionset[$optionsetname]['wordpresslink'] and !($optionset[$optionsetname]['showwpmeta'] and has_action('wp_meta'))))) 
            return;
    
	//create styles 
	unset($stylesheets);
	if (!empty($style)) {
		foreach ($styleset[$style] as $name => $value ) { 
			if (!empty($value) and $name!='stylename') $stylesheets[$name]=' style="'.$value.'"';
		}
	}
	
	//Shown part of Widget
    echo $before_widget;
        
        if(is_user_logged_in()) {
            if ($optionset[$optionsetname]['displayidentity'] and !empty($user_identity)) $optionset[$optionsetname]['title']=$user_identity;
            if($optionset[$optionsetname]['profilelink'] and current_user_can('read')) {
                echo $before_title ."<a href=\"".admin_url("/profile.php")."\" title=\"".__('Your Profile')."\">". $optionset[$optionsetname]['title'] ."</a>". $after_title; 
            } else {
            echo $before_title . $optionset[$optionsetname]['title'] . $after_title; 
            }
                echo "<ul".$stylesheets['ul'].">"; $endul=true;  
                if($optionset[$optionsetname]['seiteadmin']) echo "<li".$stylesheets['li']."><a href=\"".admin_url()."\"".$stylesheets['siteadmin'].">".__('Site Admin')."</a></li>";
                if($optionset[$optionsetname]['logout'] and $optionset[$optionsetname]['redirect']) echo "<li".$stylesheets['li']."><a href=\"".site_url('wp-login.php?action=logout&amp;redirect_to='.$_SERVER['REQUEST_URI'], 'login')."\"".$stylesheets['logout'].">".__('Log out')."</a></li>"; 
                if($optionset[$optionsetname]['logout'] and !$optionset[$optionsetname]['redirect']) echo "<li".$stylesheets['li']."><a href=\"".site_url('wp-login.php?action=logout', 'login')."\"".$stylesheets['logout'].">".__('Log out')."</a></li>"; 
             
                if (sizeof($optionset[$optionsetname]['adminlinks'])>0) { //show only if a Admin Link is selectesd
                 if ($optionset[$optionsetname]['useselectbox']) {
                    echo "<li".$stylesheets['adminlinksli']."><select onchange=\"document.location.href=this.options[this.selectedIndex].value;\"".$stylesheets['adminlinksselect']."><option selected=\"selected\"".$stylesheets['adminlinksoption'].">".__('Please select:','MiniMetaWidget')."</option>";
                 }
                 $adminlinks=get_option('minimeta_adminlinks'); 
                 foreach ($adminlinks as $menu) {
                  $output="";
                  foreach ($menu as $submenu) {
                    if(current_user_can($submenu[1]) and is_array($submenu) and in_array(wp_specialchars($submenu[2]),$optionset[$optionsetname]['adminlinks'])) {
                      if ($optionset[$optionsetname]['useselectbox']) {
                       $output.= "<option value=\"".admin_url("/".$submenu[2])."\"".$stylesheets['adminlinksoption'].">".$submenu[0]."</option>";
                      } else {
                       $output.= "<li".$stylesheets['adminlinksli']."><a href=\"".admin_url("/".$submenu[2])."\" title=\"".$submenu[0]."\"".$stylesheets['adminlinkshref'].">".$submenu[0]."</a></li>";
                      }
                    }
                  }
                  if (!empty($output) and !$optionset[$optionsetname]['notopics']) {
                    if ($optionset[$optionsetname]['useselectbox']) {
                        echo "<optgroup label=\"".$menu['menu']."\"".$stylesheets['adminlinksoptgroup'].">".$output."</optgroup>";
                    } else {
                        echo "<li".$stylesheets['adminlinkslitopic'].">".$menu['menu']."<ul".$stylesheets['adminlinksul'].">".$output."</ul></li>";
                    }
                   } else {
				     echo $output;
				   }    
                  }
                  if ($optionset[$optionsetname]['useselectbox']) {
                    echo "</select></li>";
                  }
                }
				if (!empty($optionset[$optionsetname]['linksin'])) wp_list_bookmarks('echo=1&title_li=&categorize=0&show_images=0&show_private=1&hide_invisible=0&orderby=name&include='.$optionset[$optionsetname]['linksin']);
         } else {
			echo $args['before_title'] . $optionset[$optionsetname]['title']. $args['after_title'];
            if($optionset[$optionsetname]['loginform']) {?>
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
                    <?php if($optionset[$optionsetname]['rememberme']) {?><p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> <?php _e('Remember Me'); ?></label></p><?php } ?>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Log in'); ?>" tabindex="100" />
                        <?php if($optionset[$optionsetname]['redirect']) {?><input type="hidden" name="redirect_to" value="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>" /><?php } ?>
                        <?php if($optionset[$optionsetname]['testcookie']) {?><input type="hidden" name="testcookie" value="1" /><?php } ?>
                    </p>
				</form><?php
			}
            $endul=false;
            if ($optionset[$optionsetname]['loginlink'] or $optionset[$optionsetname]['lostpwlink'] or ($optionset[$optionsetname]['registerlink'] and get_option('users_can_register')) or $optionset[$optionsetname]['rsslink'] or $optionset[$optionsetname]['rsscommentlink'] or $optionset[$optionsetname]['wordpresslink'] or ($optionset[$optionsetname]['showwpmeta'] and has_action('wp_meta'))) {
                echo "<ul".$stylesheets['ul'].">";
                $endul=true;
            }
            if($optionset[$optionsetname]['loginlink'] and $optionset[$optionsetname]['redirect']) echo "<li".$stylesheets['li']."><a href=\"".site_url('wp-login.php?action=login&amp;redirect_to='.$_SERVER['REQUEST_URI'], 'login')."\"".$stylesheets['login'].">".__('Log in')."</a></li>";
			if($optionset[$optionsetname]['loginlink'] and !$optionset[$optionsetname]['redirect']) echo "<li".$stylesheets['li']."><a href=\"".site_url('wp-login.php', 'login')."\"".$stylesheets['login'].">".__('Log in')."</a></li>";
            if($optionset[$optionsetname]['lostpwlink']) echo "<li".$stylesheets['li']."><a href=\"".site_url('wp-login.php?action=lostpassword', 'login')."\" title=\"".__('Password Lost and Found')."\"".$stylesheets['lostpw'].">".__('Lost your password?')."</a></li>";
			if($optionset[$optionsetname]['registerlink'] and get_option('users_can_register')) echo "<li".$stylesheets['li']."><a href=\"".site_url('wp-login.php?action=register', 'login')."\"".$stylesheets['register'].">" . __('Register') . "</a></li>";
		    if (!empty($optionset[$optionsetname]['linksout'])) wp_list_bookmarks('echo=1&title_li=&categorize=0&show_images=0&show_private=1&hide_invisible=0&orderby=name&include='.$optionset[$optionsetname]['linksout']);
		} 

		if($optionset[$optionsetname]['rsslink']) echo "<li".$stylesheets['li']."><a href=\"".get_bloginfo('rss2_url')."\" title=\"".attribute_escape(__('Syndicate this site using RSS 2.0'))."\"".$stylesheets['rss'].">".__('Entries <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($optionset[$optionsetname]['rsscommentlink']) echo "<li".$stylesheets['li']."><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".attribute_escape(__('The latest comments to all posts in RSS'))."\"".$stylesheets['commentrss'].">".__('Comments <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
		if($optionset[$optionsetname]['wordpresslink']) echo "<li".$stylesheets['li']."><a href=\"http://wordpress.org/\" title=\"".attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\"".$stylesheets['wporg'].">WordPress.org</a></li>";
		if($optionset[$optionsetname]['showwpmeta'] and has_action('wp_meta')) do_action('wp_meta');
		if ($endul) 
            echo "</ul>";
		echo $after_widget;
?>