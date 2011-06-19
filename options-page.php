<?php
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

function _subpagecharakter($pages,$pageid) { //functon for subpages char
	foreach ($pages as $page) {
		if ($page->post_parent!=0 and $page->ID==$pageid) {
			_subpagecharakter($pages,$page->post_parent);
			echo '&#8212; ';
		}
	}
}

if(!empty($minimeta_message)) 
	echo '<div id="message" class="updated fade"><p>'.$minimeta_message.'</p></div>'; 

?>


<div class="wrap">
	<div id="icon-themes" class="icon32"><br /></div>
<h2><?php echo __('MiniMeta Widget', 'minimeta-widget')."&nbsp;<a href=\"".wp_nonce_url(admin_url('themes.php').'?page=minimeta-widget&action=new', 'new-config')."\" class=\"add-new-h2\">".esc_html__('Add New','minimeta-widget')."</a>" ?></h2>


<div id="poststuff" class="metabox-holder has-right-sidebar"> 
	<div id="side-info-column" class="inner-sidebar">
		<form id="posts-filter" action="<?PHP admin_url('themes.php'); ?>" method="get">
		<input type="hidden" name="page" value="minimeta-widget" />
		<?PHP  $minimeta_listtable->display(); ?>
		<div id="ajax-response"></div>
		</form>
		
		<br class="clear" />
		
		<div id="jobschedule" class="stuffbox">
			<h3 class="hndle"><span><?PHP _e('Usage','minimeta-widget'); ?></span></h3>
			<div class="inside">
				<?php _e('1. Create a Widget Config.', 'minimeta-widget'); ?><br />
				<?php _e('2. Configure your Widget Config as you wont.', 'minimeta-widget'); ?><br />
				<?php _e('3. Place a Widget from WordPress Widgets or in Theme via PHP and select a Widget Config.', 'minimeta-widget'); ?><br />
				<?php _e('4. Ready.', 'minimeta-widget'); ?><br />
				&nbsp;<br />
				<strong><?php _e('Code too place a Widget via PHP:', 'minimeta-widget'); ?></strong><br />
				<code> &lt;?PHP if (function_exists('MiniMetaWidgetSidebar')) MiniMetaWidgetSidebar(<?php _e('Name of Widget Config', 'minimeta-widget'); ?>); ?&gt; </code><br />
				&nbsp;<br />				
			</div>
		</div>				
	</div>
	
	<div id="post-body">
		<div id="post-body-content">
			<div class="stuffbox">
				<h3 class="hndle"><span><?PHP _e('Config Settings','minimeta-widget'); ?></span></h3>
				<div class="inside">
					<form action="" method="post">
					<?PHP
					if (!empty($_REQUEST['mmconfigid'])) {
						$mmconfigid=$_REQUEST['mmconfigid'];
						$adminlinks=get_option('minimeta_adminlinks');
						$options_widgets = get_option('minimeta_widget_options');
						if (empty($options_widgets))
							$options_widgets=array(); 
						wp_nonce_field('MiniMeta-options');
						?>
						<input type="hidden" name="mmconfigid" value="<?php echo $mmconfigid; ?>" />
						<input type="hidden" name="page" value="minimeta-widget" />
						<input type="hidden" name="action" value="" />
						<input type="hidden" name="widget-options[order][in]" id="orderingin" value="in[]=0&in[]=1&in[]=2&in[]=3&in[]=4&in[]=5&in[]=6&in[]=7&in[]=8&in[]=9&in[]=10&in[]=11&in[]=12&in[]=13&in[]=14&in[]=15&in[]=16&in[]=17&in[]=18&in[]=19&in[]=20" />
						<input type="hidden" name="widget-options[order][out]" id="orderingout" value="out[]=0&out[]=1&out[]=2&out[]=3&out[]=4&out[]=5&out[]=6&out[]=7&out[]=8&out[]=9&out[]=10&out[]=11&out[]=12&out[]=13&out[]=14&out[]=15&out[]=16&out[]=17&out[]=18&out[]=19&out[]=20" />
						<input type="submit" name="Submit" class="button-primary alignright" id="Submit" value="<?php _e('Save Changes'); ?>" />
						
						<span class="alignleft"><?php _e('Config Name:', 'minimeta-widget'); ?> <input type="text" title="<?php _e('Config Name'); ?>" name="widget-options[optionname]" value="<?php echo $options_widgets[$mmconfigid]['optionname']; ?>" /></span>
						<br class="clear" />
						<?php 
						$wigetpartsout = new MiniMetaWidgetParts($options_widgets[$mmconfigid],false,true);
						$wigetpartsin = new MiniMetaWidgetParts($options_widgets[$mmconfigid],true,true);
						?>
						<br class="clear" />	
						<div class="widget-general">
							<h4 style="text-align:center;"><?php echo _e('General Settings:','minimeta-widget'); ?></h4>
							<div class="widget-general-list">
								<div class="widget-general-item if-js-closed">
									<h4 class="widget-general-title"><span><?php _e('Stylesheet','minimeta-widget'); ?></span> <br class="clear" /></h4>
									<div class="widget-general-control">
										&lt;ul
										style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['style']['ul'])); ?>" name="widget-options[general][style][ul]" />&quot;
										class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['class']['ul'])); ?>" name="widget-options[general][class][ul]" />&quot;
										&gt;<br />
										&lt;li
										style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['style']['li'])); ?>" name="widget-options[general][style][li]" />&quot;
										class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['class']['li'])); ?>" name="widget-options[general][class][li]" />&quot;
										&gt;<br />
									</div>
								</div>
								<div class="widget-general-item if-js-closed">
									<h4 class="widget-general-title"><span><?php _e('Sidebar Widget Settings (PHP Function)','minimeta-widget'); ?></span> <br class="clear" /></h4>
									<div class="widget-general-control">
										<?php 
										if (!isset($options_widgets[$mmconfigid]['general']['php']['title'])) $options_widgets[$mmconfigid]['general']['php']['title']=__('Meta'); //def. Options
										if (!isset($options_widgets[$mmconfigid]['general']['php']['before_title'])) $options_widgets[$mmconfigid]['general']['php']['before_title']='<h2>';
										if (!isset($options_widgets[$mmconfigid]['general']['php']['after_title'])) $options_widgets[$mmconfigid]['general']['php']['after_title']='</h2>';
										if (!isset($options_widgets[$mmconfigid]['general']['php']['before_widget'])) $options_widgets[$mmconfigid]['general']['php']['before_widget']='<div class="widget widget_minimeta">';
										if (!isset($options_widgets[$mmconfigid]['general']['php']['after_widget'])) $options_widgets[$mmconfigid]['general']['php']['after_widget']='</div>';
										?>
										<?php _e('Title:'); ?>
										<input class="textinput" type="text" name="widget-options[general][php][title]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['title'])); ?>" /><br />
										<?php _e('Before Title:'); ?>
										<input class="textinput" type="text" name="widget-options[general][php][before_title]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['before_title'])); ?>" /><br />
										<?php _e('After Title:'); ?>
										<input class="textinput" type="text" name="widget-options[general][php][after_title]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['after_title'])); ?>" /><br />
										<?php _e('Before Widget:'); ?>
										<input class="textinput" type="text" name="widget-options[general][php][before_widget]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['before_widget'])); ?>" /><br />
										<?php _e('After Widget:'); ?>
										<input class="textinput" type="text" name="widget-options[general][php][after_widget]" value="<?php echo htmlentities(stripslashes($options_widgets[$mmconfigid]['general']['php']['after_widget'])); ?>" /><br />
									</div>
								</div>
								<div class="widget-general-item if-js-closed">
									<h4 class="widget-general-title"><span><?php _e('Display on Pages','minimeta-widget'); ?></span> <br class="clear" /></h4>
									<div class="widget-general-control">
										<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['notselected'],true); ?> value="1" name="widget-options[general][pagesnot][notselected]" />&nbsp;<?php _e('Display on <b>not</b> selected Pages','minimeta-widget');?><br />
										<b><?php _e('out','minimeta-widget'); ?>&nbsp;&nbsp;<?php _e('in','minimeta-widget'); ?>&nbsp;&nbsp;&nbsp;<?php _e('Pages','minimeta-widget'); ?></b><br />
										&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out']['home'],true); ?> value="1" name="widget-options[general][pagesnot][out][home]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in']['home'],true); ?> value="1" name="widget-options[general][pagesnot][in][home]" />&nbsp;&nbsp;<?php _e('Homepage','minimeta-widget');?><br />
										&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out']['singlepost'],true); ?> value="1" name="widget-options[general][pagesnot][out][singlepost]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in']['singlepost'],true); ?> value="1" name="widget-options[general][pagesnot][in][singlepost]" />&nbsp;&nbsp;<?php _e('Single Post','minimeta-widget');?><br />
										&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out']['search'],true); ?> value="1" name="widget-options[general][pagesnot][out][search]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in']['search'],true); ?> value="1" name="widget-options[general][pagesnot][in][search]" />&nbsp;&nbsp;<?php _e('Search Page','minimeta-widget');?><br />
										&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['out']['errorpages'],true); ?> value="1" name="widget-options[general][pagesnot][out][errorpages]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked($options_widgets[$mmconfigid]['general']['pagesnot']['in']['errorpages'],true); ?> value="1" name="widget-options[general][pagesnot][in][errorpages]" />&nbsp;&nbsp;<?php _e('Error Page','minimeta-widget');?><br />
								<?php 	
										$pages = get_pages('sort_column=menu_order&hierarchical=1'); 
										foreach ($pages as $page) { ?>
											&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked(in_array($page->ID,(array)$options_widgets[$mmconfigid]['general']['pagesnot']['out']['pages']),true); ?> value="<?php echo $page->ID; ?>" name="widget-options[general][pagesnot][out][pages][]" />&nbsp;&nbsp;&nbsp;<input class="checkbox" type="checkbox" <?php checked(in_array($page->ID,(array)$options_widgets[$mmconfigid]['general']['pagesnot']['in']['pages']),true); ?> value="<?php echo $page->ID; ?>" name="widget-options[general][pagesnot][in][pages][]" />&nbsp;&nbsp;<?php _subpagecharakter($pages,$page->ID); echo $page->post_title; ?><br />
								<?PHP	} ?>
									</div>
								</div>			
							</div>
						</div>
					<?PHP } else {
						_e('Choase config or create a new!!','minimeta-widget');
					}?>
				</form> 
				</div>

			</div>					
		</div>
	</div>
</div>
</div>
