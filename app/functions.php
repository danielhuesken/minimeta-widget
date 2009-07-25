<?PHP

class MiniMetaFunctions {
 	
	//WP-Head hooks high Priority
	function head_login() {  //copy action login_head to wp-head if login form enabeld for plugin hooks 
		$test=false;
		$options = get_option('minimeta_widget_options');
		//find out is a ligon form in any MiniMeta Widegt activatet
		if (is_array($options)) {
			foreach ( $options as $number => $value ) {
				for ($i=1;$i<=sizeof($value['out']);$i++) {
					if($value['out'][$i]['part']=='loginform') 
						$test=true;
						break 2;
				}
			}
		}
		if ($test) do_action('login_head'); //do action from login had	       
	}

	//function to generate admin links    
	function generate_adminlinks() { 
		global $menu,$submenu,$parent_file;
		//let orginal Variables unchanged
		$tempmenu=$menu;
		$tempsubmenu=$submenu;
		//scan the menu
		foreach ( $tempmenu as $key => $item ) {
			if ($item[2]=="edit-comments.php") //Overwrite for Comments menu without number since wp 2.5
				$item[0] = sprintf( __('Comments %s'), "" );
			if ($item[2]=="plugins.php") //Overwrite for Plugins menu without number since wp 2.6
				$item[0] = sprintf( __('Plugins %s'), "" );
		  if (!empty($item[0])) { //filter out empty menu entrys since WP 2.7
			$adminlinks[$key]['menu']=strip_tags($item[0]); //write Menues
			if (is_array($tempsubmenu[$item[2]])) { //look if submenu existing to crate submenu on men if they downt exists
				$menulink=false; unset($lowestkey);
				foreach ($tempsubmenu[$item[2]] as $keysub => $itemsub) { // Find submenu wisout an existing menu link
					if(!isset($lowestkey) or $lowestkey>$keysub) $lowestkey=$keysub;
					if ($itemsub[2]==$item[2])
						$menulink=true;
				}
				if (!$menulink) {
					$lowestkey--;
					$tempsubmenu[$item[2]][$lowestkey] = array($item[0], $item[1], $item[2]); //create submenu
					ksort($tempsubmenu[$item[2]]);
				}
			} else {
				$tempsubmenu[$item[2]][0] = array($item[0], $item[1], $item[2]); //create submenu
			}
			$menu_file = $item[2];
			if ( false !== $pos = strpos($menu_file, '?') )
				$menu_file = substr($menu_file, 0, $pos);
			foreach ($tempsubmenu[$item[2]] as $keysub => $itemsub) { //Crate submenus and links
				$adminlinks[$key][$keysub][0]=strip_tags($itemsub[0]);
				$adminlinks[$key][$keysub][1]=$itemsub[1];
				$menu_hook = get_plugin_page_hook($itemsub[2], $item[2]);
				$sub_file = $itemsub[2];
				if ( false !== $pos = strpos($sub_file, '?') )
					$sub_file = substr($sub_file, 0, $pos);				
				if (('index.php' != $itemsub[2]) && file_exists(WP_PLUGIN_DIR . "/$sub_file") || ! empty($menu_hook) ) {
					if ((file_exists(WP_PLUGIN_DIR . "/$menu_file") && !is_dir(WP_PLUGIN_DIR . "/{$item[2]}") ) || file_exists($menu_file))
						$adminlinks[$key][$keysub][2]= "admin.php?page=".$itemsub[2];
					else
						$adminlinks[$key][$keysub][2]= $item[2]."?page=".$itemsub[2];
				} else {
					$adminlinks[$key][$keysub][2]= $itemsub[2];
				}
			}
		  }
		}
		update_option('minimeta_adminlinks', $adminlinks);
 	}

	//Thems Option  menu entry
	function menu_entry() {
		if (function_exists('add_theme_page')) {
			$hook = add_theme_page(__('MiniMeta Widget','MiniMetaWidget'), __('MiniMeta Widget','MiniMetaWidget'), 'switch_themes', 'minimeta-widget',array('MiniMetaFunctions', 'options_form')) ;
		}
		add_action('load-'.$hook, array('MiniMetaFunctions', 'options_load'));
		if (current_user_can(10))
			add_action('load-'.$hook,array('MiniMetaFunctions', 'generate_adminlinks'),1); //Generate Adminlinks
		add_contextual_help($hook,MiniMetaFunctions::show_help());
	}	
	
