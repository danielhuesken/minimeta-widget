<?php
function _subpagecharakter($pages,$pageid) { //functon for subpages char
	foreach ($pages as $page) {
		if ($page->post_parent!=0 and $page->ID==$pageid) {
			_subpagecharakter($pages,$page->post_parent);
			echo '&#8212; ';
		}
	}
}


// Main Page

$adminlinks=get_option('minimeta_adminlinks');
$options_widgets = get_option('minimeta_widget_options');

if(!empty($minimeta_options_text)) { echo '<div id="message" class="updated fade"><p>'.$minimeta_options_text.'</p></div>'; } 

?>


<div class="wrap">
	<div id="icon-themes" class="icon32"><br /></div>
<h2><?php _e('MiniMeta Widget', 'MiniMetaWidget'); ?></h2>

<ul class="subsubsub">
<li><a href="<?PHP echo $_SERVER['REQUEST_URI']; ?>&amp;subpage="<?PHP if ($_REQUEST['subpage']=="") echo ' class="current"'; ?>><?php _e('Configs', 'MiniMetaWidget'); ?></a> |</li>
<?php if(current_user_can('edit_plugins')) {?><li><a href="<?PHP echo $_SERVER['REQUEST_URI']; ?>&amp;subpage=Uninstall"<?PHP if ($_REQUEST['subpage']=="Uninstall") echo ' class="current"'; ?>><?php _e('Uninstall', 'MiniMetaWidget'); ?></a> |</li><?php } ?>
<li><a href="<?PHP echo $_SERVER['REQUEST_URI']; ?>&amp;subpage=Usage"<?PHP if ($_REQUEST['subpage']=="Usage") echo ' class="current"'; ?>><?php _e('Usage', 'MiniMetaWidget'); ?></a></li> 
</ul>
<div class="clear"></div> 

