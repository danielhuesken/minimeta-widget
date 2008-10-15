<?php
//Variables Variables Variables
$id = intval($_GET['id']);
$mode = trim($_GET['mode']);
$views_settings = array('minimeta_widget_wp','minimeta_widget_options', 'minimeta_adminlinks','minimeta_widget_styles','minimeta_widget_sidebar');

// Form Processing
// Update Options
if(!empty($_POST['Submit']) and current_user_can('switch_themes')) {
	check_admin_referer('MiniMeta-options','wpnoncemm');
	
	$update_views_queries = array();
	$update_views_text = array();
	

	//Option to delete
	$delnumber=$_POST['widget-options-SidebarDelete'];
	//write every options tab to optiones
	foreach ((array)$_POST['widget-options'] as $optionname => $optionvalues) {
	  if ($delnumber!=$optionname){ //Change only not deleted 
	    $options_widgets[$optionname]['optionname'] = htmlentities(stripslashes($optionvalues['optionname']));
		$options_widgets[$optionname]['in']['title']['args']=$optionvalues['in']['title']['args'];
		foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) {
			if ($partvalues[3]) {
				$options_widgets[$optionname]['in'][$partname]['active']=isset($optionvalues['in'][$partname]['active']);
				$options_widgets[$optionname]['in'][$partname]['args']=$optionvalues['in'][$partname]['args'];
			}
		}
		foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) { 
			if ($partvalues[4]) {
				$options_widgets[$optionname]['out'][$partname]['active']=isset($optionvalues['out'][$partname]['active']);
				$options_widgets[$optionname]['out'][$partname]['args']=$optionvalues['out'][$partname]['args'];
			}
		}
	  }
	}
	
	//For new Sidebar Widget	
	if (!empty($_POST['widget-options-SidebarNew'])) {
	    $newnumber=wp_create_nonce($_POST['widget-options-SidebarNew']);
		$options_widgets[$newnumber]['optionname']=htmlentities(stripslashes($_POST['widget-options-SidebarNew']));
		$options_widgets[$newnumber]['in']['linkloginlogout']['active']=true;
		$options_widgets[$newnumber]['in']['linkseiteadmin']['active']=true;
		$options_widgets[$newnumber]['in']['linkrss']['active']=true;
		$options_widgets[$newnumber]['in']['linkcommentrss']['active']=true;
		$options_widgets[$newnumber]['in']['linkwordpress']['active']=true;
		$options_widgets[$newnumber]['in']['actionwpmeta']['active']=true;
		$options_widgets[$newnumber]['out']['linkloginlogout']['active']=true;
		$options_widgets[$newnumber]['out']['linkregister']['active']=true;
		$options_widgets[$newnumber]['out']['linkrss']['active']=true;
		$options_widgets[$newnumber]['out']['linkcommentrss']['active']=true;
		$options_widgets[$newnumber]['out']['linkwordpress']['active']=true;
		$options_widgets[$newnumber]['out']['actionwpmeta']['active']=true;
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
	$delstyle=$_POST['widget-style-StyleDelete'];
	//write every style options tab to style optiones
	foreach ((array)$_POST['widget-minimetastyle'] as $number => $numbervalues) {
	  if ($delstyle!=$number and $number!='default'){ //Change only not deleted 
	    foreach ($numbervalues as $name => $namevalue) {
			$styleoptions[$number][$name] = $namevalue;
		}
	  }
	}
	
	//For new Sidebar Widget	
	if (!empty($_POST['widget-style-StyleNew'])) {
	    $newstyle=wp_create_nonce($_POST['widget-style-StyleNew']);
		$styleoptions[$newstyle]['stylename']=htmlentities(stripslashes($_POST['widget-style-StyleNew']));
		$styleoptions[$newstyle]['ul']='';
		$styleoptions[$newstyle]['li']='';
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
	


	//write every Seidebar options Seidbar optiones
	for ($i=1;$i<=$_POST['widget-minimeta-SidebarMany'];$i++) {
	    foreach ((array)$_POST['widget-minimeta'][$i] as $name => $namevalue) {
			$sidebaroptions[$i][$name] = $namevalue;
		}
	}
	$sidebaroptions['SidebarMany']=$_POST['widget-minimeta-SidebarMany'];
	
	$update_views_queries[] = update_option('minimeta_widget_sidebar', $sidebaroptions);
	$update_views_text[] = __('MiniMeta Widget Seidebar', 'MiniMetaWidget');

	
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
$sidebar_widgets = get_option('minimeta_widget_sidebar');
	
if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<?php wp_nonce_field('MiniMeta-options','wpnoncemm'); ?>

<div class="wrap"> 
	<h2><?php _e('MiniMeta Widget Options', 'MiniMetaWidget'); ?></h2>

	<?php _e('New:', 'MiniMetaWidget'); ?><input type="text" id="widget-options-SidebarNew" name="widget-options-SidebarNew" size="10" />
	<span id="WidgetOptDelete"><?php _e('Delete:', 'MiniMetaWidget'); ?><select id="widget-options-SidebarDelete" name="widget-options-SidebarDelete" size="1">
	<option value=""><?php _e('none', 'MiniMetaWidget'); ?></option>
	<?PHP 
	foreach ($options_widgets as $number => $values) {
		if ($number!="default") echo "<option value=\"".$number."\">".$values['optionname']."</option>";
	}
	?>
	</select></span>
	<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
	
	<?php
	if (is_array($options_widgets)) {
	foreach ( $options_widgets as $optionname => $optionvalues) {
	?>
	<div class="minimetabox" id="widget-opt-<?php echo $optionname; ?>">
	<?PHP
		echo "<h3>". __('Option:', 'MiniMetaWidget')." ".$optionvalues['optionname']."</h3>";
	?>
	<div class="inside">
		<input type="hidden" name="widget-options[<?php echo $optionname; ?>][optionname]" value="<?php echo $optionvalues['optionname']; ?>" />
		
		<?php $loginout='in'; ?>
		<div class="widget-login">
			<h4><?php echo _e('Show when Loggt in'); ?></h4>
			<ul class="widget-login-list">
				<li class="widget-login-item">
					<h4 class="widget-login-title"><span><?php echo _e('Title'); ?></span></span><br class="clear" /></h4>
					<div class="widget-login-control">
						<input class="checkbox" type="checkbox" <?php echo checked($optionvalues[$loginout]['title']['args']['displayidentity'],true); ?> id="minimeta-displayidentity-<?php echo $optionname; ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][title][args][displayidentity]" />&nbsp;<?php _e('Disply user Identity as title','MiniMetaWidget');?><br />
						<input class="checkbox" type="checkbox" <?php echo checked($optionvalues[$loginout]['title']['args']['profilelink'],true); ?> id="minimeta-profilelink-<?php echo $optionname; ?>" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][title][args][profilelink]" />&nbsp;<?php _e('Link to Your Profile in title','MiniMetaWidget');?><br />
					</div>
				</li>
	<?PHP  	foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) { 
				if ($partvalues[3]) {?>
				<li class="widget-login-item">
					<h4 class="widget-login-title"><span><input class="checkbox" type="checkbox" <?php echo checked($optionvalues[$loginout][$partname]['active'],true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $partname; ?>][active]" /> <?php echo $partvalues[0]; ?></span> <br class="clear" /></h4>
					<?PHP if ($partvalues[2]) { ?>
					<div class="widget-login-control">
						<?php				
						$options=$optionvalues[$loginout][$partname]['args'];
						call_user_func($partvalues[2], $options );
						?>
					</div>
					<?PHP } ?>
				</li>
		<?PHP  	}
			} ?>
			</ul>
		</div>	
		
		<?php $loginout='out'; ?>
		<div class="widget-logout">
			<h4><?php echo _e('Show when Loggt out'); ?></h4>
			<ul class="widget-logout-list">
				<li class="widget-logout-item">
					<h4 class="widget-logout-title"><span><?php echo _e('Title'); ?></span><br class="clear" /></h4>
				</li>
	<?PHP  	foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) { 
				if ($partvalues[4]) { ?>
				<li class="widget-logout-item">
					<h4 class="widget-logout-title"><span><input class="checkbox" type="checkbox" <?php echo checked($optionvalues[$loginout][$partname]['active'],true); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $partname; ?>][active]" /> <?php echo $partvalues[0]; ?></span><br class="clear" /></h4>
					<?PHP if ($partvalues[2]) {?>
					<div class="widget-logout-control">
						<?php				
						$options=$optionvalues[$loginout][$partname]['args'];
						call_user_func($partvalues[2], $options );
						?>
					</div>
					<?PHP } ?>
				</li>
		<?PHP  	}
			} ?>
			</ul>
		</div><br class="clear" />
		

		<p style="width:50%; float:left;"><input type="button" class="button" value="<?php _e('Remove'); ?>" onclick="jQuery('#widget-opt-<?php echo $optionname;?>').remove();" /></p>
		<p style="width:50%; float:right; text-align:right;"><input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" /></p>
		<br class="clear" />
		</div></div>
	<?php } 
		}?>
