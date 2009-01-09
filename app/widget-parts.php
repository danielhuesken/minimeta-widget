<?PHP

class MiniMetaWidgetParts {
	//Title
	function title_display($args) {
		global $user_identity;
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if(is_user_logged_in()) {
			if ($displayidentity and !empty($user_identity)) $title=$user_identity;
            if ($profilelink and current_user_can('read')) {
                echo $before_title ."<a href=\"".admin_url("/profile.php")."\" title=\"".__('Your Profile')."\">". $title ."</a>". $after_title; 
            } else {
				echo $before_title . $title . $after_title; 
            }
		} else {
			echo $before_title . $title . $after_title;
		}
	}
	
	function title_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if ($loginout=='in') {
			?>
			<input class="checkbox" type="checkbox" <?php echo checked($displayidentity,true); ?> id="minimeta-displayidentity-<?php echo $optionname; ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][displayidentity]" />&nbsp;<?php _e('Disply user Identity as title','MiniMetaWidget');?><br />
			<input class="checkbox" type="checkbox" <?php echo checked($profilelink,true); ?> id="minimeta-profilelink-<?php echo $optionname; ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][profilelink]" />&nbsp;<?php _e('Link to Your Profile in title','MiniMetaWidget');?><br />	
			<?PHP
		}
	}
	
	//Loginform
	function loginform_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$stylelogin=!empty($stylelogin)?' style="'.$stylelogin.'"':'';
		$stylepassword=!empty($stylepassword)?' style="'.$stylepassword.'"':'';
		$stylerememberme=!empty($stylerememberme)?' style="'.$stylerememberme.'"':'';
		?>
				<form name="loginform" id="loginform" action="<?php echo site_url('wp-login.php', 'login_post') ?>" method="post">
                    <p>
                        <label><?php _e('Username') ?><br />
                        <input type="text" name="log" id="user_login" class="input" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" size="20" tabindex="10"<?php echo $stylelogin; ?> /></label>
                    </p>
                    <p>
                        <label><?php _e('Password') ?><br />
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20"<?php echo $stylepassword; ?> /></label>
                    </p>
                    <?php do_action('login_form'); ?>
                    <?php if($rememberme) {?><p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90"<?php echo $stylerememberme; ?> /> <?php _e('Remember Me'); ?></label></p><?php } ?>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Log in'); ?>" tabindex="100" />
                        <?php if($redirect) {?><input type="hidden" name="redirect_to" value="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>" /><?php } ?>
                        <?php if($testcookie) {?><input type="hidden" name="testcookie" value="1" /><?php } ?>
                    </p>
				</form>
		<?php
	}
	
	function loginform_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
			<input class="checkbox" type="checkbox" <?php echo checked($rememberme,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][rememberme]" />&nbsp;<?php _e('Remember Me');?><br />
			<input class="checkbox" type="checkbox" <?php echo checked($redirect,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][redirect]" />&nbsp;<?php _e('Enable WordPress redirect function','MiniMetaWidget');?><br />
			<input class="checkbox" type="checkbox" <?php echo checked($testcookie,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][testcookie]" />&nbsp;<?php _e('Enable WordPress Cookie Test for login Form','MiniMetaWidget');?><br />
			<hr />
		<?php 
		 _e('Stylesheet:','MiniMetaWidget');?><br /> 
		&lt;input type="text" (<?php _e('Username') ?>)
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelogin)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][stylelogin]" /><br />
		&lt;input type="password" (<?php _e('Password') ?>)
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylepassword)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][stylepassword]" /><br />
		&lt;input type="checkbox" (<?php _e('Remember Me') ?>)
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylerememberme)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][stylerememberme]" /><br />
		<?PHP
	}
	
	//Seiteadmin Link
	function seiteadmin_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$styleseiteadmin=!empty($styleseiteadmin)?' style="'.$styleseiteadmin.'"':'';
		echo "<li".$stylegeneralli."><a href=\"".admin_url()."\"".$styleseiteadmin.">".__('Site Admin')."</a></li>";
	}

	function seiteadmin_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Stylesheet:','MiniMetaWidget');?> &lt;a href...
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleseiteadmin)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleseiteadmin]" /><br />
		<?PHP
	}
	
	//Loginout Link
	function linkloginout_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$styleloginout=!empty($styleloginout)?' style="'.$styleloginout.'"':'';
		$redirect = $redirect ? $_SERVER['REQUEST_URI'] : '';
		if(is_user_logged_in()) {
			echo "<li".$stylegeneralli."><a href=\"".wp_logout_url($redirect)."\"".$styleloginout.">".__('Log out')."</a></li>";
		} else {
			echo "<li".$stylegeneralli."><a href=\"".wp_login_url($redirect)."\"".$styleloginout.">".__('Log in')."</a></li>";
		}
	}
	
	function linkloginout_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
		<input class="checkbox" type="checkbox" <?php echo checked($redirect,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][redirect]" />&nbsp;<?php _e('Enable WordPress redirect function','MiniMetaWidget');?><br />
		<hr />
		<?php if (!isset($styleloginout)) $styleloginout='color:red;'; //def. Css
		 _e('Stylesheet:','MiniMetaWidget');?> &lt;a href...
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleloginout)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleloginout]" /><br />
		<?PHP
	}

	//Adminlinks
	function adminlinks_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		//prefiy style if it issent empty
		$styleadminlinkslitopic=!empty($styleadminlinkslitopic)?' style="'.$styleadminlinkslitopic.'"':'';
		$styleadminlinksul=!empty($styleadminlinksul)?' style="'.$styleadminlinksul.'"':'';
		$styleadminlinksli=!empty($styleadminlinksli)?' style="'.$styleadminlinksli.'"':'';
		$styleadminlinkshref=!empty($styleadminlinkshref)?' style="'.$styleadminlinkshref.'"':'';
		
        if (sizeof($adminlinks)>0 or $notselected) { //show only if a Admin Link is selectesd
			$minimeta_adminlinks=get_option('minimeta_adminlinks'); 
			foreach ($minimeta_adminlinks as $menu) {
				$output="";
				foreach ($menu as $submenu) {
					if(current_user_can($submenu[1]) and is_array($submenu) and ((!$notselected and in_array(wp_specialchars($submenu[2]),$adminlinks)) or ($notselected and !in_array(wp_specialchars($submenu[2]),$adminlinks)))) {
						$output.= "<li".$styleadminlinksli."><a href=\"".admin_url("/".$submenu[2])."\" title=\"".$submenu[0]."\"".$styleadminlinkshref.">".$submenu[0]."</a></li>";
					}
				}
				if (!empty($output) and !$notopics) {
					echo "<li".$styleadminlinkslitopic.">".$menu['menu']."<ul".$styleadminlinksul.">".$output."</ul></li>";
				} else {
					echo $output;
				}    
			}
        }
	}	
	
	function adminlinks_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
        <input class="checkbox" type="checkbox" <?php echo checked($notopics,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][notopics]" />&nbsp;<?php _e('Do not show Admin Links Topics','MiniMetaWidget');?><br />
		<input class="checkbox" type="checkbox" <?php echo checked($notselected,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][notselected]" />&nbsp;<?php _e('Display <b>not</b> selected Admin Links','MiniMetaWidget');?><br />
		 <?php _e('Select Admin Links:','MiniMetaWidget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?> > optgroup >option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?> > optgroup > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
         <select style="height:120px;font-size:11px;" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][adminlinks][]" id="minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?>" multiple="multiple">
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
		<hr />
		<?php 
		if (!isset($styleadminlinkslitopic)) $styleadminlinkslitopic='font-weight:bold;font-style:italic;'; //def. Css
		if (!isset($styleadminlinksli)) $styleadminlinksli='font-weight:normal;font-style:normal;';
		if (!isset($styleadminlinksselect)) $styleadminlinksselect='font-size:10px;';
		
		_e('Stylesheet:','MiniMetaWidget'); echo '<br />';
		_e('topic','MiniMetaWidget');?>&nbsp;&lt;li&gt;
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinkslitopic)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleadminlinkslitopic]" /><br />
		&lt;ul&gt;
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksul)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleadminlinksul]" /><br />
		&lt;li&gt;
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksli)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleadminlinksli]" /><br />
		&lt;a href...
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinkshref)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleadminlinkshref]" /><br />
		<?PHP
	}	
	
	//Adminlinks
	function adminselect_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		//prefiy style if it issent empty
		$styleadminlinksselect=!empty($styleadminlinksselect)?' style="'.$styleadminlinksselect.'"':'';
		$styleadminlinksoptgroup=!empty($styleadminlinksoptgroup)?' style="'.$styleadminlinksoptgroup.'"':'';
		$styleadminlinksoption=!empty($styleadminlinksoption)?' style="'.$styleadminlinksoption.'"':'';
		
        if (sizeof($adminlinks)>0 or $notselected) { //show only if a Admin Link is selectesd
            echo "<li".$stylegeneralli."><select onchange=\"document.location.href=this.options[this.selectedIndex].value;\"".$styleadminlinksselect."><option selected=\"selected\"".$styleadminlinksoption.">".__('Please select:','MiniMetaWidget')."</option>";
			$minimeta_adminlinks=get_option('minimeta_adminlinks'); 
            foreach ($minimeta_adminlinks as $menu) {
            $output="";
                foreach ($menu as $submenu) {
					if(current_user_can($submenu[1]) and is_array($submenu) and ((!$notselected and in_array(wp_specialchars($submenu[2]),$adminlinks)) or ($notselected and !in_array(wp_specialchars($submenu[2]),$adminlinks)))) {
						$output.= "<option value=\"".admin_url("/".$submenu[2])."\"".$styleadminlinksoption.">".$submenu[0]."</option>";
                    }
                }
                if (!empty($output) and !$notopics) {
                    echo "<optgroup label=\"".$menu['menu']."\"".$styleadminlinksoptgroup.">".$output."</optgroup>";
                } else {
				     echo $output;
				}    
            }
            echo "</select></li>";
        }
	}	
	
	function adminselect_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
         <input class="checkbox" type="checkbox" <?php echo checked($notopics,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][notopics]" />&nbsp;<?php _e('Do not show Admin Links Topics','MiniMetaWidget');?><br />
		 <input class="checkbox" type="checkbox" <?php echo checked($notselected,true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][notselected]" />&nbsp;<?php _e('Display <b>not</b> selected Admin Links','MiniMetaWidget');?><br />
		 <?php _e('Select Admin Links:','MiniMetaWidget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?> > optgroup >option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?> > optgroup > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
         <select style="height:120px;font-size:11px;" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][adminlinks][]" id="minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?>" multiple="multiple">
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
		<hr />
		<?php 
		if (!isset($styleadminlinksselect)) $styleadminlinksselect='font-size:10px;';
		
		_e('Stylesheet:','MiniMetaWidget'); 
		?><br />
		&lt;select&gt;
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksselect)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleadminlinksselect]" /><br />
		&lt;optiongroup&gt;
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksoptgroup)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleadminlinksoptgroup]" /><br />
		&lt;option&gt;
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksoption)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][styleadminlinksoption]" /><br />
		<?PHP
	}	
	
	//Lostpw Link
	function linklostpw_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$stylelinklostpw=!empty($stylelinklostpw)?' style="'.$stylelinklostpw.'"':'';
		echo "<li".$stylegeneralli."><a href=\"".site_url('wp-login.php?action=lostpassword', 'login')."\" title=\"".__('Password Lost and Found')."\"".$styleslinklostpw.">".__('Lost your password?')."</a></li>";
	}	
	
	function linklostpw_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Stylesheet:','MiniMetaWidget');?> &lt;a href...
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinklostpw)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][stylelinklostpw]" /><br />
		<?PHP
	}
	
	//register Link
	function linkregister_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$stylelinkregister=!empty($stylelinkregister)?' style="'.$stylelinkregister.'"':'';
		if(get_option('users_can_register')) 
			echo "<li".$stylegeneralli."><a href=\"".site_url('wp-login.php?action=register', 'login')."\"".$stylelinkregister.">" . __('Register') . "</a></li>";
	}		

	function linkregister_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Stylesheet:','MiniMetaWidget');?> &lt;a href...
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinkregister)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][stylelinkregister]" /><br />
		<?PHP
	}
	
	//WP Bokmarks 
	function bookmarks_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (is_array($links))
			wp_list_bookmarks('echo=1&title_li=&before=<li'.$stylegeneralli.'>&categorize=0&show_images=0&show_private=1&hide_invisible=0&orderby=name&include='.implode(',',$links));
	}		
	
	function bookmarks_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Select Links to Display:','MiniMetaWidget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-links<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?> > option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-links<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?> > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
        <select style="height:70px;font-size:11px;" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][links][]" id="minimeta-links<?php echo $optionname; ?>-<?php echo $loginout; ?>-<?php echo $ordering; ?>" multiple="multiple">
        <?PHP
        $bookmarks=get_bookmarks(array('hide_invisible' => 0,'orderby' =>'name'));
		foreach ($bookmarks as $linksdisplay) {
            $checklinksout = in_array($linksdisplay->link_id,(array)$links) ? ' selected="selected"' : '';
            echo "<option value=\"".$linksdisplay->link_id."\"".$checklinksout.">". wp_specialchars($linksdisplay->link_name)." - ".wp_specialchars($linksdisplay->link_url)."</option>";
        }        
        ?>  
        </select>
		<?PHP
	}

	//RSS Link
	function linkrss_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$stylelinkrss=!empty($stylelinkrss)?' style="'.$stylelinkrss.'"':'';
		echo "<li".$stylegeneralli."><a href=\"".get_bloginfo('rss2_url')."\" title=\"".attribute_escape(__('Syndicate this site using RSS 2.0'))."\"".$stylelinkrss.">".__('Entries <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
	}			
	
	function linkrss_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Stylesheet:','MiniMetaWidget');?> &lt;a href...
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinkrss)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][stylelinkrss]" /><br />
		<?PHP
	}
		
	//Comment RSS Link
	function linkcommentrss_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$stylelinkcommentrss=!empty($stylelinkcommentrss)?' style="'.$stylelinkcommentrss.'"':'';
		echo "<li".$stylegeneralli."><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".attribute_escape(__('The latest comments to all posts in RSS'))."\"".$stylelinkcommentrss.">".__('Comments <abbr title="Really Simple Syndication">RSS</abbr>')."</a></li>";
	}	
	
	function linkcommentrss_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Stylesheet:','MiniMetaWidget');?> &lt;a href...
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinkcommentrss)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][stylelinkcommentrss]" /><br />
		<?PHP
	}
		
	//Wordpress Link
	function linkwordpress_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$stylelinkwordpress=!empty($stylelinkwordpress)?' style="'.$stylelinkwordpress.'"':'';
		echo "<li".$stylegeneralli."><a href=\"http://wordpress.org/\" title=\"".attribute_escape(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\"".$stylelinkwordpress.">WordPress.org</a></li>";
	}
	
	function linkwordpress_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Stylesheet:','MiniMetaWidget');?> &lt;a href...
		<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinkwordpress)); ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][args][stylelinkwordpress]" /><br />
		<?PHP
	}
	
	//action wp_meta
	function actionwpmeta_display() {
		if(has_action('wp_meta')) 
			do_action('wp_meta');
	}	
	
	function parts() {
		//$MiniMetaWidgetParts['name']=array('name','function to display','function to control','logtin','logtout','ul')
		$MiniMetaWidgetParts['title']=array(__('Title'),array('MiniMetaWidgetParts','title_display'),array('MiniMetaWidgetParts','title_options'),true,true,false);
		$MiniMetaWidgetParts['loginform']=array(__('Login Form','MiniMetaWidget'),array('MiniMetaWidgetParts','loginform_display'),array('MiniMetaWidgetParts','loginform_options'),false,true,false);
		$MiniMetaWidgetParts['linkseiteadmin']=array(__('Link:','MiniMetaWidget').' '.__('Site Admin'),array('MiniMetaWidgetParts','seiteadmin_display'),array('MiniMetaWidgetParts','seiteadmin_options'),true,false,true);
		$MiniMetaWidgetParts['linkregister']=array(__('Link:','MiniMetaWidget').' '.__('Register'),array('MiniMetaWidgetParts','linkregister_display'),array('MiniMetaWidgetParts','linkregister_options'),false,true,true);
		$MiniMetaWidgetParts['linkloginlogout']=array(__('Link: Login/Logout','MiniMetaWidget'),array('MiniMetaWidgetParts','linkloginout_display'),array('MiniMetaWidgetParts','linkloginout_options'),true,true,true);
		$MiniMetaWidgetParts['adminlinks']=array(__('Adminlinks as Links','MiniMetaWidget'),array('MiniMetaWidgetParts','adminlinks_display'),array('MiniMetaWidgetParts','adminlinks_options'),true,false,true);
		$MiniMetaWidgetParts['adminselect']=array(__('Adminlinks as Selectbox','MiniMetaWidget'),array('MiniMetaWidgetParts','adminselect_display'),array('MiniMetaWidgetParts','adminselect_options'),true,false,true);
		$MiniMetaWidgetParts['linklostpw']=array(__('Link:','MiniMetaWidget').' '.__('Lost your password?'),array('MiniMetaWidgetParts','linklostpw_display'),array('MiniMetaWidgetParts','linklostpw_options'),false,true,true);
		$MiniMetaWidgetParts['bookmarks']=array(__('Blog Links','MiniMetaWidget'),array('MiniMetaWidgetParts','bookmarks_display'),array('MiniMetaWidgetParts','bookmarks_options'),true,true,true);
		$MiniMetaWidgetParts['linkrss']=array(__('Link:','MiniMetaWidget').' '.__('Entries <abbr title="Really Simple Syndication">RSS</abbr>'),array('MiniMetaWidgetParts','linkrss_display'),array('MiniMetaWidgetParts','linkrss_options'),true,true,true);
		$MiniMetaWidgetParts['linkcommentrss']=array(__('Link:','MiniMetaWidget').' '.__('Comments <abbr title="Really Simple Syndication">RSS</abbr>'),array('MiniMetaWidgetParts','linkcommentrss_display'),array('MiniMetaWidgetParts','linkcommentrss_options'),true,true,true);
		$MiniMetaWidgetParts['linkwordpress']=array(__('Link:','MiniMetaWidget').' WordPress.org',array('MiniMetaWidgetParts','linkwordpress_display'),array('MiniMetaWidgetParts','linkwordpress_options'),true,true,true);
		$MiniMetaWidgetParts['actionwpmeta']=array(__('Do Action:','MiniMetaWidget').' wp_meta',array('MiniMetaWidgetParts','actionwpmeta_display'),'',true,true,true);
		return apply_filters('MiniMetaWidget_parts',$MiniMetaWidgetParts);
	}
	
}



?>