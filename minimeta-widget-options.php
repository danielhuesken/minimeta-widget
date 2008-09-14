<?php
//Variables Variables Variables
$id = intval($_GET['id']);
$mode = trim($_GET['mode']);
$views_settings = array('widget_minimeta','widget_minimeta_options', 'widget_minimeta_adminlinks','widget_minimeta_seidbar_widget');

// Form Processing
$generl_options=get_option("widget_minimeta_options");
if ($generl_options['SeidbarNum']<1 or $generl_options['SeidbarNum']>9) $generl_options['SeidbarNum']=1;
// Update Options
if(!empty($_POST['Submit'])) {
	$update_views_queries = array();
	$update_views_text = array();
	
	for ($number=1;$number<=$generl_options['SeidbarNum'];$number++) {
		$options_seidbar_widget[$number]['title']=wp_specialchars($_POST['widget-minimeta'][$number]['title']);
		$widget_option_names=MiniMetaFunctions::widget_options();
		foreach ( (array) $widget_option_names as $option_name => $option_value ) {
			$options_seidbar_widget[$number][$option_name] = isset($_POST['widget-minimeta'][$number][$option_name]);
		}
		unset($options_seidbar_widget[$number]['adminlinks']);
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['adminlinks']);$i++) {
			$options_seidbar_widget[$number]['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][$number]['adminlinks'][$i]);
		}
		unset($options_seidbar_widget[$number]['linksin']);
		$options_seidbar_widget[$number]['linksin']="";
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['linksin']);$i++) {
			if (isset($_POST['widget-minimeta'][$number]['linksin'][$i])) $options_seidbar_widget[$number]['linksin'] .= $_POST['widget-minimeta'][$number]['linksin'][$i].",";
		}
		$options_seidbar_widget[$number]['linksin'] = substr($options_seidbar_widget[$number]['linksin'], 0, -1);
		unset($options_seidbar_widget[$number]['linksout']);
		$options_seidbar_widget[$number]['linksout']="";
		for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['linksout']);$i++) {
			if (isset($_POST['widget-minimeta'][$number]['linksout'][$i])) $options_seidbar_widget[$number]['linksout'] .= $_POST['widget-minimeta'][$number]['linksout'][$i].",";
		}
		$options_seidbar_widget[$number]['linksout'] = substr($options_seidbar_widget[$number]['linksout'], 0, -1);
	}
	$update_views_queries[] = update_option('widget_minimeta_seidbar_widget', $options_seidbar_widget);
	$update_views_text[] = __('MiniMeta seidbar Widget Options', 'MiniMetaWidget');
	
	
	if ($_POST['widget-minimeta-SeidbarNum']>=1 and $_POST['widget-minimeta-SeidbarNum']<=9) $generl_options['SeidbarNum']=$_POST['widget-minimeta-SeidbarNum'];
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
		

if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 

<div class="wrap"> 
	<h2><?php _e('MiniMeta Siedbar Widget Number', 'MiniMetaWidget'); ?></h2>
	<table class="form-table">
		<tr>
			<td valign="top" width="30%"><strong><?php _e('How many Siedbar Widgets:', 'MiniMetaWidget'); ?></strong></td>
			<td valign="top">
				<select class="select" name="widget-minimeta-SeidbarNum" id="widget-minimeta-SeidbarNum">
				<?php 
				for ($i=1;$i<=9;$i++) {
					$check="";
					if ($i==$generl_options['SeidbarNum']) $check="selected=\"selected\"";
					echo "<option value=\"".$i."\" ".$check.">".$i."</option>";
				} ?>
				</select>
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'MiniMetaWidget'); ?>" />
	</p>
</div>
<p>&nbsp;</p>

<div class="wrap"> 
	<h2><?php printf(__('MiniMeta Siedbar Widget %s Options', 'MiniMetaWidget'),$number); ?></h2>
	<?PHP
	for ($i=1; $i<=$generl_options['SeidbarNum']; $i++) {
	 $java="";
	 for ($y=1; $y<=$generl_options['SeidbarNum']; $y++) {
	  if ($y==$i) {
	   $java.="document.getElementById('siedebar-".$y."').style.display='block';";
	  } else {
	   $java.="document.getElementById('siedebar-".$y."').style.display='none';";
	  }
	 }
	 echo "<a href=\"javascript:".$java."\">".$i."</a> ";
	}
	?>
	<?php 
	$options_siedbar_widget = get_option('widget_minimeta_seidbar_widget');
	for ($number=1;$number<=$generl_options['SeidbarNum'];$number++) {
	unset($options_form);
	?>
	<table class="form-table" id="siedebar-<?php echo $number; ?>" style="display:<?php if ($number==1) echo "block"; else echo "none"; ?>;"> 
		 <tr>
		 <td valign="top" width="30%"><strong><?php printf(__('Siedbar Widget %s Options:', 'MiniMetaWidget'),$number); ?></strong></td>
			<td valign="top">
			<strong><?php _e('Title:', 'MiniMetaWidget'); ?></strong> <input type="text" id="minimeta-title-<?php echo $number; ?>" name="widget-minimeta[<?php echo $number; ?>][title]" size="50" value="<?php echo htmlspecialchars(stripslashes($options_siedbar_widget[$number]['title'])); ?>" />
			<?PHP
			$widget_option_names=MiniMetaFunctions::widget_options();
			foreach ( (array) $widget_option_names as $option_name => $option_value ) {
				if (!isset($options_siedbar_widget[$number][$option_name])) $options_siedbar_widget[$number][$option_name]=$option_value;
				$options_form[$option_name] = $options_siedbar_widget[$number][$option_name] ? 'checked="checked"' : '';
			}
			$options_form['adminlinks']=$options_siedbar_widget[$number]['adminlinks'];
			$options_form['linksin']=$options_siedbar_widget[$number]['linksin'];
			$options_form['linksout']=$options_siedbar_widget[$number]['linksout'];
			include('app/display/widgetcontrol.php'); ?>
			</td>
		</tr>	
		<tr>
		 <td valign="top" width="30%"><strong><?php printf(__('Siedbar Widget %s Usage:', 'MiniMetaWidget'),$number); ?></strong></td>
			<td valign="top">
				<strong>if (function_exists('MiniMetaSiedbarWidget')) MiniMetaSiedbarWidget('before_title','after_title','before_widget','after_widget',<?php echo $number; ?>);</strong>
			</td>
		</tr>
	</table>
	<?php } ?>
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