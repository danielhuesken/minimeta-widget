<?PHP

class MiniMetaWidgetParts {


	//Loginform
	function loginform_display($args) {
		extract( (array)$args, EXTR_SKIP );
		?>
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
                    <?php if($rememberme) {?><p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> <?php _e('Remember Me'); ?></label></p><?php } ?>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Log in'); ?>" tabindex="100" />
                        <?php if($redirect) {?><input type="hidden" name="redirect_to" value="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>" /><?php } ?>
                        <?php if($testcookie) {?><input type="hidden" name="testcookie" value="1" /><?php } ?>
                    </p>
				</form>
		<?php
	}
	
	function loginform_options($args) {
		global $optionname,$loginout;
		extract( (array)$args, EXTR_SKIP );
		?>
			<input class="checkbox" type="checkbox" <?php echo checked($rememberme,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][loginform][args][rememberme]" />&nbsp;<?php _e('Remember me');?><br />
			<input class="checkbox" type="checkbox" <?php echo checked($redirect,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][loginform][args][redirect]" />&nbsp;<?php _e('Enable WordPress redirect function','MiniMetaWidget');?><br />
			<input class="checkbox" type="checkbox" <?php echo checked($testcookie,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][loginform][args][testcookie]" />&nbsp;<?php _e('Enable WordPress Cookie Test for login Form','MiniMetaWidget');?><br />
		<?PHP
	}
	
	//Seiteadmin Link
	function seteadmin_display($args) {
		extract( (array)$args, EXTR_SKIP );
		echo "<li".$stylesheets['li']."><a href=\"".admin_url()."\"".$stylesheets['siteadmin'].">".__('Site Admin')."</a></li>";
	}


	//Loginout Link
	function linkloginout_display($args) {
		extract( (array)$args, EXTR_SKIP );
		if(is_user_logged_in()) {
			if($redirect) echo "<li".$stylesheets['li']."><a href=\"".wp_nonce_url(site_url('wp-login.php?action=logout&amp;redirect_to='.$_SERVER['REQUEST_URI'], 'login'), 'log-out')."\"".$stylesheets['logout'].">".__('Log out')."</a></li>"; 
            if(!$redirect) echo "<li".$stylesheets['li']."><a href=\"".wp_nonce_url(site_url('wp-login.php?action=logout', 'login'), 'log-out')."\"".$stylesheets['logout'].">".__('Log out')."</a></li>"; 
		} else {
			if($redirect) echo "<li".$stylesheets['li']."><a href=\"".wp_nonce_url(site_url('wp-login.php?action=login&amp;redirect_to='.$_SERVER['REQUEST_URI'], 'login'), 'login')."\"".$stylesheets['login'].">".__('Log in')."</a></li>";
			if(!$redirect) echo "<li".$stylesheets['li']."><a href=\"".wp_nonce_url(site_url('wp-login.php', 'login'), 'login')."\"".$stylesheets['login'].">".__('Log in')."</a></li>";		
		}
	}
	
	function linkloginout_options($args) {
		global $optionname,$loginout;
		extract( (array)$args, EXTR_SKIP );
		?>
		<input class="checkbox" type="checkbox" <?php echo checked($redirect,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][linkloginlogout][args][redirect]" />&nbsp;<?php _e('Enable WordPress redirect function','MiniMetaWidget');?><br />
		<?PHP
	}

