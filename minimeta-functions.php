<?PHP
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

//Function to call for seidbar widget
function MiniMetaWidgetSidebar($name) {
	foreach (get_option('minimeta_widget_options') as $option => $optionvalue) {
		if (strtolower($optionvalue['optionname'])==strtolower($name)) $mmconfigid=$option;
	}
	minimeta_widget_display($mmconfigid,array('type'=>'PHP'));
}	

//WordPress wiget
class WP_Widget_MiniMeta extends WP_Widget {

	function __construct() {
		parent::__construct(false, __('MiniMeta Widget','minimeta-widget'), array('classname' => 'widget_minimeta', 'description' => __('Displaying Meta links, Login Form and Admin Links','minimeta-widget')));
	}

	function widget( $args, $instance ) {
		extract($args);
		$args['title'] = empty($instance['title']) ? __('Meta') : apply_filters('widget_title', $instance['title']);
		//Set options to disply
		minimeta_widget_display($instance['config'],$args);
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['config'] = $new_instance['config'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','config'=>'' ) );
		$title = strip_tags($instance['title']);
		$config = $instance['config'];
		?>
			<p><label for="<?PHP echo $this->get_field_id('title'); ?>"><?PHP _e('Title:'); ?> <input class="widefat" id="<?PHP echo $this->get_field_id('title'); ?>" name="<?PHP echo $this->get_field_name('title'); ?>" type="text" value="<?PHP echo attribute_escape($title); ?>" /></label></p>
			<p><label for="<?PHP echo $this->get_field_id('config'); ?>" title="<?PHP _e('Select a Widget Config','minimeta-widget');?>"><?PHP _e('Widget Config:','minimeta-widget');?> 
			<?PHP 
			$options_widgets = get_option('minimeta_widget_options');
			if (is_array($options_widgets)) {
			?>
			<select class="widefat" name="<?PHP echo $this->get_field_name('config'); ?>" id="<?PHP echo $this->get_field_id('config'); ?>"><?PHP
			echo '<option value="">'.__('default','minimeta-widget').'</option>';
				foreach ($options_widgets as $name => $values) {
					?> <option value="<?PHP echo $name;?>"<?PHP selected($config,$name);?>><?PHP echo $values['optionname'];?></option> <?PHP
				}?>
			</select></label></p>
			<?PHP } else { ?>
				<span style="color:red;font-face:italic;"><?PHP _e('default','minimeta-widget'); ?></span>
			<?PHP } ?>
			<br />
			<span class="setting-description"><?PHP echo str_replace('%s',admin_url('themes.php').'?page=minimeta-widget',__('To make/change a widget config go to <a href="%s">MiniMeta Widget</a>','minimeta-widget')); ?></span>
		<?PHP
	}
}

//Function to show widget
function minimeta_widget_display($optionsetname='',$args) {
	global $ulopen,$stylegeneralul,$classgeneralul;
	if (is_array($args))
		extract( $args, EXTR_SKIP );
		
	//Overwrite vars if Seidbar Widget
	if (isset($type) and $type=="PHP") {
		$title = $options['general']['php']['title'];
		$before_title = $options['general']['php']['before_title'];
		$after_title = $options['general']['php']['after_title'];				
		$before_widget = $options['general']['php']['before_widget'];
		$after_widget = $options['general']['php']['after_widget'];
	}

	//load options
	$optionset = get_option('minimeta_widget_options');
	if (!isset($optionset[$optionsetname])) {  //find out option exists  and load
		//def options
		$options['general']['php']['title']=__('Meta');
		$options['general']['php']['before_title']='<h2>';
		$options['general']['php']['after_title']='</h2>';
		$options['general']['php']['before_widget']='<div class="MiniMetaWidgetSiedbar">';
		$options['general']['php']['after_widget']='</div>';
		$options['in'][0]['part']='title';
		$options['in'][0]['args']['title']=$title;
		$options['in'][0]['args']['before_title']=$before_title;
		$options['in'][0]['args']['after_title']=$after_title;
		$options['in'][1]['part']='linkseiteadmin';
		$options['in'][2]['part']='linkloginlogout';
		$options['in'][3]['part']='linkrss';
		$options['in'][4]['part']='linkcommentrss';
		$options['in'][5]['part']='linkwordpress';
		$options['in'][6]['part']='actionwpmeta';
		$options['out'][0]['part']='title';
		$options['out'][1]['part']='linkregister';
		$options['out'][2]['part']='linkloginlogout';
		$options['out'][3]['part']='linkrss';
		$options['out'][4]['part']='linkcommentrss';
		$options['out'][5]['part']='linkwordpress';
		$options['out'][6]['part']='actionwpmeta';
		$options['general']['pagesnot']['notselected']=true;
	} else {
		$options=$optionset[$optionsetname];
		for ($i=0;$i<=sizeof($options['in']);$i++) {
			if (!isset($options['in'][$i]))
				continue;
			if ($options['in'][$i]['part']=='title') {
				$options['in'][$i]['args']['title']=$title;
				$options['in'][$i]['args']['before_title']=$before_title;
				$options['in'][$i]['args']['after_title']=$after_title;				
			}
		}
		for ($i=0;$i<=sizeof($options['out']);$i++) {
			if (!isset($options['out'][$i]))
				continue;
			if ($options['out'][$i]['part']=='title') {
				$options['out'][$i]['args']['title']=$title;
				$options['out'][$i]['args']['before_title']=$before_title;
				$options['out'][$i]['args']['after_title']=$after_title;				
			}
		}
	}
	
	//Overwrite vars if Seidbar Widget
	if (isset($type) and $type=="PHP") {
		for ($i=0;$i<=sizeof($options['in']);$i++) {
			if ($options['in'][$i]['part']=='title') {
				$options['in'][$i]['args']['title']=$options['general']['php']['title'];
				$options['in'][$i]['args']['before_title']=$options['general']['php']['before_title'];
				$options['in'][$i]['args']['after_title']=$options['general']['php']['after_title'];				
			}
		}
		for ($i=0;$i<=sizeof($options['out']);$i++) {
			if ($options['out'][$i]['part']=='title') {
				$options['out'][$i]['args']['title']=$options['general']['php']['title'];
				$options['out'][$i]['args']['before_title']=$options['general']['php']['before_title'];
				$options['out'][$i]['args']['after_title']=$options['general']['php']['after_title'];				
			}
		}
		$before_widget = $options['general']['php']['before_widget'];
		$after_widget = $options['general']['php']['after_widget'];
	}
	
	//Not display Widget
	if(is_user_logged_in()) {
		if (sizeof($options['in'])<1) return; //Disolay widget only if parts are active
		$diplay=false;
		if (is_home() and empty($options['general']['pagesnot']['in']['home'])) $diplay=true;
		if (is_single() and empty($options['general']['pagesnot']['in']['singlepost'])) $diplay=true;
		if (is_search() and empty($options['general']['pagesnot']['in']['search'])) $diplay=true;
		if (is_404() and empty($options['general']['pagesnot']['in']['errorpages'])) $diplay=true;
		if ( !empty($options['general']['pagesnot']['in']['pages']) and is_page($options['general']['pagesnot']['in']['pages'])) $diplay=true;
		if ($diplay==false and !empty($options['general']['pagesnot']['notselected'])) return;
		if ($diplay==true and empty($options['general']['pagesnot']['notselected'])) return;
	} else {
		if (sizeof($options['out'])<1) return; //Disolay widget only if parts are active
		$diplay=false;
		if (is_home() and empty($options['general']['pagesnot']['out']['home'])) $diplay=true;
		if (is_single() and empty($options['general']['pagesnot']['out']['singlepost'])) $diplay=true;
		if (is_search() and empty($options['general']['pagesnot']['out']['search'])) $diplay=true;
		if (is_404() and empty($options['general']['pagesnot']['out']['errorpages'])) $diplay=true;
		if (!empty($options['general']['pagesnot']['out']['pages']) and is_page($options['general']['pagesnot']['out']['pages'])) $diplay=true;
		if ($diplay==false and !empty($options['general']['pagesnot']['notselected'])) return;
		if ($diplay==true and empty($options['general']['pagesnot']['notselected'])) return;
	}
	
	//Shown part of Widget
	echo $before_widget;

	$wigetparts = new MiniMetaWidgetParts($options,is_user_logged_in());
		
	echo $after_widget;		
}

//copy action login_head to wp-head if login form enabeld for plugin hooks
function minimeta_head_login() {   
	if (!has_action('login_head') or !is_user_logged_in()) 
		return;
	$options = get_option('minimeta_widget_options');
	//find out is a ligon form in any MiniMeta Widegt activatet
	if (is_array($options)) {
		foreach ( $options as $number => $value ) {
			for ($i=1;$i<=sizeof($value['out']);$i++) {
				if($value['out'][$i]['part']=='loginform') 
					do_action('login_head');
					break 2;
			}
		}
	}    
}

//function to generate admin links    
function minimeta_generate_adminlinks() { 
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
		if (is_array($tempsubmenu[$item[2]])) { //look if submenu existing to crate submenu on men if they don't exists
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
			if ($itemsub[2]=="update-core.php") //Overwrite for Update menu without number since wp 3.0
				$itemsub[0] = sprintf( __('Updates %s'), "" );
			$adminlinks[$key][$keysub][0]=strip_tags($itemsub[0]);
			$adminlinks[$key][$keysub][1]=$itemsub[1];
			$menu_hook = get_plugin_page_hook($itemsub[2], $item[2]);
			$sub_file = $itemsub[2];
			if ( false !== $pos = strpos($sub_file, '?') )
				$sub_file = substr($sub_file, 0, $pos);				
			if ( ( ('index.php' != $itemsub[2]) && file_exists(WP_PLUGIN_DIR . "/$sub_file") ) || ! empty($menu_hook) ) {
				$parent_exists = (file_exists(WP_PLUGIN_DIR . "/$menu_file") && !is_dir(WP_PLUGIN_DIR . "/{$item[2]}") ) || file_exists($menu_file);
				if ( $parent_exists )
					$adminlinks[$key][$keysub][2]= $item[2]."?page=".$itemsub[2];
				elseif ( 'admin.php' == $pagenow || !$parent_exists )
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
function minimeta_menu_entry() {
	$hook = add_theme_page(__('MiniMeta Widget','minimeta-widget'), __('MiniMeta Widget','minimeta-widget'), 'switch_themes', 'minimeta-widget','minimeta_options_page') ;
	add_action('load-'.$hook, 'minimeta_options_header');
}	

// Help too display
function minimeta_show_help() {
	$help = '<div class="metabox-prefs">';
	$help .= '<a href="http://wordpress.org/tags/minimeta-widget/" target="_blank">'.__('Support').'</a>';
	$help .= ' | <a href="http://wordpress.org/extend/plugins/minimeta-widget/faq/" target="_blank">' . __('FAQ') . '</a>';
	$help .= ' | <a href="http://danielhuesken.de/portfolio/minimeta" target="_blank">' . __('Plugin Homepage', 'filebrowser') . '</a>';
	$help .= ' | <a href="http://wordpress.org/extend/plugins/minimeta-widget" target="_blank">' . __('Plugin Home on WordPress.org', 'filebrowser') . '</a>';
	$help .= ' | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MUKCRHF5U4G2N" target="_blank">' . __('Donate') . '</a>';
	$help .= " | <script type=\"text/javascript\">
		var flattr_btn = 'compact';
		var flattr_url = 'http://danielhuesken.de/portfolio/minimeta/';
		</script><script src=\"http://api.flattr.com/button/load.js\" type=\"text/javascript\"></script>";
	$help .= "</div>\n";	
	$help .= '<div class="metabox-prefs">';
	$help .= __('Version:', 'backwpup').' '.WP_MINMETA_VERSION.' | ';
	$help .= __('Author:', 'backwpup').' <a href="http://danielhuesken.de" target="_blank">Daniel H&uuml;sken</a>';
	$help .= "</div>\n";
	return $help;
}

//Options Page
function minimeta_options_page() {
	global $minimeta_message,$current_screen,$minimeta_listtable;
	require_once(dirname(__FILE__).'/options-page.php');
}

//Options Page
function minimeta_options_header() {
	global $minimeta_message,$current_screen,$minimeta_listtable;
	//Css for Admin Section
	if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
		wp_enqueue_style('MiniMeta',plugins_url('css/minimeta-options.css',__FILE__),'',time(),'screen');
		wp_enqueue_script('MiniMetaOptions',plugins_url('js/minimeta-options.js',__FILE__),array('jquery','jquery-ui-core','jquery-ui-sortable'),time(),true);
	} else {
		wp_enqueue_style('MiniMeta',plugins_url('css/minimeta-options.css',__FILE__),'',WP_MINMETA_VERSION,'screen');
		wp_enqueue_script('MiniMetaOptions',plugins_url('js/minimeta-options.js',__FILE__),array('jquery','jquery-ui-core','jquery-ui-sortable'),WP_MINMETA_VERSION,true);
	}
	wp_localize_script('MiniMetaOptions','MiniMetaL10n',array('edit'=>__('Edit')));
	//For save Options
	require_once(dirname(__FILE__).'/options-header.php');
}

//add edit setting to plugins page
function minimeta_plugins_options_link($links) {
	$settings_link='<a href="'.admin_url('themes.php').'?page=minimeta-widget'.'" title="' . __('Go to Settings Page','minimeta-widget') . '" class="edit">' . __('Settings') . '</a>';
	array_unshift( $links, $settings_link ); 
	return $links;
}

//add admin bar menu
function minimeta_add_adminbar() {
	global $wp_admin_bar;
	if (!current_user_can('switch_themes') && !current_user_can( 'edit_theme_options' ))
		return;
    /* Add the main siteadmin menu item */
	$wp_admin_bar->add_menu(array( 'parent' => 'appearance', 'title' => __('MiniMeta Widget','minimeta-widget'), 'href' => admin_url('themes.php').'?page=minimeta-widget'));
}

//add links on plugins page
function minimeta_plugin_links($links, $file) {
	if ($file == dirname(plugin_basename(__FILE__)).'/minimeta-widget.php') {
		$links[] = '<a href="http://wordpress.org/extend/plugins/minimeta-widget/faq/" target="_blank">' . __('FAQ') . '</a>';
		$links[] = '<a href="http://wordpress.org/tags/minimeta-widget/" target="_blank">' . __('Support') . '</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MUKCRHF5U4G2N" target="_blank">' . __('Donate') . '</a>';
	}
	return $links;
}
?>