<?php
//Variables Variables Variables
$id = intval($_GET['id']);
$mode = trim($_GET['mode']);
$views_settings = array('minimeta_widget_wp','minimeta_widget_options', 'minimeta_adminlinks');



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
		//Save general options
		$options_widgets[$optionname]['general']['style']['ul'] = $optionvalues['general']['style']['ul'];
		$options_widgets[$optionname]['general']['style']['li'] = $optionvalues['general']['style']['li'];
		$options_widgets[$optionname]['general']['php']['title'] = $optionvalues['general']['php']['title'];
		$options_widgets[$optionname]['general']['php']['before_title'] = $optionvalues['general']['php']['before_title'];
		$options_widgets[$optionname]['general']['php']['after_title'] = $optionvalues['general']['php']['after_title'];
		$options_widgets[$optionname]['general']['php']['before_widget'] = $optionvalues['general']['php']['before_widget'];
		$options_widgets[$optionname]['general']['php']['after_widget'] = $optionvalues['general']['php']['after_widget'];
		foreach ((array)$optionvalues['general']['pagesnot']['in'] as $page => $pagevalue) {
			$options_widgets[$optionname]['general']['pagesnot']['in'][$page] = isset($pagevalue);
		}
		foreach ((array)$optionvalues['general']['pagesnot']['out'] as $page => $pagevalue) {
			$options_widgets[$optionname]['general']['pagesnot']['out'][$page] = isset($pagevalue);
		}
		//Save option for in and out
		$ordering=0;
		for ($i=0; $i<=sizeof($optionvalues['in']);$i++) {
			if(isset($optionvalues['in'][$i]['active'])) {
				$options_widgets[$optionname]['in'][$ordering]['part']=$optionvalues['in'][$i]['part'];
				$options_widgets[$optionname]['in'][$ordering]['args']=$optionvalues['in'][$i]['args'];
				$ordering++;
			}
		}
		$ordering=0;
		for ($i=0; $i<=sizeof($optionvalues['out']);$i++) {
			if(isset($optionvalues['out'][$i]['active'])) {
				$options_widgets[$optionname]['out'][$ordering]['part']=$optionvalues['out'][$i]['part'];
				$options_widgets[$optionname]['out'][$ordering]['args']=$optionvalues['out'][$i]['args'];
				$ordering++;
			}
		}
	  }
	}
	
	//For new Sidebar Widget	
	if (!empty($_POST['widget-options-SidebarNew'])) {
	    $newnumber=wp_create_nonce($_POST['widget-options-SidebarNew']);
		$options_widgets[$newnumber]['optionname']=htmlentities(stripslashes($_POST['widget-options-SidebarNew']));
		$options_widgets[$newnumber]['in'][0]['part']='title';
		$options_widgets[$newnumber]['in'][1]['part']='linkseiteadmin';
		$options_widgets[$newnumber]['in'][2]['part']='linkloginlogout';
		$options_widgets[$newnumber]['in'][3]['part']='linkrss';
		$options_widgets[$newnumber]['in'][4]['part']='linkcommentrss';
		$options_widgets[$newnumber]['in'][5]['part']='linkwordpress';
		$options_widgets[$newnumber]['in'][6]['part']='actionwpmeta';
		$options_widgets[$newnumber]['out'][0]['part']='title';
		$options_widgets[$newnumber]['out'][1]['part']='linkregister';
		$options_widgets[$newnumber]['out'][2]['part']='linkloginlogout';
		$options_widgets[$newnumber]['out'][3]['part']='linkrss';
		$options_widgets[$newnumber]['out'][4]['part']='linkcommentrss';
		$options_widgets[$newnumber]['out'][5]['part']='linkwordpress';
		$options_widgets[$newnumber]['out'][6]['part']='actionwpmeta';
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
	
if(!empty($text)) { echo '<div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<?php wp_nonce_field('MiniMeta-options','wpnoncemm'); ?>

<div class="wrap metabox-holder"> 
	<h2><?php _e('MiniMeta Widget Options', 'MiniMetaWidget'); ?></h2>
	
	<div class="tablenav">
		<div class="alignleft actions">
			<?php _e('New Widget Option:', 'MiniMetaWidget'); ?><input type="text" id="widget-options-SidebarNew" name="widget-options-SidebarNew" size="10" />
			<span id="WidgetOptDelete"><?php _e('Delete Widget Option:', 'MiniMetaWidget'); ?><select id="widget-options-SidebarDelete" name="widget-options-SidebarDelete">
			<option value=""><?php _e('none', 'MiniMetaWidget'); ?></option>
			<?PHP 
			if (is_array($options_widgets)) {
				foreach ($options_widgets as $number => $values) {
					echo "<option value=\"".$number."\">".$values['optionname']."</option>";
				}
			}
			?>
			</select></span>
			<input type="submit" name="Submit" class="button-secondary" value="<?php _e('Save Changes'); ?>" />
		</div>
		<br class="clear" /> 
	</div> 
	
	<?php
	if (is_array($options_widgets)) {
	foreach ( $options_widgets as $optionname => $optionvalues) {
	?>
	<div class="postbox<?PHP if ($_POST['minimeta-jsopen-'.$optionname]!=1) echo " if-js-closed";?>" id="widget-opt-<?php echo $optionname; ?>">
	<input type="hidden" name="minimeta-jsopen-<?php echo $optionname; ?>" value="<?PHP echo $_POST['minimeta-jsopen-'.$optionname];?>" />
	<?PHP
		echo "<h3 class=\"hndle\">". __('Widget Option:', 'MiniMetaWidget')." ".$optionvalues['optionname']."</h3>";
	?>
	<div class="inside">
		<input type="hidden" name="widget-options[<?php echo $optionname; ?>][optionname]" value="<?php echo $optionvalues['optionname']; ?>" />
		
		<?php $loginout='out'; ?>
		<div class="widget-logout">
			<h4 style="text-align:center;"><?php echo _e('Show when Loggt out:'); ?></h4>
			<div class="widget-logout-list">
	<?PHP  	$ordering=0;
			foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) {
				$optionsnumber='';
				for ($i=0;$i<=sizeof($optionvalues[$loginout]);$i++) {
					if ($partname==$optionvalues[$loginout][$i]['part']) $optionsnumber=$i;
				}
				if ($partvalues[4]) { ?>
				<div class="widget-logout-item if-js-closed">
					<h4 class="widget-logout-title"><span><input class="checkbox-active" type="checkbox" <?php echo checked($optionvalues[$loginout][$optionsnumber]['part'],$partname); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][active]" /> <?php echo $partvalues[0]; ?></span><br class="clear" /></h4>
					<input type="hidden"  name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][part]" value="<?php echo $partname; ?>" />
					<?PHP if ($partvalues[2]) {?>
					<div class="widget-logout-control">
						<?php				
						$options=$optionvalues[$loginout][$optionsnumber]['args'];
						call_user_func($partvalues[2], $options );
						?>
					</div>
					<?PHP } ?>
				</div>
		<?PHP  	$ordering++;
				}
			} ?>
			</div>
		</div>
		
		<?php $loginout='in'; ?>
		<div class="widget-login">
			<h4 style="text-align:center;"><?php echo _e('Show when Loggt in:'); ?></h4>
			<div class="widget-login-list">
	<?PHP  	$ordering=0;
			foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) { 
				$optionsnumber='';
				for ($i=0;$i<=sizeof($optionvalues[$loginout]);$i++) {
					if ($partname==$optionvalues[$loginout][$i]['part']) $optionsnumber=$i;
				}
				if ($partvalues[3]) {?>
				<div class="widget-login-item if-js-closed">
					<h4 class="widget-login-title"><span><input class="checkbox-active" type="checkbox" <?php echo checked($optionvalues[$loginout][$optionsnumber]['part'],$partname); ?> name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][active]" /> <?php echo $partvalues[0]; ?></span> <br class="clear" /></h4>
					<input type="hidden" name="widget-options[<?php echo $optionname; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][part]" value="<?php echo $partname; ?>" />
					<?PHP if ($partvalues[2]) { ?>
					<div class="widget-login-control">
						<?php				
						$options=$optionvalues[$loginout][$optionsnumber]['args'];
						call_user_func($partvalues[2], $options );
						?>
					</div>
					<?PHP } ?>
				</div>
		<?PHP  	$ordering++;
				}
			} ?>
			</div>
		</div>	
		<br class="clear" />	

		<div class="widget-general">
			<h4 style="text-align:center;"><?php echo _e('Generel Settings:'); ?></h4>
			<div class="widget-general-list">
				<div class="widget-general-item if-js-closed">
					<h4 class="widget-general-title"><span><?php _e('Stylesheet','MiniMetaWidget'); ?></span> <br class="clear" /></h4>
					<div class="widget-general-control">
						&lt;ul&gt;
						<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($optionvalues['general']['style']['ul'])); ?>" name="widget-options[<?php echo $optionname; ?>][general][style][ul]" /><br />
						&lt;li&gt;
						<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($optionvalues['general']['style']['li'])); ?>" name="widget-options[<?php echo $optionname; ?>][general][style][li]" /><br />
					</div>
				</div>
				<div class="widget-general-item if-js-closed">
					<h4 class="widget-general-title"><span><?php _e('Seidbar Widget Settings (PHP Function)','MiniMetaWidget'); ?></span> <br class="clear" /></h4>
					<div class="widget-general-control">
						<?php 
						if (!isset($optionvalues['general']['php']['title'])) $optionvalues['general']['php']['title']=__('Meta'); //def. Options
						if (!isset($optionvalues['general']['php']['before_title'])) $optionvalues['general']['php']['before_title']='<h2>';
						if (!isset($optionvalues['general']['php']['after_title'])) $optionvalues['general']['php']['after_title']='</h2>';
						if (!isset($optionvalues['general']['php']['before_widget'])) $optionvalues['general']['php']['before_widget']='<div class="MiniMetaWidgetSiedbar">';
						if (!isset($optionvalues['general']['php']['after_widget'])) $optionvalues['general']['php']['after_widget']='</div>';
						?>
						<?php _e('Title:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $optionname; ?>][general][php][title]" value="<?php echo htmlentities(stripslashes($optionvalues['general']['php']['title'])); ?>" /><br />
						<?php _e('Before Title:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $optionname; ?>][general][php][before_title]" value="<?php echo htmlentities(stripslashes($optionvalues['general']['php']['before_title'])); ?>" /><br />
						<?php _e('After Title:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $optionname; ?>][general][php][after_title]" value="<?php echo htmlentities(stripslashes($optionvalues['general']['php']['after_title'])); ?>" /><br />
						<?php _e('Before Widget:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $optionname; ?>][general][php][before_widget]" value="<?php echo htmlentities(stripslashes($optionvalues['general']['php']['before_widget'])); ?>" /><br />
						<?php _e('After Widget:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $optionname; ?>][general][php][after_widget]" value="<?php echo htmlentities(stripslashes($optionvalues['general']['php']['after_widget'])); ?>" /><br />
					</div>
				</div>
				<div class="widget-general-item if-js-closed">
					<h4 class="widget-general-title"><span><?php _e('Do not Display on Pages','MiniMetaWidget'); ?></span> <br class="clear" /></h4>
					<div class="widget-general-control">
						<b><?php _e('out','MiniMetaWidget'); ?>&nbsp;&nbsp;<?php _e('in','MiniMetaWidget'); ?>&nbsp;&nbsp;&nbsp;<?php _e('Pages','MiniMetaWidget'); ?></b><br />
						&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['out']['home'],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][out][home]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['in']['home'],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][in][home]" />&nbsp;&nbsp;<?php _e('Homepage','MiniMetaWidget');?><br />
						&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['out']['singlepost'],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][out][singlepost]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['in']['singlepost'],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][in][singlepost]" />&nbsp;&nbsp;<?php _e('Single Post','MiniMetaWidget');?><br />
						&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['out']['search'],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][out][search]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['in']['search'],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][in][search]" />&nbsp;&nbsp;<?php _e('Search Page','MiniMetaWidget');?><br />
						&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['out']['errorpages'],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][out][errorpages]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['in']['errorpages'],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][in][errorpages]" />&nbsp;&nbsp;<?php _e('Error Page','MiniMetaWidget');?><br />
				<?php 	function _subpagecharakter($pages,$pageid) {
							foreach ($pages as $page) {
								if ($page->post_parent!=0 and $page->ID==$pageid) {
									_subpagecharakter($pages,$page->post_parent);
									echo '&#8212; ';
								}
							}
						}
						$pages = get_pages('sort_column=menu_order&hierarchical=1'); 
						//print_r($pages);
						foreach ($pages as $page) { ?>
							&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['out'][$page->ID],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][out][<?php echo $page->ID;?>]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php echo checked($optionvalues['general']['pagesnot']['in'][$page->ID],true); ?> name="widget-options[<?php echo $optionname; ?>][general][pagesnot][in][<?php echo $page->ID;?>]" />&nbsp;&nbsp;<?php _subpagecharakter($pages,$page->ID); echo $page->post_title; ?><br />
				<?PHP	} ?>
					</div>
				</div>			
			</div>
		</div>
		<p> 
		<input type="button" class="button alignleft" value="<?php _e('Remove'); ?>" onclick="jQuery('#widget-opt-<?php echo $optionname;?>').remove();" />
		<input type="submit" name="Submit" class="button button-highlighted alignright" value="<?php _e('Save Changes'); ?>" />
		<br class="clear" />
		</p>
		</div></div>
	<?php } 
		}?>