	//Adminlinks
	function adminlinks_display($args) {
		extract( (array)$args, EXTR_SKIP );
		
               if (sizeof($adminlinks)>0) { //show only if a Admin Link is selectesd
                 if ($useselectbox) {
                    echo "<li".$stylesheets['adminlinksli']."><select onchange=\"document.location.href=this.options[this.selectedIndex].value;\"".$stylesheets['adminlinksselect']."><option selected=\"selected\"".$stylesheets['adminlinksoption'].">".__('Please select:','MiniMetaWidget')."</option>";
                 }
                 $minimeta_adminlinks=get_option('minimeta_adminlinks'); 
                 foreach ($minimeta_adminlinks as $menu) {
                  $output="";
                  foreach ($menu as $submenu) {
                    if(current_user_can($submenu[1]) and is_array($submenu) and in_array(wp_specialchars($submenu[2]),$adminlinks)) {
                      if ($useselectbox) {
                       $output.= "<option value=\"".admin_url("/".$submenu[2])."\"".$stylesheets['adminlinksoption'].">".$submenu[0]."</option>";
                      } else {
                       $output.= "<li".$stylesheets['adminlinksli']."><a href=\"".admin_url("/".$submenu[2])."\" title=\"".$submenu[0]."\"".$stylesheets['adminlinkshref'].">".$submenu[0]."</a></li>";
                      }
                    }
                  }
                  if (!empty($output) and !$notopics) {
                    if ($useselectbox) {
                        echo "<optgroup label=\"".$menu['menu']."\"".$stylesheets['adminlinksoptgroup'].">".$output."</optgroup>";
                    } else {
                        echo "<li".$stylesheets['adminlinkslitopic'].">".$menu['menu']."<ul".$stylesheets['adminlinksul'].">".$output."</ul></li>";
                    }
                   } else {
				     echo $output;
				   }    
                  }
                  if ($useselectbox) {
                    echo "</select></li>";
                  }
                }
	}	
	
	function adminlinks_options($args) {
		global $optionname,$loginout;
		extract( (array)$args, EXTR_SKIP );
		?>
 		 <input class="checkbox" type="checkbox" <?php echo checked($useselectbox,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][adminlinks][args][useselectbox]" />&nbsp;<?php _e('Use Select Box for Admin Links','MiniMetaWidget');?><br />
         <input class="checkbox" type="checkbox" <?php echo checked($notopics,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][adminlinks][args][notopics]" />&nbsp;<?php _e('Do not show Admin Links Topics','MiniMetaWidget');?><br />
		 
		 <?php _e('Select Admin Links:','MiniMetaWidget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $number; ?> > optgroup >option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $number; ?> > optgroup > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
         <select class="select" style="height:120px;width:90%" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][adminlinks][args][adminlinks][]" id="minimeta-adminlinks-<?php echo $number; ?>" multiple="multiple">
         <?PHP
			$minimeta_adminlinks=get_option('minimeta_adminlinks');
            foreach ($minimeta_adminlinks as $menu) {
             echo "<optgroup label=\"".$menu['menu']."\">";
             foreach ($menu as $submenu) {
              if (is_array($submenu)) {
               $checkadminlinks=in_array($submenu[2],(array)$adminlinks) ? ' selected="selected"' : '';
               echo "<option value=\"".$submenu[2]."\"".$checkadminlinks.">".$submenu[0]."</option>";
              }
             }
             echo "</optgroup>";
            }        
         ?>  
         </select>
		 <?PHP
	}	
	
	
	//Lostpw Link
	function linklostpw_display($args) {
		extract( (array)$args, EXTR_SKIP );
		echo "<li".$stylesheets['li']."><a href=\"".site_url('wp-login.php?action=lostpassword', 'login')."\" title=\"".__('Password Lost and Found')."\"".$stylesheets['lostpw'].">".__('Lost your password?')."</a></li>";
	}	

	//register Link
	function linkregister_display($args) {
		extract( (array)$args, EXTR_SKIP );
		if(get_option('users_can_register')) 
			echo "<li".$stylesheets['li']."><a href=\"".site_url('wp-login.php?action=register', 'login')."\"".$stylesheets['register'].">" . __('Register') . "</a></li>";
	}		

		
	//WP Bokmarks 
	function bookmarks_display($args) {
		extract( (array)$args, EXTR_SKIP );
		if (is_array($links))
			wp_list_bookmarks('echo=1&title_li=&categorize=0&show_images=0&show_private=1&hide_invisible=0&orderby=name&include='.implode(',',$links));
	}		
	
	function bookmarks_options($args) {
		global $optionname,$loginout;
		extract( (array)$args, EXTR_SKIP );
		 _e('Select Links to Display:','MiniMetaWidget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-links<?php echo $loginout; ?>-<?php echo $number; ?> > option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-links<?php echo $loginout; ?>-<?php echo $number; ?> > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
         <select class="select" style="height:70px;width:90%" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][bookmarks][args][links][]" id="minimeta-links<?php echo $loginout; ?>-<?php echo $number; ?>" multiple="multiple">
         <?PHP
            $bookmarks=get_bookmarks(array('hide_invisible' => 0,'orderby' =>'name'));
			foreach ($bookmarks as $linksdisplay) {
               $checklinksout = in_array($linksdisplay->link_id,(array)$links) ? ' selected="selected"' : '';
               echo "<option value=\"".$linksdisplay->link_id."\"".$checklinksout.">". wp_specialchars($linksdisplay->link_name)."</option>";
            }        
         ?>  
         </select>
		 <?PHP
	}