<?php if ($_REQUEST['subpage']=="") { ?>

<form id="config-action" action="" method="post">	
	<div class="tablenav">
		<div class="alignleft actions">
			<?php _e('Config:', 'MiniMetaWidget'); ?> 
			<select name="mmconfigid">
			<?PHP
			foreach ( $options_widgets as $optionid => $optionvalues) {
				if (empty($mmconfigid)) $mmconfigid=$optionid;
			    $checkmmconfigid=$mmconfigid==$optionid ? ' selected="selected"' : '';
				echo "<option value=\"".$optionid."\"".$checkmmconfigid.">".$optionvalues['optionname']."</option>";
			}
			?>
			</select>
			<input type="submit" name="gobutton" class="button-primary action" title="<?php _e('Go to MiniMeta widget config', 'MiniMetaWidget'); ?>" value="<?php _e('Go!', 'MiniMetaWidget'); ?>"/>
			<?php wp_nonce_field('MiniMeta-options','wpnoncemm'); ?>
		</div>
		<div class="alignright actions">
			<input type="submit" name="addbutton" class="button-secondary" title="<?php _e('adds a new MiniMeta widget config with default values', 'MiniMetaWidget'); ?>" value="<?php _e('Add New Config', 'MiniMetaWidget'); ?>"/>
			<input type="submit" name="dupbutton" class="button-secondary"  title="<?php _e('clones the current MiniMeta widget config', 'MiniMetaWidget'); ?>" value="<?php _e('Duplicate This Config', 'MiniMetaWidget'); ?>"/>
			<input title="<?php _e('This will delete the current MiniMeta widget config - no warning!', 'MiniMetaWidget'); ?>" type="submit" onclick="return confirm('<?php _e('This will delete the current MiniMeta widget config!', 'MiniMetaWidget'); ?>')" name="delbutton" class="button-secondary" value="<?php _e('Delete THIS Config', 'MiniMetaWidget'); ?>"/>
		</div>	
		<div class="clear"></div> 
	</div>
</form>
<div class="clear"></div> 

<?php if (!empty($mmconfigid)) { ?>

<form action="" method="post">	
<?php wp_nonce_field('MiniMeta-options','wpnoncemmconf'); ?>
<input type="hidden" name="mmconfigid" value="<?php echo $mmconfigid; ?>" />

	<table class="widefat" cellspacing="0">
	<thead>
	<tr>
	<th scope="col">
		<span class="alignleft"><?php _e('Config Name:', 'MiniMetaWidget'); ?> <input type="text" title="<?php _e('Config Name'); ?>" name="widget-options[<?php echo $mmconfigid; ?>][optionname]" value="<?php echo $options_widgets[$mmconfigid]['optionname']; ?>" size="30" /></span>
		<span class="alignright"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></span>
		<br class="clear" />
	</th>
	</tr>
	</thead>

	<tbody>
	<tr><td>
		<?php $loginout='out'; ?>
		<div class="widget-logout">
			<h4 style="text-align:center;"><?php echo _e('Show when Loggt out:'); ?></h4>
			<div class="widget-logout-list">
	<?PHP  	$ordering=0;
			foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) {
				$optionsnumber='';
				for ($i=0;$i<=sizeof($options_widgets[$mmconfigid][$loginout]);$i++) {
					if ($partname==$options_widgets[$mmconfigid][$loginout][$i]['part']) $optionsnumber=$i;
				}
				if ($partvalues[4]) { ?>
				<div class="widget-logout-item if-js-closed">
					<h4 class="widget-logout-title"><span><input class="checkbox-active" type="checkbox" <?php checked($options_widgets[$mmconfigid][$loginout][$optionsnumber]['part'],$partname); ?> name="widget-options[<?php echo $mmconfigid; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][active]" /> <?php echo $partvalues[0]; ?></span><br class="clear" /></h4>
					<input type="hidden"  name="widget-options[<?php echo $mmconfigid; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][part]" value="<?php echo $partname; ?>" />
					<?PHP if ($partvalues[2]) {?>
					<div class="widget-logout-control">
						<?php				
						$options=$options_widgets[$mmconfigid][$loginout][$optionsnumber]['args'];
						$options['ordering']=$ordering;
						$options['loginout']=$loginout;
						$options['optionname']=$mmconfigid;
						call_user_func($partvalues[2], $options);
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
				for ($i=0;$i<=sizeof($options_widgets[$mmconfigid][$loginout]);$i++) {
					if ($partname==$options_widgets[$mmconfigid][$loginout][$i]['part']) $optionsnumber=$i;
				}
				if ($partvalues[3]) {?>
				<div class="widget-login-item if-js-closed">
					<h4 class="widget-login-title"><span><input class="checkbox-active" type="checkbox" <?php checked($options_widgets[$mmconfigid][$loginout][$optionsnumber]['part'],$partname); ?> name="widget-options[<?php echo $mmconfigid; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][active]" /> <?php echo $partvalues[0]; ?></span> <br class="clear" /></h4>
					<input type="hidden" name="widget-options[<?php echo $mmconfigid; ?>][<?php echo $loginout; ?>][<?php echo $ordering; ?>][part]" value="<?php echo $partname; ?>" />
					<?PHP if ($partvalues[2]) { ?>
					<div class="widget-login-control">
						<?php				
						$options=$options_widgets[$mmconfigid][$loginout][$optionsnumber]['args'];
						$options['ordering']=$ordering;
						$options['loginout']=$loginout;
						$options['optionname']=$mmconfigid;
						call_user_func($partvalues[2], $options);
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
						<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['style']['ul'])); ?>" name="widget-options[<?php echo $mmconfigid; ?>][general][style][ul]" /><br />
						&lt;li&gt;
						<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['style']['li'])); ?>" name="widget-options[<?php echo $mmconfigid; ?>][general][style][li]" /><br />
					</div>
				</div>
				<div class="widget-general-item if-js-closed">
					<h4 class="widget-general-title"><span><?php _e('Seidbar Widget Settings (PHP Function)','MiniMetaWidget'); ?></span> <br class="clear" /></h4>
					<div class="widget-general-control">
						<?php 
						if (!isset($options_widgets[$mmconfigid]['general']['php']['title'])) $options_widgets[$mmconfigid]['general']['php']['title']=__('Meta'); //def. Options
						if (!isset($options_widgets[$mmconfigid]['general']['php']['before_title'])) $options_widgets[$mmconfigid]['general']['php']['before_title']='<h2>';
						if (!isset($options_widgets[$mmconfigid]['general']['php']['after_title'])) $options_widgets[$mmconfigid]['general']['php']['after_title']='</h2>';
						if (!isset($options_widgets[$mmconfigid]['general']['php']['before_widget'])) $options_widgets[$mmconfigid]['general']['php']['before_widget']='<div class="MiniMetaWidgetSiedbar">';
						if (!isset($options_widgets[$mmconfigid]['general']['php']['after_widget'])) $options_widgets[$mmconfigid]['general']['php']['after_widget']='</div>';
						?>
						<?php _e('Title:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $mmconfigid; ?>][general][php][title]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['title'])); ?>" /><br />
						<?php _e('Before Title:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $mmconfigid; ?>][general][php][before_title]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['before_title'])); ?>" /><br />
						<?php _e('After Title:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $mmconfigid; ?>][general][php][after_title]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['after_title'])); ?>" /><br />
						<?php _e('Before Widget:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $mmconfigid; ?>][general][php][before_widget]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['before_widget'])); ?>" /><br />
						<?php _e('After Widget:'); ?>
						<input class="textinput" type="text" name="widget-options[<?php echo $mmconfigid; ?>][general][php][after_widget]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['after_widget'])); ?>" /><br />
					</div>
				</div>
				<div class="widget-general-item if-js-closed">
					<h4 class="widget-general-title"><span><?php _e('Display on Pages','MiniMetaWidget'); ?></span> <br class="clear" /></h4>
					<div class="widget-general-control">
						<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['notselected'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][notselected]" />&nbsp;<?php _e('Display on <b>not</b> selected Pages','MiniMetaWidget');?><br />
						<b><?php _e('out','MiniMetaWidget'); ?>&nbsp;&nbsp;<?php _e('in','MiniMetaWidget'); ?>&nbsp;&nbsp;&nbsp;<?php _e('Pages','MiniMetaWidget'); ?></b><br />
						&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out']['home'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][out][home]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in']['home'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][in][home]" />&nbsp;&nbsp;<?php _e('Homepage','MiniMetaWidget');?><br />
						&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out']['singlepost'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][out][singlepost]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in']['singlepost'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][in][singlepost]" />&nbsp;&nbsp;<?php _e('Single Post','MiniMetaWidget');?><br />
						&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out']['search'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][out][search]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in']['search'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][in][search]" />&nbsp;&nbsp;<?php _e('Search Page','MiniMetaWidget');?><br />
						&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out']['errorpages'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][out][errorpages]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in']['errorpages'],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][in][errorpages]" />&nbsp;&nbsp;<?php _e('Error Page','MiniMetaWidget');?><br />
				<?php 	
						$pages = get_pages('sort_column=menu_order&hierarchical=1'); 
						//print_r($pages);
						foreach ($pages as $page) { ?>
							&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out'][$page->ID],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][out][<?php echo $page->ID;?>]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in'][$page->ID],true); ?> name="widget-options[<?php echo $mmconfigid; ?>][general][pagesnot][in][<?php echo $page->ID;?>]" />&nbsp;&nbsp;<?php _subpagecharakter($pages,$page->ID); echo $page->post_title; ?><br />
				<?PHP	} ?>
					</div>
				</div>			
			</div>
		</div>
	</td></tr>
	</tbody></table>
