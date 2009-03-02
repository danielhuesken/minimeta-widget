<?php

// Form Processing
$mmconfigid=$_REQUEST['mmconfigid'];
$options_widgets = get_option('minimeta_widget_options');

// Add new Config
if (!empty($_POST['addbutton']) and $_REQUEST['subpage']=="") {
	check_admin_referer('MiniMeta-options','wpnoncemm');

	//generate New number
	$newnumber=wp_create_nonce(mt_rand(10, 30));
	while (is_array($options_widgets[$newnumber])) {
		$newnumber=wp_create_nonce(mt_rand(10, 30));
	}
	// def. Opdions
	$options_widgets[$newnumber]['optionname']=htmlentities(stripslashes(__('New Config', 'MiniMetaWidget')));
	$options_widgets[$newnumber]['in'][0]['part']='title';
	$options_widgets[$newnumber]['in'][1]['part']='linkseiteadmin';
	$options_widgets[$newnumber]['in'][2]['part']='linkloginlogout';
	$options_widgets[$newnumber]['in'][3]['part']='linkrss';
	$options_widgets[$newnumber]['in'][4]['part']='linkcommentrss';
	$options_widgets[$newnumber]['in'][5]['part']='linkwordpress';
	$options_widgets[$newnumber]['in'][6]['part']='actionwpmeta';
	$options_widgets[$newnumber]['out'][0]['part']='title';
	$options_widgets[$newnumber]['out'][1]['part']='linkregister';
	$options_widgets[$newnumber]['out'][2]['part']='linkloginlogout';
	$options_widgets[$newnumber]['out'][3]['part']='linkrss';
	$options_widgets[$newnumber]['out'][4]['part']='linkcommentrss';
	$options_widgets[$newnumber]['out'][5]['part']='linkwordpress';
	$options_widgets[$newnumber]['out'][6]['part']='actionwpmeta';
	$options_widgets[$newnumber]['general']['pagesnot']['notselected']=true;

	$mmconfigid=$newnumber;
 
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_options_text = '<font color="green">'.__('MiniMeta Widget Config Created', 'MiniMetaWidget').'</font>';
	} else {
		$minimeta_options_text = '<font color="red">'.__('No MiniMeta Widget Config Created', 'MiniMetaWidget').'</font>';
	}
}

// Copy  Config
if (!empty($_POST['dupbutton']) and !empty($mmconfigid) and $_REQUEST['subpage']=="") {
	check_admin_referer('MiniMeta-options','wpnoncemm');

	//generate New number
	$newnumber=wp_create_nonce(mt_rand(10, 30));
	while (is_array($options_widgets[$newnumber])) {
		$newnumber=wp_create_nonce(mt_rand(10, 30));
	}
	//make config copy
	$options_widgets[$newnumber]=$options_widgets[$mmconfigid];
	$options_widgets[$newnumber]['optionname']=htmlentities(stripslashes(__('Copy of', 'MiniMetaWidget').' '.$options_widgets[$newnumber]['optionname']));
	
	$mmconfigid=$newnumber;
	
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_options_text = '<font color="green">'.__('Copy of MiniMeta Widget Config Created', 'MiniMetaWidget').'</font>';
	} else {
		$minimeta_options_text = '<font color="red">'.__('No Copy of MiniMeta Widget Config Created', 'MiniMetaWidget').'</font>';
	}
}

//Delete Config
if (!empty($_POST['delbutton']) and !empty($mmconfigid) and $_REQUEST['subpage']=="") {
	check_admin_referer('MiniMeta-options','wpnoncemm');
	
	unset($options_widgets[$mmconfigid]);
	
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_options_text = '<font color="green">'.__('MiniMeta Widget Config Deleted', 'MiniMetaWidget').'</font>';
	} else {
		$minimeta_options_text = '<font color="red">'.__('MiniMeta Widget Config Deleted', 'MiniMetaWidget').'</font>';
	}
}