</div>

<div class="wrap"> 
	<h2><?php _e('MiniMeta Widget Stylesheets', 'MiniMetaWidget'); ?></h2>
	
	<?php _e('New:', 'MiniMetaWidget'); ?><input type="text" id="widget-style-StyleNew" name="widget-style-StyleNew" size="10" />
	<span id="WidgetStyleDelete"><?php _e('Delete:', 'MiniMetaWidget'); ?><select id="widget-style-StyleDelete" name="widget-style-StyleDelete" size="1">
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
	<div class="minimetabox" id="widget-style-<?php echo $number; ?>">
		<?PHP
		if ($number=='default') {
			echo "<h3>". __('Stylesheet:', 'MiniMetaWidget')." <i>".$numbervalues['stylename']."</i></h3>";
		} else {
			echo "<h3>". __('Stylesheet:', 'MiniMetaWidget')." ".$numbervalues['stylename']."</h3>";
		}
		?>
		<div class="inside">
		<?PHP $disabeld = $number == 'default' ? ' disabled="disabled"' : ''; ?>
		<input type="hidden" name="widget-minimetastyle[<?php echo $number; ?>][stylename]" value="<?php echo $numbervalues['stylename']; ?>" />
		
		<table class="form-table">
        <tr valign="top"> 
		<th scope="row"><?php _e('Stylesheets:','MiniMetaWidget');?></th><td>
         <?php _e('General:','MiniMetaWidget');?>&nbsp;&lt;ul&gt;&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['ul'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-ul-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][ul]" /><br />
		 <?php _e('General:','MiniMetaWidget');?>&nbsp;&lt;li&gt;&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['li'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-li-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][li]" /><br />
		 
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Site Admin');?>&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['siteadmin'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-siteadmin-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][siteadmin]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Log out');?>&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['logout'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-logout-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][logout]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Login','MiniMetaWidget');?>&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['login'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-login-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][login]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Lost your password?');?>&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['lostpw'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-lostpw-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][lostpw]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Register');?>&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['register'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-register-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][register]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>');?>&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['rss'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-rss-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][rss]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;<?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>');?>&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['commentrss'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-commentrss-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][commentrss]" /><br />
		 <?php _e('Link:','MiniMetaWidget');?>&nbsp;WordPress.org&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['wporg'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-wporg-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][wporg]" /><br />
		 
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;<?php _e('topic','MiniMetaWidget');?>&nbsp;&lt;li&gt;&nbsp;<input class="widefat" type="text" size="50" value="<?php echo wp_specialchars($numbervalues['adminlinkslitopic']); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinkslitopic-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinkslitopic]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;ul&gt;&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['adminlinksul'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksul-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksul]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;li&gt;&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['adminlinksli'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksli-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksli]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;a href&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['adminlinkshref'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinkshref-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinkshref]" /><br />
		 
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;select&gt;&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['adminlinksselect'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksselect-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksselect]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;optiongroup&gt;&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['adminlinksoptgroup'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksoptgroup-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksoptgroup]" /><br />
		 <?php _e('Admin Links:','MiniMetaWidget');?>&nbsp;&lt;option&gt;&nbsp;<input class="widefat" type="text" size="50" value="<?php echo htmlentities(stripslashes($numbervalues['adminlinksoption'])); ?>"<?php  echo $disabeld; ?> id="minimetastyle-adminlinksoption-<?php echo $number; ?>" name="widget-minimetastyle[<?php echo $number; ?>][adminlinksoption]" /><br />
		</td></tr> 
        </table>
		<?php if ($number != 'default') {?>
		<p style="width:50%; float:left;"><input type="button" class="button" value="<?php _e('Remove'); ?>" onclick="jQuery('#widget-style-<?php echo $number;?>').remove();" /></p>
		<p style="width:50%; float:right; text-align:right;"><input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" /></p>
		<?php } ?><br class="clear" />
		</div></div>	
	<?php } ?>
