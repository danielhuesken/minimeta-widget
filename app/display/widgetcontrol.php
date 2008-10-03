		 <p><label for="minimeta-optionset-<?php echo $number; ?>" title="<?php _e('Select Widget Option Settings','MiniMetaWidget');?>"><?php _e('Widget Option Setting:','MiniMetaWidget');?> 
         <select class="widefat" name="widget-minimeta[<?php echo $number; ?>][optionset]" id="minimeta-optionset-<?php echo $number; ?>">
         <?PHP
            $options_widgets = get_option('minimeta_widget_options');
			foreach ($options_widgets as $tabs => $values) {
			   $check = $tabs==$optionset ? ' selected=\"selected\"' : '';
               echo "<option value=\"".$tabs."\"".$check.">".$values['optionname']."</option>";
            }        
         ?>  
         </select></label><p>
		 <p><label for="minimeta-style-<?php echo $number; ?>" title="<?php _e('Select Widget Style Settings','MiniMetaWidget');?>"><?php _e('Widget Style Setting:','MiniMetaWidget');?> 
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
         </select></label><p>
