<?php
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

//class for Table
include_once(trailingslashit(ABSPATH).'wp-admin/includes/class-wp-list-table.php');

class MiniMeta_configs_Table extends WP_List_Table {
	function __construct() {
		parent::__construct( array(
			'plural' => 'configs',
			'singular' => 'config',
			'ajax' => true
		) );
	}
	
	function ajax_user_can() {
		return current_user_can('switch_themes');
	}	
	
	function prepare_items() {
		$this->items = get_option('minimeta_widget_options');
	}

	function no_items() {
		_e( 'No Widget Configs.','minimeta-widget');
	}
	
	function get_bulk_actions() {
		$actions = array();
		$actions['delete'] = __( 'Delete' );
		return $actions;
	}
	
	function get_columns() {
		$posts_columns = array();
		$posts_columns['cb'] = '<input type="checkbox" />';
		$posts_columns['id'] = __('ID','minimeta-widget');
		$posts_columns['name'] = __('Name','minimeta-widget');
		return $posts_columns;
	}
	
	function display_rows() {
		$style = '';
		foreach ( $this->items as $optionid => $optionvalue) {
			$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
			echo "\n\t", $this->single_row( $optionid, $optionvalue, $style );
		}
	}
	
	function single_row( $optionid, $optionvalue, $style = '' ) {
		list( $columns, $hidden, $sortable ) = $this->get_column_info();
		$r = "<tr id='".$optionid."'$style>";
		foreach ( $columns as $column_name => $column_display_name ) {
			$class = "class=\"$column_name column-$column_name\"";

			$style = '';
			if ( in_array( $column_name, $hidden ) )
				$style = ' style="display:none;"';

			$attributes = "$class$style";
			
			switch($column_name) {
				case 'cb':
					$r .= '<th scope="row" class="check-column"><input type="checkbox" name="mmconfigid[]" value="'. esc_attr($optionid) .'" /></th>';
					break;
				case 'id':
					$r .= "<td $attributes>".$optionid."</td>"; 
					break;
				case 'name':				
					$r .= "<td $attributes><strong><a href=\"".wp_nonce_url(admin_url('themes.php').'?page=minimeta-widget&mmconfigid='.$optionid, 'mmconfig')."\" title=\"".__('Edit:','backwpup').$optionvalue['optionname']."\">".esc_html($optionvalue['optionname'])."</a></strong>";
					$actions = array();
					$actions['edit'] = "<a href=\"" . wp_nonce_url(admin_url('themes.php').'?page=minimeta-widget&mmconfigid='.$optionid, 'mmconfig') . "\">" . __('Edit') . "</a>";
					$actions['copy'] = "<a href=\"" . wp_nonce_url(admin_url('themes.php').'?page=minimeta-widget&action=copy&mmconfigid='.$optionid, 'mmconfig_'.$optionid) . "\">" . __('Copy','minimeta-widget') . "</a>";
					$actions['delete'] = "<a class=\"submitdelete\" href=\"" . wp_nonce_url(admin_url('themes.php').'?page=minimeta-widget&action=delete&mmconfigid[]='.$optionid, 'bulk-configs') . "\" onclick=\"return showNotice.warn();\">" . __('Delete') . "</a>";
					$r .= $this->row_actions($actions);
					$r .= "</td>";
					break;		
			}
		}
		$r .= '</tr>';
		return $r;
	}
}	
	
//Create Table
$minimeta_listtable = new MiniMeta_configs_Table();

//get cuurent action
$doaction = $minimeta_listtable->current_action();	
	
