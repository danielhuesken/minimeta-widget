<?PHP

class MiniMetaFunctions {
	
	//Js for options page
	function admin_load_js() 
	{
		wp_register_script('jquery', plugins_url('/'.WP_MINMETA_PLUGIN_DIR.'/app/js/jquery.js'), FALSE, '1.2.6');
		wp_enqueue_script('jquery.ui.core', plugins_url('/'.WP_MINMETA_PLUGIN_DIR.'/app/js/ui.core.js'), array('jquery'),'1.5.2');
		wp_enqueue_script('jquery.ui.tabs', plugins_url('/'.WP_MINMETA_PLUGIN_DIR.'/app/js/ui.tabs.js'), array('jquery'),'1.5.2');
		return;
	}

	//JS Admin Header 
	function admin_head() { 
		?>	
		<link rel="stylesheet" type="text/css" href="<?php echo(plugins_url('/'.WP_MINMETA_PLUGIN_DIR.'/app/css/minimeta-admin.css'));?>" />

		<script type="text/javascript">
		jQuery(document).ready(function()  
		{  
			jQuery("#minimetaopttabs > ul").tabs();
			jQuery("#minimetatabs > ul").tabs();
		});  
		</script> 

		<script type="text/javascript">
		function selectAll_widget_minimeta(selectBox,selectAll) {
		for (var i = 0; i < selectBox.options.length; i++) selectBox.options[i].selected = selectAll;
		}
		</script> 
		<?PHP
	}
    
	//WP-Head hooks high Priority
	function head_login() {  //copy action login_head to wp-head if login form enabeld for plugin hooks 
		$test=false;
		$options = get_option('minimeta_widget_options');
		//find out is a ligon form in any MiniMeta Widegt activatet
		foreach ( (array) $options as $widget_number => $widget_minmeta ) {
			if($widget_minmeta['loginform']) 
				$test=true;
		}
		if ($test) do_action('login_head'); //do action from login had	       
	}