</div>

<div class="wrap"> 
	<h2><?php _e('MiniMeta Seidbar Widgets', 'MiniMetaWidget'); ?></h2>
	
	<?php _e('How many:', 'MiniMetaWidget'); ?><select class="select" id="widget-minimeta-SidebarMany" name="widget-minimeta-SidebarMany" size="1">	
	<?php
	for ($i=0;$i<=9;$i++) {
		$selected= $sidebar_widgets['SidebarMany']==$i ? ' selected="selected"' : '';
		echo "<option value=\"".$i."\"".$selected.">".$i."</option>";
	}
	?></select>
	<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
	
	<?php 
	for ($i=1;$i<=$sidebar_widgets['SidebarMany'];$i++) {
	?>
	<div class="minimetabox" id="widget-seidebar-<?php echo $i; ?>">
		<?php
		$sidebar_widgets[$i]['title'] = isset($sidebar_widgets[$i]['title']) ? $sidebar_widgets[$i]['title'] : __('Meta');
		$sidebar_widgets[$i]['before_title'] = isset($sidebar_widgets[$i]['before_title']) ? $sidebar_widgets[$i]['before_title'] : '<h2>';
		$sidebar_widgets[$i]['after_title'] = isset($sidebar_widgets[$i]['after_title']) ? $sidebar_widgets[$i]['after_title'] : '</h2>';
		$sidebar_widgets[$i]['before_widget'] = isset($sidebar_widgets[$i]['before_widget']) ? $sidebar_widgets[$i]['before_widget'] : '<div class="MiniMetaWidgetSiedbar">';
		$sidebar_widgets[$i]['after_widget'] = isset($sidebar_widgets[$i]['after_widget']) ? $sidebar_widgets[$i]['after_widget'] : '</div>';
		?>
		<?PHP echo "<h3>". __('Seidbar Widget:', 'MiniMetaWidget')." ".$i."</h3>"; ?>
		<div class="inside">
		<table class="form-table">
        <tr valign="top"> 
		<th scope="row"><?php _e('Widget Settings:','MiniMetaWidget');?></th><td>
			<?php _e('Title:'); ?><input class="widefat" id="minimeta-title-<?php echo $i; ?>" name="widget-minimeta[<?php echo $i; ?>][title]" type="text" value="<?php echo htmlentities(stripslashes($sidebar_widgets[$i]['title'])); ?>" /><br />
		 	<?php _e('Before Title:'); ?><input class="widefat" id="minimeta-before_title-<?php echo $i; ?>" name="widget-minimeta[<?php echo $i; ?>][before_title]" type="text" value="<?php echo htmlentities(stripslashes($sidebar_widgets[$i]['before_title'])); ?>" /><br />
			<?php _e('After Title:'); ?><input class="widefat" id="minimeta-after_title-<?php echo $i; ?>" name="widget-minimeta[<?php echo $i; ?>][after_title]" type="text" value="<?php echo htmlentities(stripslashes($sidebar_widgets[$i]['after_title'])); ?>" /><br />
			<?php _e('Before Widget:'); ?><input class="widefat" id="minimeta-before_widget-<?php echo $i; ?>" name="widget-minimeta[<?php echo $i; ?>][before_widget]" type="text" value="<?php echo htmlentities(stripslashes($sidebar_widgets[$i]['before_widget'])); ?>" /><br />
			<?php _e('After Widget:'); ?><input class="widefat" id="minimeta-after_widget-<?php echo $i; ?>" name="widget-minimeta[<?php echo $i; ?>][after_widget]" type="text" value="<?php echo htmlentities(stripslashes($sidebar_widgets[$i]['after_widget'])); ?>" /><br />
			<?php MiniMetaWidgetDisplay::control($i,$sidebar_widgets[$i]['optionset'],$sidebar_widgets[$i]['style']); ?>
		</td></tr> 
        </table>
		<p style="text-align:right;"><input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" /></p>
		</div></div>	
	<?php } ?>