// Copy  Config
if ($doaction=='copy' and !empty($_GET['mmconfigid'])) {
	check_admin_referer('mmconfig_'.$_GET['mmconfigid']);
	$options_widgets = get_option('minimeta_widget_options');
	//generate New number
	$newnumber=wp_create_nonce(mt_rand(10, 30));
	while (isset($options_widgets[$newnumber])) {
		$newnumber=wp_create_nonce(mt_rand(10, 30));
	}
	//make config copy
	$options_widgets[$newnumber]=$options_widgets[$_GET['mmconfigid']];
	$options_widgets[$newnumber]['optionname']=htmlentities(stripslashes(__('Copy of', 'minimeta-widget').' '.$options_widgets[$_GET['mmconfigid']]['optionname']));
	
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_message = '<font color="green">'.sprintf(__('Copy of "%s" Config Created', 'minimeta-widget'),$options_widgets[$_GET['mmconfigid']]['optionname']).'</font>';
	} else {
		$minimeta_message = '<font color="red">'.sprintf(__('Copy of "%s" Config NOT Created', 'minimeta-widget'),$options_widgets[$_GET['mmconfigid']]['optionname']).'</font>';
	}
	
	$_REQUEST['mmconfigid']=$newnumber;
}
//Delete Config
if ($doaction=='delete' and !empty($_GET['mmconfigid'])) {
	check_admin_referer('bulk-configs');
	$options_widgets = get_option('minimeta_widget_options');
	if (is_array($_GET['mmconfigid'])) {
		foreach ($_GET['mmconfigid'] as $mmconfigid) {
			unset($options_widgets[$mmconfigid]);
		}	
	}
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_message = '<font color="green">'.__('Config Deleted', 'minimeta-widget').'</font>';
		$_REQUEST['mmconfigid']="";
	} else {
		$minimeta_message = '<font color="red">'.__('Config NOT Deleted', 'minimeta-widget').'</font>';
	}
}
//New Config
if ($doaction=='new') {
	check_admin_referer('new-config');
	$options_widgets = get_option('minimeta_widget_options');
	//generate New number
	$mmconfigid=wp_create_nonce(mt_rand(10, 30));
	while (isset($options_widgets[$mmconfigid])) {
		$mmconfigid=wp_create_nonce(mt_rand(10, 30));
	}
	// def. Opdions
	$options_widgets[$mmconfigid]['optionname']=htmlentities(stripslashes(__('New', 'minimeta-widget')));
	$options_widgets[$mmconfigid]['in'][0]['part']='title';
	$options_widgets[$mmconfigid]['in'][1]['part']='linkseiteadmin';
	$options_widgets[$mmconfigid]['in'][2]['part']='linkloginlogout';
	$options_widgets[$mmconfigid]['in'][3]['part']='linkrss';
	$options_widgets[$mmconfigid]['in'][4]['part']='linkcommentrss';
	$options_widgets[$mmconfigid]['in'][5]['part']='linkwordpress';
	$options_widgets[$mmconfigid]['in'][6]['part']='actionwpmeta';
	$options_widgets[$mmconfigid]['out'][0]['part']='title';
	$options_widgets[$mmconfigid]['out'][1]['part']='linkregister';
	$options_widgets[$mmconfigid]['out'][2]['part']='linkloginlogout';
	$options_widgets[$mmconfigid]['out'][3]['part']='linkrss';
	$options_widgets[$mmconfigid]['out'][4]['part']='linkcommentrss';
	$options_widgets[$mmconfigid]['out'][5]['part']='linkwordpress';
	$options_widgets[$mmconfigid]['out'][6]['part']='actionwpmeta';
	$options_widgets[$mmconfigid]['general']['pagesnot']['notselected']=true;
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_message = '<font color="green">'.__('New Config created', 'minimeta-widget').'</font>';
		$_REQUEST['mmconfigid']=$mmconfigid;
	} else {
		$minimeta_message = '<font color="red">'.__('Can\'t create new Config', 'minimeta-widget').'</font>';
	}	
}

