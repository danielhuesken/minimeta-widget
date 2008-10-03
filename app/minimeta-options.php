<?php
//Variables Variables Variables
$id = intval($_GET['id']);
$mode = trim($_GET['mode']);
$views_settings = array('minimeta_widget_wp','minimeta_widget_options', 'minimeta_adminlinks');

// Form Processing
// Update Options
if(!empty($_POST['Submit']) and current_user_can('switch_themes')) {
	check_admin_referer('MiniMeta-options');
	
	$update_views_queries = array();
	$update_views_text = array();
	
	//Set default to def. options
	unset($options_widgets['default']);
	$options_widgets['default']['optionname']='default';
	$options_widgets['default']['loginlink']=true;
	$options_widgets['default']['loginform']=false;
	$options_widgets['default']['logout']=true; 
	$options_widgets['default']['registerlink']=true;
	$options_widgets['default']['testcookie']=false; 
	$options_widgets['default']['redirect']=false; 
	$options_widgets['default']['seiteadmin']=true; 
	$options_widgets['default']['rememberme']=true; 
	$options_widgets['default']['rsslink']=true; 
	$options_widgets['default']['rsscommentlink']=true; 
	$options_widgets['default']['wordpresslink']=true; 
	$options_widgets['default']['lostpwlink']=false;
	$options_widgets['default']['profilelink']=false; 
	$options_widgets['default']['showwpmeta']=true; 
	$options_widgets['default']['displayidentity']=false; 
	$options_widgets['default']['useselectbox']=false; 
	$options_widgets['default']['notopics']=false;

	//Option to delete
	$delnumber=$_POST['widget-minimeta-SidebarDelete'];
	//write every options tab to optiones
	foreach ((array)$_POST['widget-minimeta'] as $number => $numbervalues) {
	  if ($delnumber!=$number and $number!='default'){ //Change only not deleted 
	    $options_widgets[$number]['optionname'] = wp_specialchars( $numbervalues['optionname']);
		$options_widgets[$number]['loginlink'] = isset($numbervalues['loginlink']);
		$options_widgets[$number]['loginform'] = isset($numbervalues['loginform']);
		$options_widgets[$number]['logout'] = isset($numbervalues['logout']); 
		$options_widgets[$number]['registerlink'] = isset($numbervalues['registerlink']);
		$options_widgets[$number]['testcookie'] = isset($numbervalues['testcookie']); 
		$options_widgets[$number]['redirect'] = isset($numbervalues['redirect']); 
		$options_widgets[$number]['seiteadmin'] = isset($numbervalues['seiteadmin']); 
		$options_widgets[$number]['rememberme'] = isset($numbervalues['rememberme']);
		$options_widgets[$number]['rsslink'] = isset($numbervalues['rsslink']); 
		$options_widgets[$number]['rsscommentlink'] = isset($numbervalues['rsscommentlink']); 
		$options_widgets[$number]['wordpresslink'] = isset($numbervalues['wordpresslink']);
		$options_widgets[$number]['lostpwlink'] = isset($numbervalues['lostpwlink']);
		$options_widgets[$number]['profilelink'] = isset($numbervalues['profilelink']); 
		$options_widgets[$number]['showwpmeta'] = isset($numbervalues['showwpmeta']); 
		$options_widgets[$number]['displayidentity'] = isset($numbervalues['displayidentity']); 
		$options_widgets[$number]['useselectbox'] = isset($numbervalues['useselectbox']);
		$options_widgets[$number]['notopics'] = isset($numbervalues['notopics']);
		unset($options_widgets[$number]['adminlinks']);
		for ($i=0;$i<sizeof($numbervalues['adminlinks']);$i++) {
			$options_widgets[$number]['adminlinks'][$i] = $numbervalues['adminlinks'][$i];
		}
		unset($options_widgets[$number]['linksin']);
		$options_widgets[$number]['linksin']="";
		for ($i=0;$i<sizeof($numbervalues['linksin']);$i++) {
			if (isset($numbervalues['linksin'][$i])) $options_widgets[$number]['linksin'] .= $numbervalues['linksin'][$i].",";
		}
		$options_widgets[$number]['linksin'] = substr($options_widgets[$number]['linksin'], 0, -1);
		unset($options_widgets[$number]['linksout']);
		$options_widgets[$number]['linksout']="";
		for ($i=0;$i<sizeof($numbervalues['linksout']);$i++) {
			if (isset($numbervalues['linksout'][$i])) $options_widgets[$number]['linksout'] .= $numbervalues['linksout'][$i].",";
		}
		$options_widgets[$number]['linksout'] = substr($options_widgets[$number]['linksout'], 0, -1);
	  }
	}
	
	//For new Sidebar Widget	
	if (!empty($_POST['widget-minimeta-SidebarNew'])) {
	    $newnumber=wp_create_nonce($_POST['widget-minimeta-SidebarNew']);
		$options_widgets[$newnumber]['optionname']=wp_specialchars($_POST['widget-minimeta-SidebarNew']);
		$options_widgets[$newnumber]['loginlink']=true;
		$options_widgets[$newnumber]['loginform']=false;
		$options_widgets[$newnumber]['logout']=true; 
		$options_widgets[$newnumber]['registerlink']=true;
		$options_widgets[$newnumber]['testcookie']=false; 
		$options_widgets[$newnumber]['redirect']=false; 
		$options_widgets[$newnumber]['seiteadmin']=true; 
		$options_widgets[$newnumber]['rememberme']=true; 
		$options_widgets[$newnumber]['rsslink']=true; 
		$options_widgets[$newnumber]['rsscommentlink']=true; 
		$options_widgets[$newnumber]['wordpresslink']=true; 
		$options_widgets[$newnumber]['lostpwlink']=false;
		$options_widgets[$newnumber]['profilelink']=false; 
		$options_widgets[$newnumber]['showwpmeta']=true; 
		$options_widgets[$newnumber]['displayidentity']=false; 
		$options_widgets[$newnumber]['useselectbox']=false; 
		$options_widgets[$newnumber]['notopics']=false;
	}
	
	$update_views_queries[] = update_option('minimeta_widget_options', $options_widgets);
	$update_views_text[] = __('MiniMeta Widget Options', 'MiniMetaWidget');

	$i=0;
	$text = '';
	foreach($update_views_queries as $update_views_query) {
		if($update_views_query) {
			$text .= '<font color="green">'.$update_views_text[$i].' '.__('Updated', 'MiniMetaWidget').'</font><br />';
		}
		$i++;
	}
	if(empty($text)) {
		$text = '<font color="red">'.__('No MiniMeta Widget Option Updated', 'MiniMetaWidget').'</font>';
	}
}

