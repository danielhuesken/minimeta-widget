<?PHP

/**
 * MiniMeta Widgets
 *
 * @package MiniMetaWidgetDisplay
 */
 
 
class MiniMetaWidgetDisplay {
	//Function to show widget
	function display($optionsetname='',$args) {
		global $post,$ulopen,$stylegeneralul,$classgeneralul;
		if (is_array($args))
			extract( $args, EXTR_SKIP );

		//Overwrite vars if Seidbar Widget
		if ($type=="PHP") {
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
			$options['order']['in']='0,1,2,3,4,5,6';
			$options['order']['out']='0,1,2,3,4,5,6';
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
				if ($options['in'][$i]['part']=='title') {
					$options['in'][$i]['args']['title']=$title;
					$options['in'][$i]['args']['before_title']=$before_title;
					$options['in'][$i]['args']['after_title']=$after_title;				
				}
			}
			for ($i=0;$i<=sizeof($options['out']);$i++) {
				if ($options['out'][$i]['part']=='title') {
					$options['out'][$i]['args']['title']=$title;
					$options['out'][$i]['args']['before_title']=$before_title;
					$options['out'][$i]['args']['after_title']=$after_title;				
				}
			}
		}
		
		//Overwrite vars if Seidbar Widget
		if ($type=="PHP") {
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
			if (is_home() and $options['general']['pagesnot']['in']['home']) $diplay=true;
			if (is_single() and $options['general']['pagesnot']['in']['singlepost']) $diplay=true;
			if (is_search() and $options['general']['pagesnot']['in']['search']) $diplay=true;
			if (is_404() and $options['general']['pagesnot']['in']['errorpages']) $diplay=true;
			if (is_page($post->ID) and $options['general']['pagesnot']['in'][$post->ID]) $diplay=true;
			if ($diplay==false and !$options['general']['pagesnot']['notselected']) return;
			if ($diplay==true and $options['general']['pagesnot']['notselected']) return;
		} else {
			if (sizeof($options['out'])<1) return; //Disolay widget only if parts are active
			$diplay=false;
			if (is_home() and $options['general']['pagesnot']['out']['home']) $diplay=true;
			if (is_single() and $options['general']['pagesnot']['out']['singlepost']) $diplay=true;
			if (is_search() and $options['general']['pagesnot']['out']['search']) $diplay=true;
			if (is_404() and $options['general']['pagesnot']['out']['errorpages']) $diplay=true;
			if (is_page($post->ID) and $options['general']['pagesnot']['out'][$post->ID]) $diplay=true;
			if ($diplay==false and !$options['general']['pagesnot']['notselected']) return;
			if ($diplay==true and $options['general']['pagesnot']['notselected']) return;
		}
		
		$parts=MiniMetaWidgetParts::parts();
		$stylegeneralul=$options['general']['php']['style']['ul'];
		$classgeneralul=$options['general']['php']['class']['ul'];
		
		//Shown part of Widget
		echo $before_widget;
		
		$ulopen=false;
        if(is_user_logged_in()) { //When loggt in
			if (empty($options['order']['in'])) { //for Version bevor 4.2.0
				for ($i=0;$i<=sizeof($options['in']);$i++) {
					$options['order']['in'].=$i.',';
				}
				$options['order']['in']=substr($options['order']['in'],0,-1);
			}
			$order=explode(",",$options['order']['in']);
			for ($i=0;$i<=sizeof($order);$i++) {
				if ($parts[$options['in'][$order[$i]]['part']][3]) {
					$options['in'][$order[$i]]['args']['stylegeneralli']=$options['general']['php']['style']['li'];
					$options['in'][$order[$i]]['args']['classgeneralli']=$options['general']['php']['class']['li'];
					call_user_func($parts[$options['in'][$order[$i]]['part']][1], $options['in'][$order[$i]]['args'] );
				}
			}		
		} else { //When loggt out
			if (empty($options['order']['out'])) { //for Version bevor 4.2.0
				for ($i=0;$i<=sizeof($options['out']);$i++) {
					$options['order']['out'].=$i.',';
				}
				$options['order']['out']=substr($options['order']['out'],0,-1);
			}
			$order=explode(",",$options['order']['out']);
			for ($i=0;$i<=sizeof($order);$i++) { 
				if ($parts[$options['out'][$order[$i]]['part']][4]) {
					$options['out'][$order[$i]]['args']['stylegeneralli']=$options['general']['php']['style']['li'];
					$options['out'][$order[$i]]['args']['classgeneralli']=$options['general']['php']['class']['li'];
					call_user_func($parts[$options['out'][$order[$i]]['part']][1], $options['out'][$order[$i]]['args'] );
				}
			}
		}
		if ($ulopen)
			echo '</ul>';
			
		echo $after_widget;		
	}
}