// Update Options
if(!empty($_POST['Submit']) and !empty($_POST['mmconfigid'])) {
	check_admin_referer('MiniMeta-options');
	$options_widgets = get_option('minimeta_widget_options');

	//write every options tab to optiones
	if (is_array($_POST['widget-options'])) {
		if (is_array($options_widgets[$_POST['mmconfigid']])) //Clean old values
			unset($options_widgets[$_POST['mmconfigid']]);
	    $options_widgets[$_POST['mmconfigid']]['optionname'] = htmlentities(stripslashes($_POST['widget-options']['optionname']));
		//Save Ordering
		$options_widgets[$_POST['mmconfigid']]['order']['in']=str_replace("&in[]=",",",substr($_POST['widget-options']['order']['in'],5));
		$options_widgets[$_POST['mmconfigid']]['order']['out']=str_replace("&out[]=",",",substr($_POST['widget-options']['order']['out'],6));
		//Save general options
		$options_widgets[$_POST['mmconfigid']]['general']['style']['ul'] = $_POST['widget-options']['general']['style']['ul'];
		$options_widgets[$_POST['mmconfigid']]['general']['style']['li'] = $_POST['widget-options']['general']['style']['li'];
		$options_widgets[$_POST['mmconfigid']]['general']['class']['ul'] = $_POST['widget-options']['general']['class']['ul'];
		$options_widgets[$_POST['mmconfigid']]['general']['class']['li'] = $_POST['widget-options']['general']['class']['li'];
		$options_widgets[$_POST['mmconfigid']]['general']['php']['title'] = $_POST['widget-options']['general']['php']['title'];
		$options_widgets[$_POST['mmconfigid']]['general']['php']['before_title'] = $_POST['widget-options']['general']['php']['before_title'];
		$options_widgets[$_POST['mmconfigid']]['general']['php']['after_title'] = $_POST['widget-options']['general']['php']['after_title'];
		$options_widgets[$_POST['mmconfigid']]['general']['php']['before_widget'] = $_POST['widget-options']['general']['php']['before_widget'];
		$options_widgets[$_POST['mmconfigid']]['general']['php']['after_widget'] = $_POST['widget-options']['general']['php']['after_widget'];
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['notselected'] = isset($_POST['widget-options']['general']['pagesnot']['notselected']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['out']['home'] = isset($_POST['widget-options']['general']['pagesnot']['out']['home']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['out']['singlepost'] = isset($_POST['widget-options']['general']['pagesnot']['out']['singlepost']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['out']['search'] = isset($_POST['widget-options']['general']['pagesnot']['out']['search']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['out']['errorpages'] = isset($_POST['widget-options']['general']['pagesnot']['out']['errorpages']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['in']['home'] = isset($_POST['widget-options']['general']['pagesnot']['in']['home']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['in']['singlepost'] = isset($_POST['widget-options']['general']['pagesnot']['in']['singlepost']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['in']['search'] = isset($_POST['widget-options']['general']['pagesnot']['in']['search']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['in']['errorpages'] = isset($_POST['widget-options']['general']['pagesnot']['in']['errorpages']);
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['in']['pages'] = $_POST['widget-options']['general']['pagesnot']['in']['pages'];
		$options_widgets[$_POST['mmconfigid']]['general']['pagesnot']['out']['pages'] = $_POST['widget-options']['general']['pagesnot']['out']['pages'];
		//Save option for in and out and sort
		$ordering=0;
		$sort=split(",",$options_widgets[$_POST['mmconfigid']]['order']['in']);
		for ($i=0; $i<sizeof($sort);$i++) {
			if(isset($_POST['widget-options']['in'][$sort[$i]]['active'])) {
				$options_widgets[$_POST['mmconfigid']]['in'][$ordering]['part']=$_POST['widget-options']['in'][$sort[$i]]['part'];
				if (is_array($_POST['widget-options']['in'][$sort[$i]]['args'])) {
					foreach($_POST['widget-options']['in'][$sort[$i]]['args'] as $argkey => $argvalue) {
						$options_widgets[$_POST['mmconfigid']]['in'][$ordering]['args'][$argkey]=$argvalue;
						if ($argvalue=='1')
							$options_widgets[$_POST['mmconfigid']]['in'][$ordering]['args'][$argkey]=true;
					}
				}
				$ordering++;
			}
		}
		$ordering=0;
		$sort=split(",",$options_widgets[$_POST['mmconfigid']]['order']['out']);
		for ($i=0; $i<sizeof($sort);$i++) {
			if(isset($_POST['widget-options']['out'][$sort[$i]]['active'])) {
				$options_widgets[$_POST['mmconfigid']]['out'][$ordering]['part']=$_POST['widget-options']['out'][$sort[$i]]['part'];
				if (is_array($_POST['widget-options']['out'][$sort[$i]]['args'])) {
					foreach($_POST['widget-options']['out'][$sort[$i]]['args'] as $argkey => $argvalue) {
						$options_widgets[$_POST['mmconfigid']]['out'][$ordering]['args'][$argkey]=$argvalue;
						if ($argvalue=='1')
							$options_widgets[$_POST['mmconfigid']]['out'][$ordering]['args'][$argkey]=true;
					}
				}
				$ordering++;
			}
		}
	}
	if (update_option('minimeta_widget_options', $options_widgets)) {
		$minimeta_message = '<font color="green">'.sprintf(__('Config "%s" Updated', 'minimeta-widget'),$options_widgets[$_REQUEST['mmconfigid']]['optionname']).'</font>';
	} else {
		$minimeta_message = '<font color="red">'.sprintf(__('Config "%s" NOT Updated', 'minimeta-widget'),$options_widgets[$_REQUEST['mmconfigid']]['optionname']).'</font>';
	}
}

$minimeta_listtable->prepare_items();
add_contextual_help($current_screen,minimeta_show_help());
?>