// Update Options
if(!empty($_POST['Submit']) and !empty($mmconfigid) and $_REQUEST['subpage']=="") {
	check_admin_referer('MiniMeta-options','wpnoncemmconf');
	
	//write every options tab to optiones
	if (is_array($_POST['widget-options'][$mmconfigid])) {
		unset($options_widgets[$mmconfigid]);
	    $options_widgets[$mmconfigid]['optionname'] = htmlentities(stripslashes($_POST['widget-options'][$mmconfigid]['optionname']));
		//Save general options
		$options_widgets[$mmconfigid]['general']['style']['ul'] = $_POST['widget-options'][$mmconfigid]['general']['style']['ul'];
		$options_widgets[$mmconfigid]['general']['style']['li'] = $_POST['widget-options'][$mmconfigid]['general']['style']['li'];
		$options_widgets[$mmconfigid]['general']['php']['title'] = $_POST['widget-options'][$mmconfigid]['general']['php']['title'];
		$options_widgets[$mmconfigid]['general']['php']['before_title'] = $_POST['widget-options'][$mmconfigid]['general']['php']['before_title'];
		$options_widgets[$mmconfigid]['general']['php']['after_title'] = $_POST['widget-options'][$mmconfigid]['general']['php']['after_title'];
		$options_widgets[$mmconfigid]['general']['php']['before_widget'] = $_POST['widget-options'][$mmconfigid]['general']['php']['before_widget'];
		$options_widgets[$mmconfigid]['general']['php']['after_widget'] = $_POST['widget-options'][$mmconfigid]['general']['php']['after_widget'];
		$options_widgets[$mmconfigid]['general']['pagesnot']['notselected'] = $_POST['widget-options'][$mmconfigid]['general']['pagesnot']['notselected'];
		if (is_array($_POST['widget-options'][$mmconfigid]['general']['pagesnot']['in'])) {
			foreach ($_POST['widget-options'][$mmconfigid]['general']['pagesnot']['in'] as $page => $pagevalue) {
				$options_widgets[$mmconfigid]['general']['pagesnot']['in'][$page] = isset($pagevalue);
			}
		}
		if (is_array($_POST['widget-options'][$mmconfigid]['general']['pagesnot']['out'])) {
			foreach ($_POST['widget-options'][$mmconfigid]['general']['pagesnot']['out'] as $page => $pagevalue) {
				$options_widgets[$mmconfigid]['general']['pagesnot']['out'][$page] = isset($pagevalue);
			}
		}
		//Save option for in and out
		$ordering=0;
		for ($i=0; $i<=sizeof($_POST['widget-options'][$mmconfigid]['in']);$i++) {
			if(isset($_POST['widget-options'][$mmconfigid]['in'][$i]['active'])) {
				$options_widgets[$mmconfigid]['in'][$ordering]['part']=$_POST['widget-options'][$mmconfigid]['in'][$i]['part'];
				$options_widgets[$mmconfigid]['in'][$ordering]['args']=$_POST['widget-options'][$mmconfigid]['in'][$i]['args'];
				$ordering++;
			}
		}
		$ordering=0;
		for ($i=0; $i<=sizeof($_POST['widget-options'][$mmconfigid]['out']);$i++) {
			if(isset($_POST['widget-options'][$mmconfigid]['out'][$i]['active'])) {
				$options_widgets[$mmconfigid]['out'][$ordering]['part']=$_POST['widget-options'][$mmconfigid]['out'][$i]['part'];
				$options_widgets[$mmconfigid]['out'][$ordering]['args']=$_POST['widget-options'][$mmconfigid]['out'][$i]['args'];
				$ordering++;
			}
		}
	}
	
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_options_text = '<font color="green">'.__('MiniMeta Widget Config Updated', 'MiniMetaWidget').'</font>';
	} else {
		$minimeta_options_text = '<font color="red">'.__('MiniMeta Widget Config Updated', 'MiniMetaWidget').'</font>';
	}
}
?>