	//WP-Head hooks low Priority
	function wp_head() {
		//Set Style sheet
		if (file_exists(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/custom/minimeta-widget.css')) {
			echo "<link rel=\"stylesheet\" href=\"".plugins_url("/".WP_MINMETA_PLUGIN_DIR."/custom/minimeta-widget.css")."\" type=\"text/css\" media=\"screen\" />";
		} elseif (file_exists(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/minimeta-widget.css')) {
			echo "<link rel=\"stylesheet\" href=\"".plugins_url("/".WP_MINMETA_PLUGIN_DIR."/minimeta-widget.css")."\" type=\"text/css\" media=\"screen\" />";
		}
	}

	//function to generate admin links    
	function generate_adminlinks() { 
		global $menu,$submenu;
		//let orginal Variables unchanged
		$tempmenu=$menu;
		$tempsubmenu=$submenu;
		//scan the menu
		foreach ( $tempmenu as $key => $item ) {
			$adminlinks[$key]['menu']=wp_specialchars(strip_tags($item[0])); //write Menues
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
				$adminlinks[$key][$keysub][0]=wp_specialchars(strip_tags($itemsub[0]));
				$adminlinks[$key][$keysub][1]=$itemsub[1];
				$menu_hook = get_plugin_page_hook($itemsub[2], $item[2]);       
				if (file_exists(ABSPATH . PLUGINDIR . "/".$itemsub[2]) || ! empty($menu_hook)) {
					if (!in_array($item[2],array('index.php','page-new.php','edit.php','themes.php','edit-comments.php','options-general.php','plugins.php','users.php','profile.php')) )
						$adminlinks[$key][$keysub][2]= "admin.php?page=".$itemsub[2];
					else
						$adminlinks[$key][$keysub][2]= $item[2]."?page=".$itemsub[2];
				} else {
					$adminlinks[$key][$keysub][2]= $itemsub[2];
				}
				if ($adminlinks[$key][$keysub][2]=="edit-comments.php?page=akismet-admin") //Overwrite for Akismet Spam menu without number
					$adminlinks[$key][$keysub][0] = __('Akismet Spam');
			}   
		}
		update_option('minimeta_adminlinks', $adminlinks);
	}

	//Thems Option  menu entry
	function menu_entry() {
		if (function_exists('add_theme_page')) {
			add_theme_page(__('MiniMeta Widget','MiniMetaWidget'), __('MiniMeta Widget','MiniMetaWidget'), 'switch_themes', WP_MINMETA_PLUGIN_DIR.'/app/minimeta-options.php') ;
		}
	}
	

	//Set start Options
	function install() {
		add_option('minimeta_widget_options',"","MiniMeta Widgets Options");
		add_option('minimeta_widget_wp',"","MiniMeta Wordpress Widgets Options");
		add_option('minimeta_adminlinks',"","MiniMeta Widget Generated Admin Links");
		MiniMetaFunctions::generate_adminlinks();
		//set def. options for default 
		$options = get_option('minimeta_widget_options');
		$options['default']['loginlink']=true;
		$options['default']['loginform']=false;
		$options['default']['logout']=true; 
		$options['default']['registerlink']=true;
		$options['default']['testcookie']=false; 
		$options['default']['redirect']=false; 
		$options['default']['seiteadmin']=true; 
		$options['default']['rememberme']=true; 
		$options['default']['rsslink']=true; 
		$options['default']['rsscommentlink']=true; 
		$options['default']['wordpresslink']=true; 
		$options['default']['lostpwlink']=false;
		$options['default']['profilelink']=false; 
		$options['default']['showwpmeta']=true; 
		$options['default']['displayidentity']=false; 
		$options['default']['useselectbox']=false; 
		$options['default']['notopics']=false;
		unset($options['default']['adminlinks']);
		unset($options['default']['linksin']);
		unset($options['default']['linksout']);
		update_option('minimeta_widget_options',$options);
	}
	
	  
	// add all action and so on only if plugin loaded.
	function init() {
		global $wp_version;
	  
		//Version checks
		if (version_compare($wp_version, '2.5', '<')) { // Let only Activate on WordPress Version 2.5 or heiger
			//Loads language files
			load_plugin_textdomain('MiniMetaWidget', PLUGINDIR.'/'.WP_MINMETA_PLUGIN_DIR.'/lang');	
			add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, MiniMeta Widget works only under WordPress 2.5 or higher',"MiniMetaWidget") . '</strong></p></div>\';'));
			return;
		} elseif (version_compare($wp_version, '2.6', '<')) {   // Pre-2.6 compatibility
			define( 'WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins' );
			if (!function_exists('site_url')) {
				function site_url($path = '', $scheme = null) { 
					return get_bloginfo('wpurl').'/'.$path;
				}
			}
			if (!function_exists('admin_url')) {
				function admin_url($path = '') {
					return get_bloginfo('wpurl').'/wp-admin/'.$path;
				}
			}
			if (!function_exists('plugins_url')) {
				function plugins_url($path = '') { 
					return get_option('siteurl') . '/wp-content/plugins/'.$path;
				}
			}
			//Loads language files
			load_plugin_textdomain('MiniMetaWidget', PLUGINDIR.'/'.WP_MINMETA_PLUGIN_DIR.'/lang');
		} else { //hieger than WP 2.6
			//Loads language files
			load_plugin_textdomain('MiniMetaWidget', false, WP_MINMETA_PLUGIN_DIR.'/lang');	
		}

		if (has_action('login_head') and !is_user_logged_in())
			add_action('wp_head', array('MiniMetaFunctions', 'head_login'),1);

		if (current_user_can('switch_themes')) {
			add_action('admin_menu', array('MiniMetaFunctions', 'menu_entry'));
			if (current_user_can(10))
				add_action('admin_init',array('MiniMetaFunctions', 'generate_adminlinks'),1);
			if((isset($_GET['page'])) && (stristr($_GET['page'], 'minimeta-options'))!==false) { //only on Option Page
				add_action('admin_init', array('MiniMetaFunctions', 'admin_load_js'));
				add_action('admin_head', array('MiniMetaFunctions', 'admin_head'));
			}
		}
	
		add_action('wp_head', array('MiniMetaFunctions', 'wp_head'));
		
	
		//Support for Sidbar tyeps
		if (class_exists('K2SBM'))  { //K2 SBM only
			require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widgets-k2sbm.php');
			MiniMetaWidgetK2SBM::register();
		} elseif (function_exists('register_sidebar_widget')) { //Widgest only
			require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widgets-wp.php');
			add_action('widgets_init', array('MiniMetaWidgetWP', 'register'));
		}
		//lod seidbar widgets per function
		require_once(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/app/widgets-sidebar.php');
	} 
	
}

?>