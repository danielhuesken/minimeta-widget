<?php
//Variables Variables Variables
$id = intval($_GET['id']);
$mode = trim($_GET['mode']);
$views_settings = array('minimeta_widget_wp','minimeta_widget_options', 'minimeta_adminlinks');

// Form Processing
// Update Options
if(!empty($_POST['Submit'])) {
	$update_views_queries = array();
	$update_views_text = array();
	
	$newnumber=wp_specialchars($_POST['widget-minimeta-SidebarDelete']);
	
	foreach ($_POST['widget-minimeta'] as $number => $numbervalues) {
	  if ($newnumber!=$number){ //Change only not deleted
		$options_widgets[$number]['title']=wp_specialchars($_POST['widget-minimeta'][$number]['title']);
		$widget_option_names=MiniMetaFunctions::widget_options();
		foreach ( (array) $widget_option_names as $option_name => $option_value ) {
			$options_widgets[$number][$option_name] = isset($_POST['widget-minimeta'][$number][$option_name]);
		}
		unset($options_widgets[$number]['adminlinks']);
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['adminlinks']);$i++) {
			$options_widgets[$number]['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][$number]['adminlinks'][$i]);
		}
		unset($options_widgets[$number]['linksin']);
		$options_widgets[$number]['linksin']="";
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['linksin']);$i++) {
			if (isset($_POST['widget-minimeta'][$number]['linksin'][$i])) $options_widgets[$number]['linksin'] .= $_POST['widget-minimeta'][$number]['linksin'][$i].",";
		}
		$options_widgets[$number]['linksin'] = substr($options_widgets[$number]['linksin'], 0, -1);
		unset($options_widgets[$number]['linksout']);
		$options_widgets[$number]['linksout']="";
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['linksout']);$i++) {
			if (isset($_POST['widget-minimeta'][$number]['linksout'][$i])) $options_widgets[$number]['linksout'] .= $_POST['widget-minimeta'][$number]['linksout'][$i].",";
		}
		$options_widgets[$number]['linksout'] = substr($options_widgets[$number]['linksout'], 0, -1);
	  }
	}
	
	$number=wp_specialchars($_POST['widget-minimeta-SidebarNew']); //For new Sidebar Widget
	if (!empty($number)) {
		$options_widgets[$number]=MiniMetaFunctions::widget_options();
		$options_widgets[$number]['title']=__('Meta')." ".$number;	
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
// Decide What To Do
if(!empty($_POST['do'])) {
	//  Uninstall MiniMeta Widget
	switch($_POST['do']) {		
		case __('UNINSTALL MiniMeta Widget', 'MiniMetaWidget') :
			if(trim($_POST['uninstall_MiniMeta_yes']) == 'yes') {
				echo '<div id="message" class="updated fade">';
				echo '<p>';
				foreach($views_settings as $setting) {
					$delete_setting = delete_option($setting);
					if($delete_setting) {
						echo '<font color="green">';
						printf(__('Setting Key \'%s\' has been deleted.', 'MiniMetaWidget'), "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					} else {
						echo '<font color="red">';
						printf(__('Error deleting Setting Key \'%s\'.', 'MiniMetaWidget'), "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					}
				}
				if (defined('K2_LOAD_SBM') and K2_LOAD_SBM) sbm_delete_option('minimeta_widget');
				echo '</p>';
				echo '</div>'; 
				$mode = 'end-UNINSTALL';
			}
			break;
	}
}


// Determines Which Mode It Is
switch($mode) {
		//  Deactivating WP-PostViews
		case 'end-UNINSTALL':
			$deactivate_url = 'plugins.php?action=deactivate&amp;plugin='.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php';
			if(function_exists('wp_nonce_url')) { 
				$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_'.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php');
			}
			echo '<div class="wrap">';
			echo '<h2>'.__('Uninstall MiniMeta Widget', 'MiniMetaWidget').'</h2>';
			echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Finish The Uninstallation And MiniMeta Widget Will Be Deactivated Automatically.', 'MiniMetaWidget'), $deactivate_url).'</strong></p>';
			echo '</div>';
			break;

// Main Page
default:
		
$options_widgets = get_option('minimeta_widget_options');
	
if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 

<div class="wrap"> 
	<h2><?php _e('MiniMeta Sidebar Widget Options', 'MiniMetaWidget'); ?></h2>
	
	<div id="minimetatabs"> 
		<ul>
		<?PHP
			foreach ($options_widgets as $tabs => $values) {
				echo "<li><a href=\"#siedebar-".$tabs."\"><span>".$tabs."</span></a></li>";
			}
		?>
        </ul>
  
	<?php 
	foreach ($options_widgets as $number => $numbervalues) {
	unset($options_form);
	if (empty($firstnumber) and empty($_POST['widget-minimeta-SidebarNew'])) $firstnumber=$number;
	if (empty($firstnumber) and !empty($_POST['widget-minimeta-SidebarNew'])) $firstnumber=wp_specialchars($_POST['widget-minimeta-SidebarNew']);
	?>
	<div id="siedebar-<?php echo $number; ?>" style="width:500px;">
			<strong><?php _e('Title:', 'MiniMetaWidget'); ?></strong> <input type="text" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" size="50" value="<?php echo htmlspecialchars(stripslashes($options_widgets[$number]['title'])); ?>" />
			<?PHP
			$widget_option_names=MiniMetaFunctions::widget_options();
			foreach ( (array) $widget_option_names as $option_name => $option_value ) {
				if (!isset($options_widgets[$number][$option_name])) $options_widgets[$number][$option_name]=$option_value;
				$options_form[$option_name] = $options_widgets[$number][$option_name] ? 'checked="checked"' : '';
			}
			$options_form['adminlinks']=$options_widgets[$number]['adminlinks'];
			$options_form['linksin']=$options_widgets[$number]['linksin'];
			$options_form['linksout']=$options_widgets[$number]['linksout'];
			include('display/widgetoptions.php'); ?>
	</div>
	<?php } ?>
	</div>
	
	<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
	<?php _e('New:', 'MiniMetaWidget'); ?><input type="text" id="widget-minimeta-SidebarNew" name="widget-minimeta-SidebarNew" size="10" />
	<?php _e('Delete:', 'MiniMetaWidget'); ?><select id="widget-minimeta-SidebarDelete" name="widget-minimeta-SidebarDelete" size="1">
	<option value=""><?php _e('none', 'MiniMetaWidget'); ?></option>
	<?PHP 
	foreach ($options_widgets as $number => $values) {
		if ($number!="default") echo "<option value=\"".$number."\">".$number."</option>";
	}
	?>
	</select>
</div>
</form> 
<p>&nbsp;</p>

<!-- Uninstall MiniMeta Widget -->
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<div class="wrap"> 
	<h2><?php _e('Uninstall MiniMeta Widget', 'MiniMetaWidget'); ?></h2>
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
</div> 
</form>
<?php
} // End switch($mode)
?>