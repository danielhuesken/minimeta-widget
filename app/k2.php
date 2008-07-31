<?PHP

/**
 * MiniMeta K2
 *
 * @package MiniMetaK2
 */
 
 
class MiniMetaK2 {
	// This registers our widget and  widget control for K2 SBM 
	function register() {  
      register_sidebar_module('MiniMeta Widget', array('MiniMetaK2', 'display'));
      register_sidebar_module_control('MiniMeta Widget', array('MiniMetaK2', 'control'));
	}

function control() {
    $number=1; //SBM dont need numbers set it to 1
    $options = sbm_get_option('widget_minimeta'); //load Options
    if ( $_POST['widget-minimeta'][$number]) {
		$options['loginlink'] = isset($_POST['widget-minimeta'][$number]['loginlink']);
		$options['loginform'] = isset($_POST['widget-minimeta'][$number]['loginform']);
        $options['logout'] = isset($_POST['widget-minimeta'][$number]['logout']);
        $options['registerlink'] = isset($_POST['widget-minimeta'][$number]['registerlink']);
        $options['testcookie'] = isset($_POST['widget-minimeta'][$number]['testcookie']);
        $options['redirect'] = isset($_POST['widget-minimeta'][$number]['redirect']);
        $options['seiteadmin'] = isset($_POST['widget-minimeta'][$number]['seiteadmin']);
        $options['rememberme'] = isset($_POST['widget-minimeta'][$number]['rememberme']);
		$options['rsslink'] = isset($_POST['widget-minimeta'][$number]['rsslink']);
		$options['rsscommentlink'] = isset($_POST['widget-minimeta'][$number]['rsscommentlink']);
		$options['wordpresslink'] = isset($_POST['widget-minimeta'][$number]['wordpresslink']);
		$options['lostpwlink'] = isset($_POST['widget-minimeta'][$number]['lostpwlink']);
		$options['profilelink'] = isset($_POST['widget-minimeta'][$number]['profilelink']);
        $options['showwpmeta'] = isset($_POST['widget-minimeta'][$number]['showwpmeta']);
        $options['displayidentity'] = isset($_POST['widget-minimeta'][$number]['displayidentity']);
        $options['useselectbox'] = isset($_POST['widget-minimeta'][$number]['useselectbox']);          
        $options['notopics'] = isset($_POST['widget-minimeta'][$number]['notopics']); 
        unset($options['adminlinks']);
        for ($i=0;$i<sizeof($_POST['widget-minimeta'][$number]['adminlinks']);$i++) {
            $options['adminlinks'][$i] = wp_specialchars($_POST['widget-minimeta'][$number]['adminlinks'][$i]);
        }
        sbm_update_option('widget_minimeta', $options); //save Options
    } 
    //make settings
    if (!isset($options['loginlink'])) {
        $loginlink='checked="checked"';
        $loginform='';
        $logout='checked="checked"';
        $registerlink='checked="checked"';
        $testcookie='';
        $redirect='';
        $seiteadmin='checked="checked"';
        $rememberme='checked="checked"';
        $rsslink='checked="checked"';
        $rsscommentlink='checked="checked"';
        $wordpresslink='checked="checked"';
        $lostpwlink='';
        $profilelink='';
        $showwpmeta='checked="checked"';
        $displayidentity='';
        $useselectbox='';
        $notopics='';    
    } else {
		$loginform = $options['loginform'] ? 'checked="checked"' : '';
		$loginlink = $options['loginlink'] ? 'checked="checked"' : '';
		$logout = $options['logout'] ? 'checked="checked"' : '';
        $registerlink = $options['registerlink'] ? 'checked="checked"' : '';
        $testcookie = $options['testcookie'] ? 'checked="checked"' : '';
        $redirect = $options['redirect'] ? 'checked="checked"' : '';
        $seiteadmin = $options['seiteadmin'] ? 'checked="checked"' : '';
		$rememberme = $options['rememberme'] ? 'checked="checked"' : '';
		$rsslink = $options['rsslink'] ? 'checked="checked"' : '';
		$rsscommentlink = $options['rsscommentlink'] ? 'checked="checked"' : '';
		$wordpresslink = $options['wordpresslink'] ? 'checked="checked"' : '';
		$lostpwlink = $options['lostpwlink'] ? 'checked="checked"' : '';
		$profilelink= $options['profilelink'] ? 'checked="checked"' : '';
        $showwpmeta = $options['showwpmeta'] ? 'checked="checked"' : '';
        $displayidentity = $options['displayidentity'] ? 'checked="checked"' : '';
        $useselectbox = $options['useselectbox'] ? 'checked="checked"' : '';
        $notopics = $options['notopics'] ? 'checked="checked"' : '';
        $adminlinksset=$options['adminlinks'];
    }

	//displaying options
	include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/display/widgetcontrol.php'); 
}

//Display Widget 
function display($args,$widget_args = 1) {
    global $user_identity;	
    extract( $args, EXTR_SKIP );
    
    //load options
    $options = sbm_get_option('widget_minimeta');
    //title compatibility for K2SBM
    $options['title']=$title;
     
	include(WP_PLUGIN_DIR.'/'.WP_MINMETA_PLUGIN_DIR.'/display/widget.php');
}


}



?>