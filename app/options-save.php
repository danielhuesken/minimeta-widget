<?php

// Form Processing
// Update Options
if(!empty($_POST['Submit']) and current_user_can('switch_themes')) {
	check_admin_referer('MiniMeta-options','wpnoncemm');
	
	$update_views_queries = array();
	$update_views_text = array();
	
	//Option to delete
	$delnumber=$_POST['widget-options-SidebarDelete'];
	//write every options tab to optiones
	foreach ((array)$_POST['widget-options'] as $optionname => $optionvalues) {
	  if ($delnumber!=$optionname and !empty($optionvalues['optionname'])){ //Change only not deleted 
	    $options_widgets[$optionname]['optionname'] = htmlentities(stripslashes($optionvalues['optionname']));
		//Save general options
		$options_widgets[$optionname]['general']['style']['ul'] = $optionvalues['general']['style']['ul'];
		$options_widgets[$optionname]['general']['style']['li'] = $optionvalues['general']['style']['li'];
		$options_widgets[$optionname]['general']['php']['title'] = $optionvalues['general']['php']['title'];
		$options_widgets[$optionname]['general']['php']['before_title'] = $optionvalues['general']['php']['before_title'];
		$options_widgets[$optionname]['general']['php']['after_title'] = $optionvalues['general']['php']['after_title'];
		$options_widgets[$optionname]['general']['php']['before_widget'] = $optionvalues['general']['php']['before_widget'];
		$options_widgets[$optionname]['general']['php']['after_widget'] = $optionvalues['general']['php']['after_widget'];
		$options_widgets[$optionname]['general']['pagesnot']['notselected'] = $optionvalues['general']['pagesnot']['notselected'];
		foreach ((array)$optionvalues['general']['pagesnot']['in'] as $page => $pagevalue) {
			$options_widgets[$optionname]['general']['pagesnot']['in'][$page] = isset($pagevalue);
		}
		foreach ((array)$optionvalues['general']['pagesnot']['out'] as $page => $pagevalue) {
			$options_widgets[$optionname]['general']['pagesnot']['out'][$page] = isset($pagevalue);
		}
		//Save option for in and out
		$ordering=0;
		for ($i=0; $i<=sizeof($optionvalues['in']);$i++) {
			if(isset($optionvalues['in'][$i]['active'])) {
				$options_widgets[$optionname]['in'][$ordering]['part']=$optionvalues['in'][$i]['part'];
				$options_widgets[$optionname]['in'][$ordering]['args']=$optionvalues['in'][$i]['args'];
				$ordering++;
			}
		}
		$ordering=0;
		for ($i=0; $i<=sizeof($optionvalues['out']);$i++) {
			if(isset($optionvalues['out'][$i]['active'])) {
				$options_widgets[$optionname]['out'][$ordering]['part']=$optionvalues['out'][$i]['part'];
				$options_widgets[$optionname]['out'][$ordering]['args']=$optionvalues['out'][$i]['args'];
				$ordering++;
			}
		}
	  }
	}
	
	//For new Sidebar Widget	
	if (!empty($_POST['widget-options-SidebarNew'])) {
	    $newnumber=wp_create_nonce($_POST['widget-options-SidebarNew']);
		$options_widgets[$newnumber]['optionname']=htmlentities(stripslashes($_POST['widget-options-SidebarNew']));
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
	}
	
	$update_views_queries[] = update_option('minimeta_widget_options', $options_widgets);
	$update_views_text[] = __('MiniMeta Widget Options', 'MiniMetaWidget');

	
	$i=0;
	$minimeta_options_text = '';
	foreach($update_views_queries as $update_views_query) {
		if($update_views_query) {
			$minimeta_options_text .= '<font color="green">'.$update_views_text[$i].' '.__('Updated', 'MiniMetaWidget').'</font><br />';
		}
		$i++;
	}
	if(empty($minimeta_options_text)) {
		$minimeta_options_text = '<font color="red">'.__('No MiniMeta Widget Option Updated', 'MiniMetaWidget').'</font>';
	}
}
?>