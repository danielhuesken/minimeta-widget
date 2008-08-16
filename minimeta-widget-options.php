<?php
if (current_user_can(10))
	MiniMetaFunctions::generate_adminlinks();
### Variables Variables Variables
$base_name = plugin_basename(__FILE__);
$base_page = 'themes.php?page='.$base_name;
$id = intval($_GET['id']);
$mode = trim($_GET['mode']);
$views_settings = array('widget_minimeta', 'widget_minimeta_adminlinks','widget_minimeta_fixed_widget');

### Form Processing
// Update Options
if(!empty($_POST['Submit'])) {
	$options_fiexed_widget[1]['title']=wp_specialchars($_POST['widget-minimeta'][1]['title']);
	$widget_option_names=MiniMetaFunctions::widget_options();
	foreach ( (array) $widget_option_names as $option_name => $option_value ) {
		$options_fiexed_widget[1][$option_name] = isset($_POST['widget-minimeta'][1][$option_name]);
	}
    unset($options_fiexed_widget[1]['adminlinks']);
    for ($i=0;$i<sizeof($_POST['widget-minimeta'][1]['adminlinks']);$i++) {
        $options_fiexed_widget[1]['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][1]['adminlinks'][$i]);
    }
	
	$update_views_queries = array();
	$update_views_text = array();
	$update_views_queries[] = update_option('widget_minimeta_fixed_widget', $options_fiexed_widget);
	$update_views_text[] = __('MiniMeta Fixed Widget Options', 'MiniMetaWidget');
	
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
				if (defined('K2_LOAD_SBM') and K2_LOAD_SBM) sbm_delete_option('widget_minimeta');
				echo '</p>';
				echo '</div>'; 
				$mode = 'end-UNINSTALL';
			}
			break;
	}
}


### Determines Which Mode It Is
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
		$options_fiexed_widget = get_option('widget_minimeta_fixed_widget');

if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<div class="wrap"> 
	<h2><?php _e('MiniMeta Fiexed Widget Options', 'MiniMetaWidget'); ?></h2>
	<?php $number=1; ?>
	<table class="form-table">
		<tr>
			<td valign="top" width="30%"><strong><?php _e('Fiexed Widget Title:', 'MiniMetaWidget'); ?></strong></td>
			<td valign="top">
				<input type="text" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" size="70" value="<?php echo htmlspecialchars(stripslashes($options_fiexed_widget[$number]['title'])); ?>" />
			</td>
		</tr>
		 <tr>
		 <td valign="top" width="30%"><strong><?php _e('Fiexed Widget Options:', 'MiniMetaWidget'); ?></strong></td>
			<td valign="top">
			<?
			$number=1;
			$widget_option_names=MiniMetaFunctions::widget_options();
			foreach ( (array) $widget_option_names as $option_name => $option_value ) {
				if (!isset($options_fiexed_widget[$number][$option_name])) $options_fiexed_widget[$number][$option_name]=$option_value;
				$options_form[$option_name] = $options_fiexed_widget[$number][$option_name] ? 'checked="checked"' : '';
			}
			$options_form['adminlinksset']=$options_fiexed_widget[$number]['adminlinks'];
			include('app/display/widgetcontrol.php'); ?>
			</td>
		</tr>
		<tr>
		 <td valign="top" width="30%"><strong><?php _e('Fiexed Widget Usage:', 'MiniMetaWidget'); ?></strong></td>
			<td valign="top">
				Put Code to template. 
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
	</p>
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