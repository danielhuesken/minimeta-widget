		<p><label for="minimeta-optionset-<?php echo $number; ?>" title="<?php _e('Select Widget Option Settings','MiniMetaWidget');?>"><?php _e('Widget Option Settings:','MiniMetaWidget');?> 
         <select class="widefat" name="widget-minimeta[<?php echo $number; ?>][optionset]" id="minimeta-optionset-<?php echo $number; ?>">
         <?PHP
            $options_widgets = get_option('minimeta_widget_options');
			foreach ($options_widgets as $tabs => $values) {
               echo "<option value=\"".$tabs."\"".selected($tabs,$optionset).">".$values['optionname']."</option>";
            }        
         ?>  
         </select></label><p>