// Determines Which Mode It Is
if(trim($_POST['uninstall_MiniMeta_yes']) == 'yes' and current_user_can('edit_plugins')) {
	check_admin_referer('MiniMeta-delete');
	// Uninstall MiniMeta Widget
	echo '<div id="message" class="updated fade">';
	echo '<p>';
	MiniMetaFunctions::uninstall(true); //Show uninstll with echos
	echo '</p>';
	echo '</div>'; 
	//  Deactivating MiniMeta Widget
	$deactivate_url = 'plugins.php?action=deactivate&amp;plugin='.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php';
	if(function_exists('wp_nonce_url')) 
		$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_'.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php');
	echo '<div class="wrap">';
	echo '<h2>'.__('Uninstall MiniMeta Widget', 'MiniMetaWidget').'</h2>';
	echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Finish The Uninstallation And MiniMeta Widget Will Be Deactivated Automatically.', 'MiniMetaWidget'), $deactivate_url).'</strong></p>';
	echo '</div>';
} else {
// Main Page

$options_widgets = get_option('minimeta_widget_options');
	
if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>

<div class="wrap"> 
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
	<?php wp_nonce_field('MiniMeta-options'); ?>
	
	<h2><?php _e('MiniMeta Widget Options', 'MiniMetaWidget'); ?></h2>
	
	<div id="minimetaopttabs"> 
		<ul>
		<?PHP
			foreach ($options_widgets as $tabs => $values) {
				if ($tabs=='default') {
				   echo "<li><a href=\"#siedebar-".$tabs."\"><span><i>".$values['optionname']."</i></span></a></li>";
				} else {
					echo "<li><a href=\"#siedebar-".$tabs."\"><span>".$values['optionname']."</span></a></li>";
				}
			}
		?>
        </ul>
  
	<?php 
	foreach ($options_widgets as $number => $numbervalues) {
	?>
	<div id="siedebar-<?php echo $number; ?>">
		<?PHP $disabeld = $number == 'default' ? ' disabled=\"disabled\"' : ''; ?>
		<input type="hidden" name="widget-minimeta[<?php echo $number; ?>][optionname]" value="<?php echo $options_widgets[$number]['optionname']; ?>" />
		<table class="form-table">
        <tr valign="top"> 
		<th scope="row"><label for="show_when_logged_out"><?php _e('Show when logged out:','MiniMetaWidget');?></label></th><td><fieldset>
         <label for="minimeta-loginlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['loginlink'],true); echo $disabeld; ?> id="minimeta-loginlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][loginlink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Login');?></label><br />
         <label for="minimeta-loginform-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['loginform'],true); echo $disabeld; ?> id="minimeta-loginform-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][loginform]" />&nbsp;<?php _e('Login Form with','MiniMetaWidget');?>&nbsp;</label><label for="minimeta-rememberme-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['rememberme'],true); echo $disabeld; ?> id="minimeta-rememberme-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rememberme]" />&nbsp;<?php _e('Remember me and');?>&nbsp;</label><label for="minimeta-testcookie-<?php echo $number; ?>" title="<?php _e('Enable WordPress Cookie Test for login Form','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['testcookie'],true); echo $disabeld; ?> id="minimeta-testcookie-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][testcookie]" />&nbsp;<?php _e('Cookie Test','MiniMetaWidget');?></label><br />
		 <label for="minimeta-lostpwlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['lostpwlink'],true); echo $disabeld; ?> id="minimeta-lostpwlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][lostpwlink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Lost your password?');?></label><br />
		 <label for="minimeta-registerlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['registerlink'],true); echo $disabeld; ?> id="minimeta-registerlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][registerlink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Register');?></label><br />    
		 <td><fieldset><label for="minimeta-linksout-<?php echo $number; ?>" title="<?php _e('Display Links Selection','MiniMetaWidget');?>"><?php _e('Select Links to Display:','MiniMetaWidget');?> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-linksout-<?php echo $number; ?>'),true)" style="font-size:9px;"><?php _e('All'); ?></a> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-linksout-<?php echo $number; ?>'),false)" style="font-size:9px;"><?php _e('None'); ?></a><br />
         <select class="select" style="height:70px;" name="widget-minimeta[<?php echo $number; ?>][linksout][]" id="minimeta-linksout-<?php echo $number; ?>" multiple="multiple"<?php echo $disabeld; ?>>
         <?PHP
            $bookmarks=get_bookmarks(array('hide_invisible' => 0,'orderby' =>'name'));
            (array)$linkidsout=explode(",",$options_widgets[$number]['linksout']);
			foreach ($bookmarks as $links) {
               $checklinksout = in_array($links->link_id,$linkidsout) ? ' selected=\"selected\"' : '';
               echo "<option value=\"".$links->link_id."\"".$checklinksout.">". wp_specialchars($links->link_name)."</option>";
            }        
         ?>  
         </select></label></fieldset></td>
		</fieldset></td></tr> 
		<tr valign="top"> 
		<th scope="row"><label for="show_allways"><?php _e('Show allways:','MiniMetaWidget');?></label></th><td><fieldset>
         <label for="minimeta-rsslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['rsslink'],true); echo $disabeld; ?> id="minimeta-rsslink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rsslink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-rsscommentlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['rsscommentlink'],true); echo $disabeld; ?> id="minimeta-rsscommentlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rsscommentlink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-wordpresslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['wordpresslink'],true); echo $disabeld; ?> id="minimeta-wordpresslink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][wordpresslink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;WordPress.org</label><br />
		<td><fieldset>
		 <label for="minimeta-redirect-<?php echo $number; ?>" title="<?php _e('Enable WordPress redirect function on Login/out','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['redirect'],true); echo $disabeld; ?> id="minimeta-redirect-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][redirect]" />&nbsp;<?php _e('Enable Login/out Redirect','MiniMetaWidget');?></label><br />
		 <label for="minimeta-showwpmeta-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['showwpmeta'],true); echo $disabeld; ?> id="minimeta-showwpmeta-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][showwpmeta]" />&nbsp;<?php _e('wp_meta Plugin hooks','MiniMetaWidget');?></label><br />
		</fieldset></td>
		</fieldset></td></tr>
		<tr valign="top"> 
		<th scope="row"><label for="show_when_loggt_in"><?php _e('Show when logged in:','MiniMetaWidget');?></label></th><td><fieldset>
		 <label for="minimeta-logout-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['logout'],true); echo $disabeld; ?> id="minimeta-logout-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][logout]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Logout');?></label><br />
         <label for="minimeta-seiteadmin-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['seiteadmin'],true); echo $disabeld; ?> id="minimeta-seiteadmin-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][seiteadmin]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Site Admin');?></label><br />
		 &nbsp;<br />
		 <label for="minimeta-displayidentity-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['displayidentity'],true); echo $disabeld; ?> id="minimeta-displayidentity-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][displayidentity]" />&nbsp;<?php _e('Disply user Identity as title','MiniMetaWidget');?></label><br />
         <label for="minimeta-profilelink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['profilelink'],true); echo $disabeld; ?> id="minimeta-profilelink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][profilelink]" />&nbsp;<?php _e('Link to Your Profile in title','MiniMetaWidget');?></label><br />
		 <td><fieldset><label for="minimeta-linksin-<?php echo $number; ?>" title="<?php _e('Display Links Selection','MiniMetaWidget');?>"><?php _e('Select Links to Display:','MiniMetaWidget');?> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-linksin-<?php echo $number; ?>'),true)" style="font-size:9px;"><?php _e('All'); ?></a> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-linksin-<?php echo $number; ?>'),false)" style="font-size:9px;"><?php _e('None'); ?></a><br />
         <select class="select" style="height:70px;" name="widget-minimeta[<?php echo $number; ?>][linksin][]" id="minimeta-linksin-<?php echo $number; ?>" multiple="multiple"<?php echo $disabeld; ?>>
         <?PHP
			(array)$linkidsin=explode(",",$options_widgets[$number]['linksin']);
			foreach ($bookmarks as $links) {
               $checklinksin=in_array($links->link_id,$linkidsin) ? ' selected=\"selected\"' : '';
               echo "<option value=\"".$links->link_id."\"".$checklinksin.">". wp_specialchars($links->link_name)."</option>";
            }        
         ?>  
         </select></label></fieldset></td>
		</fieldset></td></tr>	
		<tr valign="top"> 
		<th scope="row"><label for="admin_links"><?php _e('Admin links:','MiniMetaWidget');?></label></th><td><fieldset>
 		 <label for="minimeta-useselectbox-<?php echo $number; ?>" title="<?php _e('Use Select Box for Admin Links','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['useselectbox'],true); echo $disabeld; ?> id="minimeta-useselectbox-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][useselectbox]" />&nbsp;<?php _e('Use Select Box','MiniMetaWidget');?></label><br />
         <label for="minimeta-notopics-<?php echo $number; ?>" title="<?php _e('Do not show Admin Links topics','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo checked($options_widgets[$number]['notopics'],true); echo $disabeld; ?> id="minimeta-notopics-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][notopics]" />&nbsp;<?php _e('No Topics','MiniMetaWidget');?></label><br />
         <td><fieldset><label for="minimeta-adminlinks-<?php echo $number; ?>" title="<?php _e('Admin Links Selection','MiniMetaWidget');?>"><?php _e('Select Admin Links:','MiniMetaWidget');?> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-adminlinks-<?php echo $number; ?>'),true)" style="font-size:9px;"><?php _e('All'); ?></a> <a href="javascript:selectAll_widget_minimeta(document.getElementById('minimeta-adminlinks-<?php echo $number; ?>'),false)" style="font-size:9px;"><?php _e('None'); ?></a><br />
         <select class="select" style="height:120px;" name="widget-minimeta[<?php echo $number; ?>][adminlinks][]" id="minimeta-adminlinks-<?php echo $number; ?>" multiple="multiple"<?php echo $disabeld; ?>>
         <?PHP
            $adminlinks=get_option('minimeta_adminlinks');
            foreach ($adminlinks as $menu) {
             echo "<optgroup label=\"".$menu['menu']."\">";
             foreach ($menu as $submenu) {
              if (is_array($submenu)) {
               $checkadminlinks=in_array($submenu[2],(array)$options_widgets[$number]['adminlinks']) ? ' selected=\"selected\"' : '';
               echo "<option value=\"".$submenu[2]."\"".$checkadminlinks.">".$submenu[0]."</option>";
              }
             }
             echo "</optgroup>";
            }        
         ?>  
         </select></label></fieldset></td>
		</fieldset></td></tr>	
        </table>	
	</div>
	<?php } ?>

	<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
	<?php _e('New:', 'MiniMetaWidget'); ?><input type="text" id="widget-minimeta-SidebarNew" name="widget-minimeta-SidebarNew" size="10" />
	<?php _e('Delete:', 'MiniMetaWidget'); ?><select id="widget-minimeta-SidebarDelete" name="widget-minimeta-SidebarDelete" size="1">
	<option value=""><?php _e('none', 'MiniMetaWidget'); ?></option>
	<?PHP 
	foreach ($options_widgets as $number => $values) {
		if ($number!="default") echo "<option value=\"".$number."\">".$values['optionname']."</option>";
	}
	?>
	</select>
	