</div>


</form> 
	
<div class="wrap"> 
	<h2><?php _e('MiniMeta Widget', 'MiniMetaWidget'); ?></h2>
	<div class="minimetabox">
		<h3><?php _e('Usage', 'MiniMetaWidget'); ?></h3>
		<div class="inside">
			<table style="width:100%;"><tr><td style="width:50%;padding-right:10px;">
				1. Create a otion set above.<br />
				2. Place a widget from WordPress Wigets, K2 Seidbar Modules or in Theme via PHP and select a option set.<br />
				3. redy.<br />
				&nbsp;<br />
				<strong>Code too place a Widget via PHP:</strong><br />
				<code> &lt;?PHP if (function_exists('MiniMetaWidgetSidebar')) MiniMetaWidgetSidebar(SeidbarWidgetNumber); ?&gt; </code><br />
				&nbsp;<br />
			</td><td style="border-left-width:1px;border-left-style:solid;border-left-color:#ccc;text-align:left;width:50%;padding-left:15px;">
				<strong>SeidbarWidgetNumber</strong> = Number of a Widget above <br />
			</td></tr></table>
		</div>
	</div>
	<div class="minimetabox">
		<h3><?php _e('About', 'MiniMetaWidget'); ?></h3>
		<div class="inside">
			<table style="width:100%;"><tr><td style="width:50%;">
				<?PHP $plugin_data=get_plugin_data(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php'); ?>
				<strong><?php _e('Plugin Name:', 'MiniMetaWidget'); ?></strong><br />&nbsp;&nbsp;&nbsp;<?PHP echo $plugin_data['Name'] ?><br />
				<strong><?php _e('Plugin Version:', 'MiniMetaWidget'); ?></strong><br />&nbsp;&nbsp;&nbsp;<?PHP echo $plugin_data['Version'] ?><br />
				<strong><?php _e('Author:', 'MiniMetaWidget'); ?></strong><br />&nbsp;&nbsp;&nbsp;<?PHP echo $plugin_data['Author'] ?><br />
				<strong><?php _e('Author Webseite:', 'MiniMetaWidget'); ?></strong><br />&nbsp;&nbsp;&nbsp;<a href="<?PHP echo $plugin_data['AuthorURI'] ?>" target="_blank"><?PHP echo $plugin_data['AuthorURI'] ?></a><br />
				<strong><?php _e('Plugin Webseite:', 'MiniMetaWidget'); ?></strong><br />&nbsp;&nbsp;&nbsp;<a href="<?PHP echo $plugin_data['PluginURI'] ?>" target="_blank"><?PHP echo $plugin_data['PluginURI'] ?></a><br />
				<strong><?php _e('Plugin on WordPress:', 'MiniMetaWidget'); ?></strong><br />&nbsp;&nbsp;&nbsp;<a href="http://wordpress.org/extend/plugins/minimeta-widget/" target="_blank">http://wordpress.org/extend/plugins/minimeta-widget/</a><br />
				<strong><?php _e('Description:', 'MiniMetaWidget'); ?></strong><br /><?PHP echo $plugin_data['Description'] ?><br />
			</td><td style="border-left-width:1px;border-left-style:solid;border-left-color:#ccc;text-align:center;width:50%;">
				<?php _e('If you find it useful, please consider donating.', 'MiniMetaWidget'); ?><br />&nbsp;<br />
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=daniel%40huesken-net%2ede&amp;item_name=MiniMeta%20Widget%20Plugin%20for%20WordPress&amp;no_shipping=1&amp;no_note=1&amp;tax=0&amp;currency_code=EUR&amp;lc=LV&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8" target="_blank"><img alt="Donate" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" /></a>
			</td></tr></table>
		</div>
	</div>
	<?php if(current_user_can('edit_plugins')) {?>
	<div class="minimetabox">
		<h3><?php _e('Uninstall', 'MiniMetaWidget'); ?></h3>
		<div class="inside">
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
	</div>
	<?php } ?>
</div>


<?php
} // End if
?>