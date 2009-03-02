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
			foreach ($tempsubmenu[$item[2]] as $keysub => $itemsub) { //Crate submenus and links
				$adminlinks[$key][$keysub][0]=strip_tags($itemsub[0]);
				$adminlinks[$key][$keysub][1]=$itemsub[1];
				$menu_hook = get_plugin_page_hook($itemsub[2], $item[2]);       
				if ( file_exists(WP_PLUGIN_DIR . "/{$itemsub[2]}") || ! empty($menu_hook) ) {
					if ( 'admin.php' == $pagenow || !file_exists(WP_PLUGIN_DIR . "/$parent_file") )
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
		add_filter('contextual_help', array('MiniMetaFunctions', 'show_help'), 10, 2);
	}	
	
	// Help too display
	function show_help() {
	    $plugin_data=get_plugin_data(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.php');
		
		$help .= '<h5>' . __('Plugin Info', 'MiniMetaWidget') . '</h5>';
		$help .= '<div class="metabox-prefs">';
		$help .= __('Name:', 'MiniMetaWidget').' '.$plugin_data['Title'].' | ';
		$help .= __('Version:', 'MiniMetaWidget').' '.$plugin_data['Version'].' | ';
		$help .= __('Author:', 'MiniMetaWidget').' '.$plugin_data['Author'];
		$help .= "</div>\n";
		
		$help .= '<h5>' . __('More Help & Info', 'MiniMetaWidget') . '</h5>';
		$help .= '<div class="metabox-prefs">';
		$help .= '<a href="http://wordpress.org/tags/minimeta-widget" target="_blank">'.__('Support Forums', 'MiniMetaWidget').'</a>';
		$help .= ' | <a href="http://wordpress.org/extend/plugins/minimeta-widget" target="_blank">' . __('Plugin Home on WordPress.org', 'MiniMetaWidget') . '</a>';
		$help .= "</div>\n";	

		$help .= '<h5>' . __('Donate', 'MiniMetaWidget') . '</h5>';
		$help .= '<div class="metabox-prefs">';
		$help .='<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
		$help .='<input type="hidden" name="cmd" value="_donations" />';
		$help .='<input type="hidden" name="business" value="daniel@huesken-net.de" />';
		$help .='<input type="hidden" name="item_name" value="Daniel Hüsken Plugin Donation" />';
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
		wp_enqueue_style('MiniMeta',plugins_url('/'.WP_MINMETA_PLUGIN_DIR.'/app/css/minimeta-admin.css'),'','4.1.0','screen');
		wp_enqueue_script('MiniMetaOptions',plugins_url('/'.WP_MINMETA_PLUGIN_DIR.'/app/js/minimeta-options.js'),'jQuery','4.1.0',true);
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
		add_option('minimeta_widget_wp');
		add_option('minimeta_adminlinks');
		MiniMetaFunctions::generate_adminlinks();
		if (get_option('widget_minimeta')) MiniMetaFunctions::update(); //Update if option exists
	}
	
	//update from older version
	function update($optionswpwold) {
		delete_option('widget_minimeta');
		delete_option('widget_minimeta_adminlinks');
	}
	
	//add edit setting to plugins page
	function plugins_options_link($action_links) {
		$edit_link='<a href="admin.php?page=minimeta-widget" title="' . __('Go to Settings Page','MiniMetaWidget') . '" class="edit">' . __('Settings') . '</a>';
		return array_merge( array($edit_link), $action_links);
	}


	// add all action and so on only if plugin loaded.
	function init() {
		global $pagenow;
		
		//load Text Domain
		if (!function_exists('wp_print_styles')) {
			load_plugin_textdomain('MiniMetaWidget', PLUGINDIR.'/'.WP_MINMETA_PLUGIN_DIR.'/lang');	
		} else {
			load_plugin_textdomain('MiniMetaWidget', false, WP_MINMETA_PLUGIN_DIR.'/lang');	 //TextDomain for WP 2.6 and heiger
		}		
		
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
			require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widgets-wp.php');
			add_action('widgets_init', array('MiniMetaWidgetWP', 'register'));
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