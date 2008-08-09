<?PHP

class MiniMetaFunctions {

//JS Admin Header for All/None Selection
function admin_head() { 
    ?>
    <script type="text/javascript">
	 function selectAll_widget_minimeta(selectBox,selectAll) {
	  for (var i = 0; i < selectBox.options.length; i++) selectBox.options[i].selected = selectAll;
	 }
	</script>    
    <?PHP
}
    
//WP-Head hooks high Priority
function head_login() {
    if (!is_user_logged_in()) {   //copy action login_head to wp-head if login form enabeld for plugin hooks 
        if (K2_LOAD_SBM) {
            do_action('login_head'); //do action from login had
        } else {
            $options = get_option('widget_minimeta');
            //find out is a ligon form in any MiniMeta Widegt activatet
            foreach ( (array) $options as $widget_number => $widget_minmeta ) {
                if($widget_minmeta['loginform']) 
                    do_action('login_head'); //do action from login had
            }
        }
    }        
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
 global $menu,$submenu,$pagenow;
 if (!(current_user_can(10) and ("plugins.php"==$pagenow or "widgets.php"==$pagenow or (K2_LOAD_SBM and "themes.php"==$pagenow)))) 
    return;   
 //let orginal Variables unchanged
 $tempmenu=$menu;
 $tempsubmenu=$submenu;
 //scan the menu
 foreach ( $tempmenu as $key => $item ) {
    if ($item[2]=="edit-comments.php") //Overwrite for Comments menu without number since wp 2.5
        $item[0] = __('Comments');
	if ($item[2]=="plugins.php") //Overwrite for Plugins menu without number since wp 2.6
        $item[0] = __('Plugins');
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
 update_option('widget_minimeta_adminlinks', $adminlinks);
}

	function get_widget_options() {
		$i=0;
		$option[$i]['name']='loginlink'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='loginform'; $option[$i]['defvalue']=false; $i++;
		$option[$i]['name']='logout'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='registerlink'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='testcookie'; $option[$i]['defvalue']=false; $i++;
		$option[$i]['name']='redirect'; $option[$i]['defvalue']=false; $i++;
		$option[$i]['name']='seiteadmin'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='rememberme'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='rsslink'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='rsscommentlink'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='wordpresslink'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='lostpwlink'; $option[$i]['defvalue']=false; $i++;
		$option[$i]['name']='profilelink'; $option[$i]['defvalue']=false; $i++;
		$option[$i]['name']='showwpmeta'; $option[$i]['defvalue']=true; $i++;
		$option[$i]['name']='displayidentity'; $option[$i]['defvalue']=false; $i++;
		$option[$i]['name']='useselectbox'; $option[$i]['defvalue']=false; $i++;
		$option[$i]['name']='notopics'; $option[$i]['defvalue']=false; $i++; 
		return $option;
	}


}

?>