	// Help too display
	function show_help() {
		$help .= '<div class="metabox-prefs">';
		$help .= '<a href="http://wordpress.org/tags/minimeta-widget" target="_blank">'.__('Support Forums', 'MiniMetaWidget').'</a>';
		$help .= ' | <a href="http://danielhuesken.de/portfolio/minimeta/" target="_blank">' . __('Plugin Homepage', 'MiniMetaWidget') . '</a>';
		$help .= ' | <a href="http://wordpress.org/extend/plugins/minimeta-widget" target="_blank">' . __('Plugin Home on WordPress.org', 'MiniMetaWidget') . '</a>';
		$help .= "</div>\n";	
		$help .= '<div class="metabox-prefs">';
		$help .= __('Version:', 'MiniMetaWidget').' '.WP_MINMETA_VERSION.' | ';
		$help .= __('Author:', 'MiniMetaWidget').' <a href="http://danielhuesken.de" target="_blank">Daniel H&uuml;sken</a>';
		$help .= "</div>\n";
		$help .= '<div class="metabox-prefs">';
		$help .='<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
		$help .='<input type="hidden" name="cmd" value="_donations" />';
		$help .='<input type="hidden" name="business" value="daniel@huesken-net.de" />';
		$help .='<input type="hidden" name="item_name" value="Daniel H�sken Plugin Donation" />';
		$help .='<input type="hidden" name="item_number" value="MiniMeta Widget" />';
		$help .='<input type="hidden" name="page_style" value="Primary" />';
		$help .='<input type="hidden" name="no_shipping" value="1" />';
		$help .='<input type="hidden" name="currency_code" value="EUR" />';
		$help .='<input type="hidden" name="tax" value="0" />';
		$help .='<input type="hidden" name="cn" value="Message / Note" />';
		$help .='<input type="hidden" name="lc" value="DE" />';
		$help .='<input type="hidden" name="bn" value="PP-DonationsBF" />';
		$help .='<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" name="submit" />';
		$help .='<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" style="clear:both;" />';
		$help .='</form>';
		$help .= "</div>\n";	
		return $help;
	}
	
	//Options Page
	function options_form() {
		global $minimeta_options_text,$mmconfigid;
		//If uninstall checked
		if(trim($_POST['uninstall_MiniMeta_yes']) == 'yes' and current_user_can('edit_plugins')) {
			check_admin_referer('MiniMeta-delete','wpnoncemmui');
			// Uninstall MiniMeta Widget
			echo '<div id="message" class="updated fade"><p>';
			MiniMetaFunctions::uninstall(true); //Show uninstll with echos
			echo '</p></div>'; 
			//  Deactivating MiniMeta Widget
			$deactivate_url = 'plugins.php?action=deactivate&amp;plugin='.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php';
			if(function_exists('wp_nonce_url')) 
				$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_'.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php');
			echo '<div class="wrap">';
			echo '<h2>'.__('Uninstall MiniMeta Widget', 'MiniMetaWidget').'</h2>';
			echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Finish The Uninstallation And MiniMeta Widget Will Be Deactivated Automatically.', 'MiniMetaWidget'), $deactivate_url).'</strong></p>';
			echo '</div>';
		} else {
			require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/options-form.php');
		}
	}
	
	//Options Page
	function options_load() {
		global $minimeta_options_text,$mmconfigid,$wp_version;
		//Css for Admin Section
		wp_enqueue_style('MiniMeta',plugins_url('/'.WP_MINMETA_PLUGIN_DIR.'/app/css/minimeta-admin.css'),'',WP_MINMETA_VERSION,'screen');
		wp_enqueue_script('MiniMetaOptions',plugins_url('/'.WP_MINMETA_PLUGIN_DIR.'/app/js/minimeta-options.js'),array('jquery','jquery-ui-core','jquery-ui-sortable'),WP_MINMETA_VERSION,true);
		wp_localize_script('MiniMetaOptions','MiniMetaL10n',array('edit'=>__('Edit')));
		//For save Options
		require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/options-save.php');
	}
	
	
    //delete Otions
	function uninstall($echo=false) {
		$option_settings=array('minimeta_widget_wp','minimeta_widget_options', 'minimeta_adminlinks');
		foreach($option_settings as $setting) {
			$delete_setting = delete_option($setting);
			if ($echo) {
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
		}
	}
	
	//Set start Options
	function install() {
		add_option('minimeta_widget_options');
		add_option('minimeta_adminlinks');
		MiniMetaFunctions::generate_adminlinks();
		if (get_option('widget_minimeta')) MiniMetaFunctions::update(); //Update if option exists
	}
	
	//update from older version
	function update() {
		delete_option('widget_minimeta');
		delete_option('widget_minimeta_adminlinks');
	}
	
	//add edit setting to plugins page
	function plugins_options_link($links) {
		$settings_link='<a href="admin.php?page=minimeta-widget" title="' . __('Go to Settings Page','MiniMetaWidget') . '" class="edit">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); 
		return $links;
	}

	// add all action and so on only if plugin loaded.
	function init() {	
		if (has_action('login_head') and !is_user_logged_in())
			add_action('wp_head', array('MiniMetaFunctions', 'head_login'),1);

		add_action('admin_menu', array('MiniMetaFunctions', 'menu_entry'));
		
		if (current_user_can('switch_themes')) 
			add_filter('plugin_action_links_'.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php', array('MiniMetaFunctions', 'plugins_options_link'));
		
		//Generate Adminlinks on plugin page
		if (current_user_can(10)) 
			add_action('load-plugins.php',array('MiniMetaFunctions', 'generate_adminlinks'),1); //Generate Adminlinkson plugins page
			
		//Support for Wordpress Widgets
		if (function_exists('register_sidebar_widget')) { //Widgest only
			if (!class_exists('WP_Widget')) { //test for WP_Widget class fron WP 2.8
				require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widgets-wp-2_5.php');
				add_action('widgets_init', array('MiniMetaWidgetWP', 'register'));
			} else {
				require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widgets-wp-2_8.php');	
				add_action('widgets_init', array('WP_Widget_MiniMeta', 'register'));
			}
			
		}
		//load seidbar widgets per function
		require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widgets-sidebar.php');
		//Widget Displaying
		require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widget-display.php');
		//Widget Parts
		require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widget-parts.php');
	} 	
}

?>