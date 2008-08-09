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