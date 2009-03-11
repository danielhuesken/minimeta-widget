<?php

// Form Processing
$mmconfigid=$_REQUEST['mmconfigid'];
$options_widgets = get_option('minimeta_widget_options');

if (!empty($_POST['gobutton'])) {
	check_admin_referer('MiniMeta-options','wpnoncemm');
	$mmconfigid=$_POST['selectmmconfigid'];
}

// Add new Config
if (!empty($_POST['addbutton']) and $_REQUEST['subpage']=="") {
	check_admin_referer('MiniMeta-options','wpnoncemm');

	//generate New number
	$newnumber=wp_create_nonce(mt_rand(10, 30));
	while (is_array($options_widgets[$newnumber])) {
		$newnumber=wp_create_nonce(mt_rand(10, 30));
	}
	// def. Opdions
	$options_widgets[$newnumber]['optionname']=htmlentities(stripslashes(__('New', 'MiniMetaWidget')));
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

	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_options_text = '<font color="green">'.__('New Config Created', 'MiniMetaWidget').'</font>';
	} else {
		$minimeta_options_text = '<font color="red">'.__('New Config NOT Created', 'MiniMetaWidget').'</font>';
	}
	
	$mmconfigid=$newnumber;
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
	$options_widgets[$newnumber]['optionname']=htmlentities(stripslashes(__('Copy of', 'MiniMetaWidget').' '.$options_widgets[$mmconfigid]['optionname']));
	
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_options_text = '<font color="green">'.sprintf(__('Copy of "%s" Config Created', 'MiniMetaWidget'),$options_widgets[$mmconfigid]['optionname']).'</font>';
	} else {
		$minimeta_options_text = '<font color="red">'.sprintf(__('Copy of "%s" Config NOT Created', 'MiniMetaWidget'),$options_widgets[$mmconfigid]['optionname']).'</font>';
	}
	
	$mmconfigid=$newnumber;
}

//Delete Config
if (!empty($_POST['delbutton']) and !empty($mmconfigid) and $_REQUEST['subpage']=="") {
	check_admin_referer('MiniMeta-options','wpnoncemm');
	
	$name=$options_widgets[$mmconfigid]['optionname']; 
	unset($options_widgets[$mmconfigid]);
	
	$i=0; //test if config exists
	foreach ($options_widgets as $optionid) {
		$i++;
	}
	
	if ($i==0) { //only Update if a config exists
		if (delete_option('minimeta_widget_options')) {
			add_option('minimeta_widget_options');
			$minimeta_options_text = '<font color="green">'.sprintf(__('Config "%s" Deleted', 'MiniMetaWidget'),$name).'</font>';
			$mmconfigid="";
		} else {
			$minimeta_options_text = '<font color="red">'.sprintf(__('Config "%s" NOT Deleted', 'MiniMetaWidget'),$name).'</font>';
		}
	} else {
		if (update_option('minimeta_widget_options', $options_widgets)) {
			$minimeta_options_text = '<font color="green">'.sprintf(__('Config "%s" Deleted', 'MiniMetaWidget'),$name).'</font>';
			$mmconfigid="";
		} else {
			$minimeta_options_text = '<font color="red">'.sprintf(__('Config "%s" NOT Deleted', 'MiniMetaWidget'),$name).'</font>';
		}
	}
}

// Update Options
if(!empty($_POST['Submit']) and !empty($mmconfigid) and $_REQUEST['subpage']=="") {
	check_admin_referer('MiniMeta-options','wpnoncemm');
	
	//write every options tab to optiones
	if (is_array($_POST['widget-options'][$mmconfigid])) {
		unset($options_widgets[$mmconfigid]);
	    $options_widgets[$mmconfigid]['optionname'] = htmlentities(stripslashes($_POST['widget-options'][$mmconfigid]['optionname']));
		//Save Ordering
		$options_widgets[$mmconfigid]['order']['in']=str_replace("&in[]=",",",substr($_POST['widget-options'][$mmconfigid]['order']['in'],5));
		$options_widgets[$mmconfigid]['order']['out']=str_replace("&out[]=",",",substr($_POST['widget-options'][$mmconfigid]['order']['out'],6));
		//Save general options
		$options_widgets[$mmconfigid]['general']['style']['ul'] = $_POST['widget-options'][$mmconfigid]['general']['style']['ul'];
		$options_widgets[$mmconfigid]['general']['style']['li'] = $_POST['widget-options'][$mmconfigid]['general']['style']['li'];
		$options_widgets[$mmconfigid]['general']['class']['ul'] = $_POST['widget-options'][$mmconfigid]['general']['class']['ul'];
		$options_widgets[$mmconfigid]['general']['class']['li'] = $_POST['widget-options'][$mmconfigid]['general']['class']['li'];
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
		//Save option for in and out and sort
		$ordering=0;
		$sort=split(",",str_replace("&in[]=",",",substr($_POST['widget-options'][$mmconfigid]['order']['in'],5)));
		for ($i=0; $i<=sizeof($sort);$i++) {
			if(isset($_POST['widget-options'][$mmconfigid]['in'][$sort[$i]]['active'])) {
				$options_widgets[$mmconfigid]['in'][$ordering]['part']=$_POST['widget-options'][$mmconfigid]['in'][$sort[$i]]['part'];
				$options_widgets[$mmconfigid]['in'][$ordering]['args']=$_POST['widget-options'][$mmconfigid]['in'][$sort[$i]]['args'];
				$ordering++;
			}
		}
		$ordering=0;
		$sort=split(",",str_replace("&out[]=",",",substr($_POST['widget-options'][$mmconfigid]['order']['out'],6)));
		for ($i=0; $i<=sizeof($sort);$i++) {
			if(isset($_POST['widget-options'][$mmconfigid]['out'][$sort[$i]]['active'])) {
				$options_widgets[$mmconfigid]['out'][$ordering]['part']=$_POST['widget-options'][$mmconfigid]['out'][$sort[$i]]['part'];
				$options_widgets[$mmconfigid]['out'][$ordering]['args']=$_POST['widget-options'][$mmconfigid]['out'][$sort[$i]]['args'];
				$ordering++;
			}
		}
	}
	
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_options_text = '<font color="green">'.sprintf(__('Config "%s" Updated', 'MiniMetaWidget'),$options_widgets[$mmconfigid]['optionname']).'</font>';

	} else {
		$minimeta_options_text = '<font color="red">'.sprintf(__('Config "%s" NOT Updated', 'MiniMetaWidget'),$options_widgets[$mmconfigid]['optionname']).'</font>';
	}
}
?>