</form> 	
</div>
	
<div class="wrap"> 
	
	<h2><?php _e('MiniMeta Widget', 'MiniMetaWidget'); ?></h2>
	<div id="minimetatabs"> 
		<ul>
			<li><a href="#usage"><span><?php _e('Usage', 'MiniMetaWidget'); ?></span></a></li>
			<li><a href="#about"><span><?php _e('About', 'MiniMetaWidget'); ?></span></a></li>
			<?php if(current_user_can('edit_plugins')) ?><li><a href="#uninstall"><span><?php _e('Uninstall', 'MiniMetaWidget'); ?></span></a></li>
        </ul>
		<div id="usage" style="width:600px;">
		 1. Create a otion set above.<br />
		 2. Place a widget from WordPress or K2 Seidbar Modules or in Theme via PHP and select a option set.<br />
		 3. redy.<br />
		 &nbsp;<br />
		 <strong>Code too place a Widget via PHP:</strong><br />
		 <code> &lt;?PHP if (function_exists('MiniMetaWidgetSidebar')) MiniMetaWidgetSidebar(before_title, title, after_title, before_widget, after_widget, otionsetname); ?&gt; </code><br />
		 &nbsp;<br />
		 <strong>before_title</strong> = HTML before Title <i>default: &lt;h4&gt;</i><br />
		 <strong>title</strong> = Title for Widget <i>default: Meta</i><br />
		 <strong>after_title</strong> = HTML after Title <i>default: &lt;/h4&gt;</i><br />
		 <strong>before_widget</strong> = HTML before Widget <i>default: &lt;div class="MiniMetaWidgetSiedbar"&gt;</i><br />
		 <strong>after_widget</strong> = HTML after Widget <i>default: &lt;/div&gt;</i><br />
		 <strong>otionsetname</strong> = Name of one settings from above <i>default: default</i><br />
		 
		</div>
		<div id="about" style="width:600px;">
		 <strong>Plugin Name:</strong> MiniMeta Wigdet<br />
		 <strong>Author:</strong> Daniel H&uuml;sken<br />
		 <strong>Author Webseite:</strong> <a href="http://danielhuesken.de" target="_new">http://danielhuesken.de</a><br />
		 <strong>Plugin Webseite:</strong> <a href="http://danielhuesken.de/portfolio/minimeta/" target="_new">http://danielhuesken.de/portfolio/minimeta/</a><br />
		 <strong>Plugin by WordPress:</strong> <a href="http://wordpress.org/extend/plugins/minimeta-widget/" target="_new">http://wordpress.org/extend/plugins/minimeta-widget/</a><br />
		 
		  <?php _e('If you find it useful, please consider donating.', 'MiniMetaWidget'); ?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=daniel%40huesken-net%2ede&item_name=MiniMeta%20Widget%20Plugin%20for%20WordPress&no_shipping=1&no_note=1&tax=0&currency_code=EUR&lc=LV&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_new"><img alt="Donate" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" /></a>
		</div>
		<?php if(current_user_can('edit_plugins')) {?>
		<div id="uninstall" style="width:600px;">
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
			<?php wp_nonce_field('MiniMeta-delete'); ?>
			<p style="text-align: left;">
				<?php _e('Deactivating MiniMeta Widget plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.', 'MiniMetaWidget'); ?>
			</p>
			<p style="text-align: left; color: red">
				<strong><?php _e('WARNING:', 'MiniMetaWidget'); ?></strong><br />
				<?php _e('Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.', 'MiniMetaWidget'); ?>
			</p>
			<p style="text-align: left; color: red">
				<strong><?php _e('The following WordPress Options will be DELETED:', 'MiniMetaWidget'); ?></strong><br />
			</p>
			<table class="widefat">
				<thead>
					<tr>
						<th><?php _e('WordPress Options', 'MiniMetaWidget'); ?></th>
					</tr>
				</thead>
				<tr>
					<td valign="top">
						<ol>
						<?php
							foreach($views_settings as $settings) {
								echo '<li>'.$settings.'</li>'."\n";
							}
						?>
						</ol>
					</td>
				</tr>
			</table>
			<p>&nbsp;</p>
			<p style="text-align: center;">
				<input type="checkbox" name="uninstall_MiniMeta_yes" value="yes" />&nbsp;<?php _e('Yes', 'MiniMetaWidget'); ?><br /><br />
				<input type="submit" name="do" value="<?php _e('UNINSTALL MiniMeta Widget', 'MiniMetaWidget'); ?>" class="button" onclick="return confirm('<?php _e('You Are About To Uninstall MiniMeta Widget From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.', 'MiniMetaWidget'); ?>')" />
			</p>		
			</form>	
		</div>
		<?php } ?>
	</div>
</div>


<?php
} // End if
?>