</form> 
<?php }?>
<?php }?>

<?php if ($_REQUEST['subpage']=="Usage") { ?>
		<div id="mm-usage">
				<?php _e('1. Create a Widget Config.', 'MiniMetaWidget'); ?><br />
				<?php _e('2. Place a Widget from WordPress Wigets or in Theme via PHP and select a Widget Config.', 'MiniMetaWidget'); ?><br />
				<?php _e('3. Ready.', 'MiniMetaWidget'); ?><br />
				&nbsp;<br />
				<strong><?php _e('Code too place a Widget via PHP:', 'MiniMetaWidget'); ?></strong><br />
				<code> &lt;?PHP if (function_exists('MiniMetaWidgetSidebar')) MiniMetaWidgetSidebar(WidgetConfigName); ?&gt; </code><br />
				&nbsp;<br />

				<strong>WidgetConfigName</strong> <?php _e('= Name of Widget Config from Configs', 'MiniMetaWidget'); ?><br />
		</div> 
		<div class="clear"></div> 
<?php } ?>
<?php if(current_user_can('edit_plugins') and $_REQUEST['subpage']=="Uninstall") {?>
		<div id="mm-uninstall">
			<form method="post" action=""> 
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
							foreach(array('minimeta_widget_wp','minimeta_widget_options', 'minimeta_adminlinks') as $settings) {
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
				<input type="submit" name="do" value="<?php _e('UNINSTALL MiniMeta Widget', 'MiniMetaWidget'); ?>" class="button-primary" onclick="return confirm('<?php _e('You Are About To Uninstall MiniMeta Widget From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.', 'MiniMetaWidget'); ?>')" />
			</p>		
			</form>
		</div> 
		<div class="clear"></div> 
<?php } ?>
</div>
