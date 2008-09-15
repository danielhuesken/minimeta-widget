<?php
//Variables Variables Variables
$id = intval($_GET['id']);
$mode = trim($_GET['mode']);
$views_settings = array('widget_minimeta','widget_minimeta_options', 'widget_minimeta_adminlinks','widget_minimeta_sidebar_widget');

// Form Processing
// Update Options
if(!empty($_POST['Submit'])) {
	$update_views_queries = array();
	$update_views_text = array();
	
	$newnumber=wp_specialchars($_POST['widget-minimeta-SidebarDelete']);
	
	foreach ($_POST['widget-minimeta'] as $number => $numbervalues) {
	  if ($newnumber!=$number){ //Change only not deleted
		$options_sidebar_widget[$number]['title']=wp_specialchars($_POST['widget-minimeta'][$number]['title']);
		$widget_option_names=MiniMetaFunctions::widget_options();
		foreach ( (array) $widget_option_names as $option_name => $option_value ) {
			$options_sidebar_widget[$number][$option_name] = isset($_POST['widget-minimeta'][$number][$option_name]);
		}
		unset($options_sidebar_widget[$number]['adminlinks']);
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['adminlinks']);$i++) {
			$options_sidebar_widget[$number]['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][$number]['adminlinks'][$i]);
		}
		unset($options_sidebar_widget[$number]['linksin']);
		$options_sidebar_widget[$number]['linksin']="";
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['linksin']);$i++) {
			if (isset($_POST['widget-minimeta'][$number]['linksin'][$i])) $options_sidebar_widget[$number]['linksin'] .= $_POST['widget-minimeta'][$number]['linksin'][$i].",";
		}
		$options_sidebar_widget[$number]['linksin'] = substr($options_sidebar_widget[$number]['linksin'], 0, -1);
		unset($options_sidebar_widget[$number]['linksout']);
		$options_sidebar_widget[$number]['linksout']="";
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['linksout']);$i++) {
			if (isset($_POST['widget-minimeta'][$number]['linksout'][$i])) $options_sidebar_widget[$number]['linksout'] .= $_POST['widget-minimeta'][$number]['linksout'][$i].",";
		}
		$options_sidebar_widget[$number]['linksout'] = substr($options_sidebar_widget[$number]['linksout'], 0, -1);
	  }
	}
	
	$number=wp_specialchars($_POST['widget-minimeta-SidebarNew']); //For new Sidebar Widget
	if (!empty($number)) {
		$options_sidebar_widget[$number]=MiniMetaFunctions::widget_options();
		$options_sidebar_widget[$number]['title']=__('Meta')." ".$number;	
	}
	
	$update_views_queries[] = update_option('widget_minimeta_Sidebar_widget', $options_sidebar_widget);
	$update_views_text[] = __('MiniMeta Sidebar Widget Options', 'MiniMetaWidget');
	
	
	if ($_POST['widget-minimeta-SidebarNum']>=1 and $_POST['widget-minimeta-SidebarNum']<=9) $generl_options['SidebarNum']=$_POST['widget-minimeta-SidebarNum'];
	$update_views_queries[] = update_option('widget_minimeta_options', $generl_options);
	$update_views_text[] = __('MiniMeta Generel Options', 'MiniMetaWidget');
	
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
		
$options_sidebar_widget = get_option('widget_minimeta_Sidebar_widget');
	
if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 

<div class="wrap"> 
	<h2><?php _e('MiniMeta Sidebar Widget Options', 'MiniMetaWidget'); ?></h2>
	<table class="form-table" border="1"><tr>
	<?PHP
	foreach ($options_sidebar_widget as $tabs => $values) {
	 $java="";
	 foreach ($options_sidebar_widget as $javatabs => $javavalues) {
	  if ($javatabs==$tabs) {
	   $java.="document.getElementById('siedebar-".$javatabs."').style.display='block';";
	  } else {
	   $java.="document.getElementById('siedebar-".$javatabs."').style.display='none';";
	  }
	 }
	 echo "<td onclick=\"".$java."\" align=\"center\"><strong><i>".$tabs."</i></strong></td>";
	}
	?>
	<td style="width:140px" align=\"center\"><strong><?php _e('New:', 'MiniMetaWidget'); ?></strong><input type="text" id="widget-minimeta-SidebarNew" name="widget-minimeta-SidebarNew" size="10"  /></td>
	<td style="width:120px" align=\"center\"><strong><?php _e('Delete:', 'MiniMetaWidget'); ?></strong><select id="widget-minimeta-SidebarDelete" name="widget-minimeta-SidebarDelete" size="1">
	<option value=""><?php _e('none', 'MiniMetaWidget'); ?></option>
	<?PHP 
	foreach ($options_sidebar_widget as $number => $values) {
		if ($number!="default") echo "<option value=\"".$number."\">".$number."</option>";
	}
	?>
	</select></td>
	<td  style="width:120px" align=\"center\"><input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" /></td>
	</tr>
	</table>
	<?php 
	$options_sidebar_widget = get_option('widget_minimeta_Sidebar_widget');
	foreach ($options_sidebar_widget as $number => $numbervalues) {
	unset($options_form);
	if (empty($firstnumber) and empty($_POST['widget-minimeta-SidebarNew'])) $firstnumber=$number;
	if (empty($firstnumber) and !empty($_POST['widget-minimeta-SidebarNew'])) $firstnumber=wp_specialchars($_POST['widget-minimeta-SidebarNew']);
	?>
	<table class="form-table" id="siedebar-<?php echo $number; ?>" style="display:<?php if ($number==$firstnumber) echo "block"; else echo "none"; ?>;"> 
		 <tr>
		 <td valign="top" width="30%"><strong><?php printf(__('Sidebar Widget %s Options:', 'MiniMetaWidget'),$number); ?></strong></td>
			<td valign="top">
			<strong><?php _e('Title:', 'MiniMetaWidget'); ?></strong> <input type="text" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" size="50" value="<?php echo htmlspecialchars(stripslashes($options_sidebar_widget[$number]['title'])); ?>" />
			<?PHP
			$widget_option_names=MiniMetaFunctions::widget_options();
			foreach ( (array) $widget_option_names as $option_name => $option_value ) {
				if (!isset($options_sidebar_widget[$number][$option_name])) $options_sidebar_widget[$number][$option_name]=$option_value;
				$options_form[$option_name] = $options_sidebar_widget[$number][$option_name] ? 'checked="checked"' : '';
			}
			$options_form['adminlinks']=$options_sidebar_widget[$number]['adminlinks'];
			$options_form['linksin']=$options_sidebar_widget[$number]['linksin'];
			$options_form['linksout']=$options_sidebar_widget[$number]['linksout'];
			include('app/display/widgetcontrol.php'); ?>
			</td>
		</tr>	
		<tr>
		 <td valign="top" width="30%"><strong><?php printf(__('Sidebar Widget %s Usage:', 'MiniMetaWidget'),$number); ?></strong></td>
			<td valign="top">
				<strong>if (function_exists('MiniMetaSidebarWidget')) MiniMetaSidebarWidget('before_title','after_title','before_widget','after_widget','<?php echo $number; ?>');</strong>
			</td>
		</tr>
	</table>
	<?php } ?>
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