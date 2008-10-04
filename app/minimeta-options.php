<?php
//Variables Variables Variables
$id = intval($_GET['id']);
$mode = trim($_GET['mode']);
$views_settings = array('minimeta_widget_wp','minimeta_widget_options', 'minimeta_adminlinks','minimeta_widget_styles');

// Form Processing
// Update Options
if(!empty($_POST['Submit']) and current_user_can('switch_themes')) {
	check_admin_referer('MiniMeta-options','wpnoncemm');
	
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

	
    //Set default to def. options for Stylesheets
	unset($styleoptions['default']);
	$styleoptions['default']['stylename']='default';
	$styleoptions['default']['ul']='';
	$styleoptions['default']['li']='';
	$styleoptions['default']['siteadmin']='';
	$styleoptions['default']['logout']='color:red;';
	$styleoptions['default']['adminlinksli']='font-weight:normal;font-style:normal;';
	$styleoptions['default']['adminlinksselect']='font-size:10px;';
	$styleoptions['default']['adminlinksoption']='';
	$styleoptions['default']['adminlinkshref']='';
	$styleoptions['default']['adminlinksoptgroup']='';
	$styleoptions['default']['adminlinkslitopic']='font-weight:bold;font-style:italic;';
	$styleoptions['default']['adminlinksul']='';
	$styleoptions['default']['login']='';
	$styleoptions['default']['lostpw']='';
	$styleoptions['default']['register']='';
	$styleoptions['default']['rss']='';
	$styleoptions['default']['commentrss']='';
	$styleoptions['default']['wporg']='';

	//Option to delete
	$delstyle=$_POST['widget-minimeta-StyleDelete'];
	//write every style options tab to style optiones
	foreach ((array)$_POST['widget-minimetastyle'] as $number => $numbervalues) {
	  if ($delstyle!=$number and $number!='default'){ //Change only not deleted 
	    foreach ($numbervalues as $name => $namevalue) {
			$styleoptions[$number][$name] = wp_specialchars($namevalue);
		}
	  }
	}
	
	//For new Sidebar Widget	
	if (!empty($_POST['widget-minimeta-StyleNew'])) {
	    $newstyle=wp_create_nonce($_POST['widget-minimeta-StyleNew']);
		$styleoptions[$newstyle]['stylename']=wp_specialchars($_POST['widget-minimeta-StyleNew']);
		$styleoptions['default']['ul']='';
		$styleoptions['default']['li']='';
		$styleoptions[$newstyle]['siteadmin']='';
		$styleoptions[$newstyle]['logout']='color:red;';
		$styleoptions[$newstyle]['adminlinksli']='font-weight:normal;font-style:normal;';
		$styleoptions[$newstyle]['adminlinksselect']='font-size:10px;';
		$styleoptions[$newstyle]['adminlinksoption']='';
		$styleoptions[$newstyle]['adminlinkshref']='';
		$styleoptions[$newstyle]['adminlinksoptgroup']='';
		$styleoptions[$newstyle]['adminlinkslitopic']='font-weight:bold;font-style:italic;';
		$styleoptions[$newstyle]['adminlinksul']='';
		$styleoptions[$newstyle]['login']='';
		$styleoptions[$newstyle]['lostpw']='';
		$styleoptions[$newstyle]['register']='';
		$styleoptions[$newstyle]['rss']='';
		$styleoptions[$newstyle]['commentrss']='';
		$styleoptions[$newstyle]['wporg']='';
	}
	
	$update_views_queries[] = update_option('minimeta_widget_styles', $styleoptions);
	$update_views_text[] = __('MiniMeta Widget Stylesheets', 'MiniMetaWidget');
	
	
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
	check_admin_referer('MiniMeta-delete','wpnoncemmui');
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

$adminlinks=get_option('minimeta_adminlinks');
$options_widgets = get_option('minimeta_widget_options');
$styles = get_option('minimeta_widget_styles');
	
if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<?php wp_nonce_field('MiniMeta-options','wpnoncemm'); ?>

<div class="wrap"> 
	<h2><?php _e('MiniMeta Widget Options', 'MiniMetaWidget'); ?></h2>

	<?php _e('New:', 'MiniMetaWidget'); ?><input type="text" id="widget-minimeta-SidebarNew" name="widget-minimeta-SidebarNew" size="10" />
	<span id="SidebarDelete"><?php _e('Delete:', 'MiniMetaWidget'); ?><select id="widget-minimeta-SidebarDelete" name="widget-minimeta-SidebarDelete" size="1">
	<option value=""><?php _e('none', 'MiniMetaWidget'); ?></option>
	<?PHP 
	foreach ($options_widgets as $number => $values) {
		if ($number!="default") echo "<option value=\"".$number."\">".$values['optionname']."</option>";
	}
	?>
	</select></span>
	<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
	
	<?php
	foreach ($options_widgets as $number => $numbervalues) {
	?>
	<div class="postbox" id="widget-opt-<?php echo $number; ?>">
	<?PHP
	if ($number=='default') {
		echo "<h3>". __('Option:', 'MiniMetaWidget')." <i>".$numbervalues['optionname']."</i></h3>";
	} else {
		echo "<h3>". __('Option:', 'MiniMetaWidget')." ".$numbervalues['optionname']."";
		?> <input style="font-size:6px;" type="button" value="X" onclick="jQuery('#widget-opt-<?php echo $number;?>').remove();" /> </h3><?PHP
	}
	?>
	<div class="inside">
		<?PHP $disabeld = $number == 'default' ? ' disabled="disabled"' : ''; ?>
		<input type="hidden" name="widget-minimeta[<?php echo $number; ?>][optionname]" value="<?php echo $numbervalues['optionname']; ?>" />
		<table class="form-table">
        <tr valign="top"> 
		<th scope="row"><?php _e('Show when logged out:','MiniMetaWidget');?></th><td><fieldset>
         <label for="minimeta-loginlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['loginlink'],true); echo $disabeld; ?> id="minimeta-loginlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][loginlink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Login');?></label><br />
         <label for="minimeta-loginform-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['loginform'],true); echo $disabeld; ?> id="minimeta-loginform-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][loginform]" />&nbsp;<?php _e('Login Form with','MiniMetaWidget');?>&nbsp;</label>
			<label for="minimeta-rememberme-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['rememberme'],true); echo $disabeld; ?> id="minimeta-rememberme-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rememberme]" />&nbsp;<?php _e('Remember me and');?>&nbsp;</label>
			<label for="minimeta-testcookie-<?php echo $number; ?>" title="<?php _e('Enable WordPress Cookie Test for login Form','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['testcookie'],true); echo $disabeld; ?> id="minimeta-testcookie-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][testcookie]" />&nbsp;<?php _e('Cookie Test','MiniMetaWidget');?></label><br />
		 <label for="minimeta-lostpwlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['lostpwlink'],true); echo $disabeld; ?> id="minimeta-lostpwlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][lostpwlink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Lost your password?');?></label><br />
		 <label for="minimeta-registerlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['registerlink'],true); echo $disabeld; ?> id="minimeta-registerlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][registerlink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Register');?></label><br />    
		 </fieldset></td><td><fieldset>
		 <label for="minimeta-linksout-<?php echo $number; ?>" title="<?php _e('Display Links Selection','MiniMetaWidget');?>"><?php _e('Select Links to Display:','MiniMetaWidget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-linksout-<?php echo $number; ?> > option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-linksout-<?php echo $number; ?> > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> /><br />
         <select class="select" style="height:70px;" name="widget-minimeta[<?php echo $number; ?>][linksout][]" id="minimeta-linksout-<?php echo $number; ?>" multiple="multiple"<?php echo $disabeld; ?>>
         <?PHP
            $bookmarks=get_bookmarks(array('hide_invisible' => 0,'orderby' =>'name'));
            (array)$linkidsout=explode(",",$numbervalues['linksout']);
			foreach ($bookmarks as $links) {
               $checklinksout = in_array($links->link_id,$linkidsout) ? ' selected="selected"' : '';
               echo "<option value=\"".$links->link_id."\"".$checklinksout.">". wp_specialchars($links->link_name)."</option>";
            }        
         ?>  
         </select></label>
		</fieldset></td></tr> 
		<tr valign="top"> 
		<th scope="row"><?php _e('Show allways:','MiniMetaWidget');?></th><td><fieldset>
         <label for="minimeta-rsslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['rsslink'],true); echo $disabeld; ?> id="minimeta-rsslink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rsslink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-rsscommentlink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['rsscommentlink'],true); echo $disabeld; ?> id="minimeta-rsscommentlink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][rsscommentlink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?></label><br />
		 <label for="minimeta-wordpresslink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['wordpresslink'],true); echo $disabeld; ?> id="minimeta-wordpresslink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][wordpresslink]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;WordPress.org</label><br />
		</fieldset></td><td><fieldset>
		 <label for="minimeta-redirect-<?php echo $number; ?>" title="<?php _e('Enable WordPress redirect function on Login/out','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['redirect'],true); echo $disabeld; ?> id="minimeta-redirect-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][redirect]" />&nbsp;<?php _e('Enable Login/out Redirect','MiniMetaWidget');?></label><br />
		 <label for="minimeta-showwpmeta-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['showwpmeta'],true); echo $disabeld; ?> id="minimeta-showwpmeta-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][showwpmeta]" />&nbsp;<?php _e('wp_meta Plugin hooks','MiniMetaWidget');?></label><br />
		</fieldset></td></tr>
		<tr valign="top"> 
		<th scope="row"><?php _e('Show when logged in:','MiniMetaWidget');?></th><td><fieldset>
		 <label for="minimeta-logout-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['logout'],true); echo $disabeld; ?> id="minimeta-logout-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][logout]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Logout');?></label><br />
         <label for="minimeta-seiteadmin-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['seiteadmin'],true); echo $disabeld; ?> id="minimeta-seiteadmin-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][seiteadmin]" />&nbsp;<?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Site Admin');?></label><br />
		 &nbsp;<br />
		 <label for="minimeta-displayidentity-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['displayidentity'],true); echo $disabeld; ?> id="minimeta-displayidentity-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][displayidentity]" />&nbsp;<?php _e('Disply user Identity as title','MiniMetaWidget');?></label><br />
         <label for="minimeta-profilelink-<?php echo $number; ?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['profilelink'],true); echo $disabeld; ?> id="minimeta-profilelink-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][profilelink]" />&nbsp;<?php _e('Link to Your Profile in title','MiniMetaWidget');?></label><br />
		 </fieldset></td><td><fieldset>
		 <label for="minimeta-linksin-<?php echo $number; ?>" title="<?php _e('Display Links Selection','MiniMetaWidget');?>"><?php _e('Select Links to Display:','MiniMetaWidget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-linksin-<?php echo $number; ?> > option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-linksin-<?php echo $number; ?> > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> /><br />
         <select class="select" style="height:70px;" name="widget-minimeta[<?php echo $number; ?>][linksin][]" id="minimeta-linksin-<?php echo $number; ?>" multiple="multiple"<?php echo $disabeld; ?>>
         <?PHP
			(array)$linkidsin=explode(",",$numbervalues['linksin']);
			foreach ($bookmarks as $links) {
               $checklinksin=in_array($links->link_id,$linkidsin) ? ' selected="selected"' : '';
               echo "<option value=\"".$links->link_id."\"".$checklinksin.">". wp_specialchars($links->link_name)."</option>";
            }        
         ?>  
         </select></label>
		</fieldset></td></tr>	
		<tr valign="top"> 
		<th scope="row"><?php _e('Admin links:','MiniMetaWidget');?></th><td><fieldset>
 		 <label for="minimeta-useselectbox-<?php echo $number; ?>" title="<?php _e('Use Select Box for Admin Links','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['useselectbox'],true); echo $disabeld; ?> id="minimeta-useselectbox-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][useselectbox]" />&nbsp;<?php _e('Use Select Box','MiniMetaWidget');?></label><br />
         <label for="minimeta-notopics-<?php echo $number; ?>" title="<?php _e('Do not show Admin Links topics','MiniMetaWidget');?>"><input class="checkbox" type="checkbox" <?php echo checked($numbervalues['notopics'],true); echo $disabeld; ?> id="minimeta-notopics-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][notopics]" />&nbsp;<?php _e('No Topics','MiniMetaWidget');?></label><br />
         </fieldset></td><td><fieldset>
		 <label for="minimeta-adminlinks-<?php echo $number; ?>" title="<?php _e('Admin Links Selection','MiniMetaWidget');?>"><?php _e('Select Admin Links:','MiniMetaWidget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $number; ?> > optgroup >option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $number; ?> > optgroup > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> /><br />
         <select class="select" style="height:120px;" name="widget-minimeta[<?php echo $number; ?>][adminlinks][]" id="minimeta-adminlinks-<?php echo $number; ?>" multiple="multiple"<?php echo $disabeld; ?>>
         <?PHP
            foreach ($adminlinks as $menu) {
             echo "<optgroup label=\"".$menu['menu']."\">";
             foreach ($menu as $submenu) {
              if (is_array($submenu)) {
               $checkadminlinks=in_array($submenu[2],(array)$numbervalues['adminlinks']) ? ' selected="selected"' : '';
               echo "<option value=\"".$submenu[2]."\"".$checkadminlinks.">".$submenu[0]."</option>";
              }
             }
             echo "</optgroup>";
            }        
         ?>  
         </select></label>
		</fieldset></td></tr>	
        </table></div></div>
	<?php } ?>
	<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