</div>
</form> 
	
<div class="wrap metabox-holder"> 
	<h2><?php _e('MiniMeta Widget', 'MiniMetaWidget'); ?></h2>
	<div class="postbox if-js-closed">
		<h3 class="hndle"><span><?php _e('Usage', 'MiniMetaWidget'); ?></span></h3>
		<div class="inside">
			<table style="width:100%;"><tr><td style="width:50%;padding-right:10px;">
				<?php _e('1. Create a Widget Option above.', 'MiniMetaWidget'); ?><br />
				<?php _e('2. Place a Widget from WordPress Wigets, K2 Seidbar Modules or in Theme via PHP and select a Widget Option.', 'MiniMetaWidget'); ?><br />
				<?php _e('3. Redy.', 'MiniMetaWidget'); ?><br />
				&nbsp;<br />
				<strong><?php _e('Code too place a Widget via PHP:', 'MiniMetaWidget'); ?></strong><br />
				<code> &lt;?PHP if (function_exists('MiniMetaWidgetSidebar')) MiniMetaWidgetSidebar(WidgetOptionName); ?&gt; </code><br />
				&nbsp;<br />
			</td><td class="tablehalf">
				<strong>WidgetOptionName</strong> <?php _e('= Name of Widget Setting above', 'MiniMetaWidget'); ?><br />
			</td></tr></table>
		</div>
	</div>
	<div class="postbox if-js-closed">
		<h3 class="hndle"><span><?php _e('About', 'MiniMetaWidget'); ?></span></h3>
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
			</td><td class="tablehalf">
				<?php _e('If you find it useful, please consider donating.', 'MiniMetaWidget'); ?><br />&nbsp;<br />
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=daniel%40huesken-net%2ede&amp;item_name=MiniMeta%20Widget%20Plugin%20for%20WordPress&amp;no_shipping=1&amp;no_note=1&amp;tax=0&amp;currency_code=EUR&amp;lc=LV&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8" target="_blank"><img alt="Donate" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" /></a>
			</td></tr></table>
		</div>
	</div>
	<?php if(current_user_can('edit_plugins')) {?>
	<div class="postbox if-js-closed">
		<h3 class="hndle"><span><?php _e('Uninstall', 'MiniMetaWidget'); ?></span></h3>
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