	//RSS Link
	function linkrss_display($args) {
		extract( (array)$args, EXTR_SKIP );
		echo "<li".$stylesheets['li']."><a href=\"".get_bloginfo('rss2_url')."\" title=\"".attribute_escape(__('Syndicate this site using RSS 2.0'))."\"".$stylesheets['rss'].">".__('Entries <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
	}		
		
	//Comment RSS Link
	function linkcommentrss_display($args) {
		extract( (array)$args, EXTR_SKIP );
		echo "<li".$stylesheets['li']."><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".attribute_escape(__('The latest comments to all posts in RSS'))."\"".$stylesheets['commentrss'].">".__('Comments <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
	}	
		
	//Wordpress Link
	function linkwordpress_display($args) {
		extract( (array)$args, EXTR_SKIP );
		echo "<li".$stylesheets['li']."><a href=\"http://wordpress.org/\" title=\"".attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\"".$stylesheets['wporg'].">WordPress.org</a></li>";
	}
			
	//action wp_meta
	function actionwpmeta_display() {
		if(has_action('wp_meta')) 
			do_action('wp_meta');
	}	
	
	function parts() {
		//$MiniMetaWidgetParts['name']=array('name','function to display','function to control','logtin','logtout','ul')
		$MiniMetaWidgetParts['loginform']=array(__('Login Form'),array('MiniMetaWidgetParts','loginform_display'),array('MiniMetaWidgetParts','loginform_options'),false,true,false);
		$MiniMetaWidgetParts['linkseiteadmin']=array(__('Link:','MiniMetaWidget').' '.__('Site Admin'),array('MiniMetaWidgetParts','seteadmin_display'),'',true,false,true);
		$MiniMetaWidgetParts['linkloginlogout']=array(__('Link: Login/Logout'),array('MiniMetaWidgetParts','linkloginout_display'),array('MiniMetaWidgetParts','linkloginout_options'),true,true,true);
		$MiniMetaWidgetParts['adminlinks']=array(__('Adminlinks'),array('MiniMetaWidgetParts','adminlinks_display'),array('MiniMetaWidgetParts','adminlinks_options'),true,false,true);
		$MiniMetaWidgetParts['linklostpw']=array(__('Link:','MiniMetaWidget').' '.__('Lost your password?'),array('MiniMetaWidgetParts','linklostpw_display'),'',false,true,true);
		$MiniMetaWidgetParts['linkregister']=array(__('Link:','MiniMetaWidget').' '.__('Register'),array('MiniMetaWidgetParts','linkregister_display'),'',false,true,true);
		$MiniMetaWidgetParts['bookmarks']=array(__('Blog Links'),array('MiniMetaWidgetParts','bookmarks_display'),array('MiniMetaWidgetParts','bookmarks_options'),true,true,true);
		$MiniMetaWidgetParts['linkrss']=array(__('Link:','MiniMetaWidget').' '.__('Entries <abbr title="Really Simple Syndication">RSS</abbr>'),array('MiniMetaWidgetParts','linkrss_display'),'',true,true,true);
		$MiniMetaWidgetParts['linkcommentrss']=array(__('Link:','MiniMetaWidget').' '.__('Comments <abbr title="Really Simple Syndication">RSS</abbr>'),array('MiniMetaWidgetParts','linkcommentrss_display'),'',true,true,true);
		$MiniMetaWidgetParts['linkwordpress']=array(__('Link:','MiniMetaWidget').' WordPress.org',array('MiniMetaWidgetParts','linkwordpress_display'),'',true,true,true);
		$MiniMetaWidgetParts['actionwpmeta']=array(__('Do Action:','MiniMetaWidget').' wp_meta',array('MiniMetaWidgetParts','actionwpmeta_display'),'',true,true,true);
		return $MiniMetaWidgetParts;
	}
	
}



?>