</div>

<div class="wrap"> 
	<h2><?php _e('MiniMeta Widget Stylesheets', 'MiniMetaWidget'); ?></h2>
	
	<?php _e('New:', 'MiniMetaWidget'); ?><input type="text" id="widget-minimeta-StyleNew" name="widget-minimeta-StyleNew" size="10" />
	<span id="StyleDelete"><?php _e('Delete:', 'MiniMetaWidget'); ?><select id="widget-minimeta-StyleDelete" name="widget-minimeta-StyleDelete" size="1">
	<option value=""><?php _e('none', 'MiniMetaWidget'); ?></option>
	<?PHP 
	foreach ($styles as $number => $values) {
		if ($number!="default") echo "<option value=\"".$number."\">".$values['stylename']."</option>";
	}
	?>
	</select></span>	
	<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
	
	<?php 
	foreach ($styles as $number => $numbervalues) {
	?>
	<div class="postbox" id="widget-style-<?php echo $number; ?>">
		<?PHP
		if ($number=='default') {
			echo "<h3>". __('Stylesheet:', 'MiniMetaWidget')." <i>".$numbervalues['stylename']."</i></h3>";
		} else {
			echo "<h3>". __('Stylesheet:', 'MiniMetaWidget')." ".$numbervalues['stylename']."";
			?> <input style="font-size:6px;" type="button" value="X" onclick="jQuery('#widget-style-<?php echo $number;?>').remove();" /> </h3><?PHP
		}
		?>
		<div class="inside">
		<?PHP $disabeld = $number == 'default' ? ' disabled="disabled"' : ''; ?>
		<input type="hidden" name="widget-minimetastyle[<?php echo $number; ?>][stylename]" value="<?php echo $numbervalues['stylename']; ?>" />
		
		<table class="form-table">
        <tr valign="top"> 
		<th scope="row"><?php _e('Stylesheets:','MiniMetaWidget');?></th><td>
         <?php _e('General:','MiniMetaWidget');?>&nbsp;&lt;ul&gt;&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['ul']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-ul-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][ul]" /><br />
		 <?php _e('General:','MiniMetaWidget');?>&nbsp;&lt;li&gt;&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['li']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-li-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][li]" /><br />
		 
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Site Admin');?>&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['siteadmin']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-siteadmin-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][siteadmin]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Log out');?>&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['logout']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-logout-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][logout]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Login','MiniMetaWidget');?>&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['login']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-login-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][login]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Lost your password?');?>&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['lostpw']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-lostpw-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][lostpw]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Register');?>&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['register']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-register-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][register]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?>&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['rss']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-rss-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][rss]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?>&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['commentrss']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-commentrss-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][commentrss]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;WordPress.org&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['wporg']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-wporg-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][wporg]" /><br />
		 
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;<?php _e('topic','MiniMetaWidget');?>&nbsp;&lt;li&gt;&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['adminlinkslitopic']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinkslitopic-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinkslitopic]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;ul&gt;&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['adminlinksul']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksul-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksul]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;li&gt;&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['adminlinksli']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksli-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksli]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;a href&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['adminlinkshref']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinkshref-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinkshref]" /><br />
		 
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;select&gt;&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['adminlinksselect']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksselect-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksselect]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;optiongroup&gt;&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['adminlinksoptgroup']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksoptgroup-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksoptgroup]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;option&gt;&nbsp;<input class="text" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['adminlinksoption']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksoption-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksoption]" /><br />
		</td></tr> 
        </table></div></div>	
	<?php } ?>
	<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />

</div>
</form> 
	
	
	
	
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
		 <code> &lt;?PHP if (function_exists('MiniMetaWidgetSidebar')) MiniMetaWidgetSidebar(before_title, title, after_title, before_widget, after_widget, otionsetname, stylename); ?&gt; </code><br />
		 &nbsp;<br />
		 <strong>before_title</strong> = HTML before Title <i>default: &lt;h4&gt;</i><br />
		 <strong>title</strong> = Title for Widget <i>default: Meta</i><br />
		 <strong>after_title</strong> = HTML after Title <i>default: &lt;/h4&gt;</i><br />
		 <strong>before_widget</strong> = HTML before Widget <i>default: &lt;div class="MiniMetaWidgetSiedbar"&gt;</i><br />
		 <strong>after_widget</strong> = HTML after Widget <i>default: &lt;/div&gt;</i><br />
		 <strong>otionsetname</strong> = Name of one settings from above <i>default: default</i><br />
		 <strong>stylename</strong> = Name of one settings from above <i>default: </i><br />
		 
		</div>
		<div id="about" style="width:600px;">
		 <strong>Plugin Name:</strong> MiniMeta Wigdet<br />
		 <strong>Author:</strong> Daniel H&uuml;sken<br />
		 <strong>Author Webseite:</strong> <a href="http://danielhuesken.de" target="_blank">http://danielhuesken.de</a><br />
		 <strong>Plugin Webseite:</strong> <a href="http://danielhuesken.de/portfolio/minimeta/" target="_blank">http://danielhuesken.de/portfolio/minimeta/</a><br />
		 <strong>Plugin by WordPress:</strong> <a href="http://wordpress.org/extend/plugins/minimeta-widget/" target="_blank">http://wordpress.org/extend/plugins/minimeta-widget/</a><br />
		 
		  <?php _e('If you find it useful, please consider donating.', 'MiniMetaWidget'); ?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=daniel%40huesken-net%2ede&amp;item_name=MiniMeta%20Widget%20Plugin%20for%20WordPress&amp;no_shipping=1&amp;no_note=1&amp;tax=0&amp;currency_code=EUR&amp;lc=LV&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8" target="_blank"><img alt="Donate" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" /></a>
		</div>
		<?php if(current_user_can('edit_plugins')) {?>
		<div id="uninstall" style="width:600px;">
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
			<?php wp_nonce_field('MiniMeta-delete','wpnoncemmui'); ?>
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