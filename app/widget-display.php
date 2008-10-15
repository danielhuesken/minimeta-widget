<?PHP

/**
 * MiniMeta Widgets
 *
 * @package MiniMetaWidgetDisplay
 */
 
 
class MiniMetaWidgetDisplay {
	//Function to show widget
	function display($args,$optionsetname='default',$stylenumber='') {
		global $user_identity;
		extract( $args, EXTR_SKIP );
		
		//load options
		$optionset = get_option('minimeta_widget_options');
		if (!isset($optionset[$optionsetname])) {  //find out option exists  and load
			//def options
			$options['in']['linkloginlogout']['active']=true;
			$options['in']['linkseiteadmin']['active']=true;
			$options['in']['linkrss']['active']=true;
			$options['in']['linkcommentrss']['active']=true;
			$options['in']['linkwordpress']['active']=true;
			$options['in']['actionwpmeta']['active']=true;
			$options['out']['linkloginlogout']['active']=true;
			$options['out']['linkregister']['active']=true;
			$options['out']['linkrss']['active']=true;
			$options['out']['linkcommentrss']['active']=true;
			$options['out']['linkwordpress']['active']=true;
			$options['out']['actionwpmeta']['active']=true;
		} else {
			$options=$optionset[$optionsetname];
		}
		
		//find out styles exists  and load
		$styleset = get_option('minimeta_widget_styles'); 
		if (isset($styleset[$stylenumber]) and !empty($options[$stylenumber])) {
			$stylenumber = '';
		} 
		unset($stylesheets);
		if (!empty($stylenumber)) {
			foreach ($styleset[$stylenumber] as $name => $value ) { 
				if (!empty($value) and $name!='stylename') $stylesheets[$name]=' style="'.$value.'"';
			}
		}
		
	
		//Shown part of Widget
		echo $before_widget;
        
        if(is_user_logged_in()) { //When loggt in
            //Display Title
			if ($options['in']['title']['args']['displayidentity'] and !empty($user_identity)) $title=$user_identity;
            if ($options['in']['title']['args']['profilelink'] and current_user_can('read')) {
                echo $before_title ."<a href=\"".admin_url("/profile.php")."\" title=\"".__('Your Profile')."\">". $title ."</a>". $after_title; 
            } else {
				echo $before_title . $title . $after_title; 
            }
			$ulopen=false;
			foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) { 
				if ($partvalues[3] and $options['in'][$partname]['active']) {
					if ($partvalues[5] and !$ulopen) {
						echo '<ul>';
						$ulopen=true;
					}	
					if (!$partvalues[5] and $ulopen) {
						echo '</ul>';
						$ulopen=false;
					}
					$options['in'][$partname]['args']['stylesheets']=$stylesheets;
					call_user_func($partvalues[1], $options['in'][$partname]['args'] );
				}
			}
			if ($ulopen)
				echo '</ul>';			
		} else { //When loggt out
			//Display Title
			echo $before_title . $title . $after_title;
			$ulopen=false;
			foreach (MiniMetaWidgetParts::parts() as $partname => $partvalues) { 
				if ($partvalues[4] and $options['out'][$partname]['active']) {
					if ($partvalues[5] and !$ulopen) {
						echo '<ul>';
						$ulopen=true;
					}	
					if (!$partvalues[5] and $ulopen) {
						echo '</ul>';
						$ulopen=false;
					}
					//call functions to display
					$options['out'][$partname]['args']['stylesheets']=$stylesheets;
					call_user_func($partvalues[1], $options['out'][$partname]['args'] );
				}
			}
			if ($ulopen)
				echo '</ul>';
		}
  
		echo $after_widget;		
	}


	function control($number,$optionsetname,$style) {
		?>
		<label for="minimeta-optionset-<?php echo $number; ?>" title="<?php _e('Select Widget Option Settings','MiniMetaWidget');?>"><?php _e('Widget Option Setting:','MiniMetaWidget');?> 
         <select class="widefat" name="widget-minimeta[<?php echo $number; ?>][optionset]" id="minimeta-optionset-<?php echo $number; ?>">
         <?PHP
            $options_widgets = get_option('minimeta_widget_options');
			$check = empty($optionsetname) ? ' selected=\"selected\"' : '';
            echo "<option value=\"\"".$check.">".__('None','MiniMetaWidget')."</option>";
			foreach ($options_widgets as $name => $values) {
			   $check = $name==$optionsetname ? ' selected=\"selected\"' : '';
               echo "<option value=\"".$name."\"".$check.">".$values['optionname']."</option>";
            }        
         ?>  
         </select></label><br />
		 <label for="minimeta-style-<?php echo $number; ?>" title="<?php _e('Select Widget Style Settings','MiniMetaWidget');?>"><?php _e('Widget Style Setting:','MiniMetaWidget');?> 
		 <select class="widefat" name="widget-minimeta[<?php echo $number; ?>][style]" id="minimeta-style-<?php echo $number; ?>">
         <?PHP
		    $check = empty($style) ? ' selected=\"selected\"' : '';
            echo "<option value=\"\"".$check.">".__('None','MiniMetaWidget')."</option>";
            $style_widgets = get_option('minimeta_widget_styles');
			foreach ($style_widgets as $tabs => $values) {
			   $check = $tabs==$style ? ' selected=\"selected\"' : '';
               echo "<option value=\"".$tabs."\"".$check.">".$values['stylename']."</option>";
            }        
         ?>  
         </select></label>
		<?php
	}
}