<?PHP
// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

class MiniMetaWidgetParts {
	private $options=array();
	private $parts=array();
	private $inorout="out";
	private $fuctionplace=4;
	private $ulopen=false;
	private $styleclassgeneralul='';
	private $styleclassgeneralli='';



	public function __construct($options,$login,$diplayoptions=false) {
		//set option
		$this->options=$options;
		//$this->parts['name']=array('name','function to display','function to control','logtin','logtout')
		$this->parts['gravatar']=array(__('Gravatar'),array($this,'gravatar_display'),array($this,'gravatar_options'),true,false);
		$this->parts['title']=array(__('Title'),array($this,'title_display'),array($this,'title_options'),true,true);
		$this->parts['gravatar']=array(__('Gravatar'),array($this,'gravatar_display'),array($this,'gravatar_options'),true,false);
		$this->parts['loginform']=array(__('Login Form','minimeta-widget'),array($this,'loginform_display'),array($this,'loginform_options'),false,true);
		$this->parts['linkseiteadmin']=array(__('Link:','minimeta-widget').' '.__('Site Admin'),array($this,'seiteadmin_display'),array($this,'seiteadmin_options'),true,false);
		$this->parts['linkregister']=array(__('Link:','minimeta-widget').' '.__('Register'),array($this,'linkregister_display'),array($this,'linkregister_options'),false,true);
		$this->parts['linkloginlogout']=array(__('Link: Login/Logout','minimeta-widget'),array($this,'linkloginout_display'),array($this,'linkloginout_options'),true,true);
		$this->parts['adminlinks']=array(__('Adminlinks as Links','minimeta-widget'),array($this,'adminlinks_display'),array($this,'adminlinks_options'),true,false);
		$this->parts['adminselect']=array(__('Adminlinks as Selectbox','minimeta-widget'),array($this,'adminselect_display'),array($this,'adminselect_options'),true,false);
		$this->parts['linklostpw']=array(__('Link:','minimeta-widget').' '.__('Lost your password?'),array($this,'linklostpw_display'),array($this,'linklostpw_options'),false,true);
		$this->parts['bookmarkscat']=array(__('Blog Links by category','minimeta-widget'),array($this,'bookmarkscat_display'),array($this,'bookmarkscat_options'),true,true);
		$this->parts['bookmarks']=array(__('Blog Links','minimeta-widget'),array($this,'bookmarks_display'),array($this,'bookmarks_options'),true,true);
		$this->parts['linkrss']=array(__('Link:','minimeta-widget').' '.__('Entries <abbr title="Really Simple Syndication">RSS</abbr>'),array($this,'linkrss_display'),array($this,'linkrss_options'),true,true);
		$this->parts['linkcommentrss']=array(__('Link:','minimeta-widget').' '.__('Comments <abbr title="Really Simple Syndication">RSS</abbr>'),array($this,'linkcommentrss_display'),array($this,'linkcommentrss_options'),true,true);
		$this->parts['linkwordpress']=array(__('Link:','minimeta-widget').' WordPress.org',array($this,'linkwordpress_display'),array($this,'linkwordpress_options'),true,true);
		$this->parts['actionwpmeta']=array(__('Do Action:','minimeta-widget').' wp_meta',array($this,'actionwpmeta_display'),'',true,true);		
		//if loggt in
		if($login) { 
			$this->inorout="in";
			$this->fuctionplace=3;
		} else {
			$this->inorout="out";
			$this->fuctionplace=4;
		}
		//display parts
		if (!$diplayoptions) {
			//set style and class for ul li
			if (!isset($options['general']['php']['style']['ul']))
				$options['general']['php']['style']['ul']='';
			if (!isset($options['general']['php']['class']['ul']))
				$options['general']['php']['class']['ul']='';
			$this->styleclassgeneralul=$this->_styleclass($options['general']['php']['style']['ul'],$options['general']['php']['class']['ul']);
			if (!isset($options['general']['php']['style']['li']))
				$options['general']['php']['style']['li']='';
			if (!isset($options['general']['php']['class']['li']))
				$options['general']['php']['class']['li']='';
			$this->styleclassgeneralli=$this->_styleclass($options['general']['php']['style']['li'],$options['general']['php']['class']['li']);
				
			foreach ($this->options[$this->inorout] as $partsettings) {
				if (empty($partsettings['args']))
					$partsettings['args']=array();
				if ($this->parts[$partsettings['part']][$this->fuctionplace]) 
					call_user_func($this->parts[$partsettings['part']][1], $partsettings['args']);				
			}
			
			if ($this->ulopen)
				echo '</ul>';	

		} else {  // Display options
			//make sorting list
			for ($i=0;$i<sizeof($this->options[$this->inorout]);$i++) {
				$orderparts[]=$this->options[$this->inorout][$i]['part'];
			}	
			foreach ($this->parts as $partname => $partvalues) {
					if ($partvalues[$this->fuctionplace] and !in_array($partname,$orderparts)) 
						$orderparts[]=$partname;
			}
			?>
			<div class="widget-log<?php echo $this->inorout; ?>">
			<h4 style="text-align:center;"><?php echo str_replace('%s',$this->inorout,__('Show when Logged %s:','minimeta-widget')); ?></h4>
			<div class="widget-log<?php echo $this->inorout; ?>-list" id="widget-log<?php echo $this->inorout; ?>-list">
			<?PHP  	
			for ($orderingid=0;$orderingid<sizeof($orderparts);$orderingid++) { 
				if (!isset($this->options[$this->inorout][$orderingid]['part']))
					$this->options[$this->inorout][$orderingid]['part']='';
				?>
				<div class="widget-log<?php echo $this->inorout; ?>-item if-js-closed" id="<?php echo $this->inorout; ?>_<?php echo $orderingid; ?>">
					<h4 class="widget-log<?php echo $this->inorout; ?>-title"><span><input class="checkbox-active" type="checkbox" <?php checked($this->options[$this->inorout][$orderingid]['part'],$orderparts[$orderingid]); ?> value="1" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $orderingid; ?>][active]" /> <?php echo $this->parts[$orderparts[$orderingid]][0]; ?></span><br class="clear" /></h4>
					<input type="hidden"  name="widget-options[<?php echo $this->inorout; ?>][<?php echo $orderingid; ?>][part]" value="<?php echo $orderparts[$orderingid]; ?>" />
					<?PHP if (!empty($this->parts[$orderparts[$orderingid]][2])) {?>
					<div class="widget-log<?php echo $this->inorout; ?>-control">
						<?php
						$options=array();
						if (!empty($this->options[$this->inorout][$orderingid]['args']))
							$options=$this->options[$this->inorout][$orderingid]['args'];
						$options['ordering']=$orderingid;
						call_user_func($this->parts[$orderparts[$orderingid]][2], $options);
						?>
					</div>
					<?PHP } ?>
				</div>
		<?PHP	} ?>
			</div>
			</div>
			<?PHP
		}
	}
	
	//help Functions
	private function _styleclass($style="",$class="") {
		$style=!empty($style)?' style="'.$style.'"':'';
		$class=!empty($class)?' class="'.$class.'"':'';
		return $style.$class;
	}
	
    private function _ulopenclose($useul=true) {
		global $stylegeneralul,$classgeneralul;
		if ($useul and !$this->ulopen) {
			echo '<ul'.$this->styleclassgeneralul.'>';
			$this->ulopen=true;
		}	
		if (!$useul and $this->ulopen) {
			echo '</ul>';
			$this->ulopen=false;
		}	
	}

	//Title
	private function title_display($args) {
		global $user_identity;
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (!isset($displayidentity))
			$displayidentity=false;
		if (!isset($profilelink))
			$profilelink=false;		
		
		$this->_ulopenclose(false);
		if($this->inorout=="in") {
			$titletext=$title;
			if ($displayidentity and !empty($user_identity)) {
				$titletext=$user_identity;
				if (!empty($bevore))
					$titletext= htmlentities(stripslashes($bevore)).' '.$titletext;
				if (!empty($after))
					$titletext.=' '. htmlentities(stripslashes($after));
			}	
			if ($profilelink and current_user_can('read')) {
                echo $before_title ."<a href=\"".admin_url("/profile.php")."\" title=\"".__('Your Profile')."\">".$titletext."</a>". $after_title; 
            } else {
				echo $before_title . $titletext . $after_title; 
            }
		} else {
			echo $before_title . $title . $after_title;
		}
	}
	
	public function title_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if ($this->inorout=='in') {
			?>
			<input class="checkbox" value="1" type="checkbox" <?php checked($displayidentity,true); ?> id="minimeta-displayidentity-<?php echo $optionname; ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][displayidentity]" />&nbsp;<?php _e('Display user Identity as title','minimeta-widget');?><br />
			<?php _e('Text bevore user Identity','minimeta-widget');?>&nbsp;<input class="textinput" value="<?php echo htmlentities(stripslashes($bevore)); ?>" type="text" id="minimeta-bevortitletext-<?php echo $optionname; ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][bevore]" /><br />
			<?php _e('Text after user Identity','minimeta-widget');?>&nbsp;<input class="textinput" value="<?php echo htmlentities(stripslashes($after)); ?>" type="text" id="minimeta-bevortitletext-<?php echo $optionname; ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][after]" /><br />
			<input class="checkbox" value="1" type="checkbox" <?php checked($profilelink,true); ?> id="minimeta-profilelink-<?php echo $optionname; ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][profilelink]" />&nbsp;<?php _e('Link to Your Profile in title','minimeta-widget');?><br />	
			<?PHP
		}
	}

	//Gravatar
	private function gravatar_display($args) {
		global $user_ID;
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$this->_ulopenclose(false);
		if ($size>512) $size=512;
		echo "<div".$this->_styleclass($stylediv,$classdiv).">";
		echo get_avatar($user_ID, $size);
		echo "</div>";
	}
	
	public function gravatar_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if ($size>512) $size=512;
		if (empty($size)) $size=70;
		?>
			<?php _e('Gravatar Size:','minimeta-widget');?> <input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($size)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][size]" /><br />
			<hr />
		<?php 
		if (!isset($stylediv)) $stylediv='text-align:center;'; //def. Css
		 _e('Stylesheet:','minimeta-widget');?><br /> 
		&lt;div
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylediv)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylediv]" />&quot; 
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classdiv)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classdiv]" />&quot;
		&gt;<br />
		<?PHP
	}
	
	//Loginform
	private function loginform_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if($ulli) {
			$this->_ulopenclose(true);
			echo "<li".$this->styleclassgeneralli.">";
		} else {
			$this->_ulopenclose(false);
		}
		?>
				<form name="loginform" id="loginform" action="<?php echo site_url('wp-login.php', 'login_post') ?>" method="post"<?php echo $this->_styleclass($styleform,$classform); ?>>
                    <p>
                        <label><?php _e('Username') ?><br />
                        <input type="text" name="log" id="user_login" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" size="20" tabindex="10"<?php echo $this->_styleclass($stylelogin,$classlogin); ?> /></label>
                    </p>
                    <p>
                        <label><?php _e('Password') ?><br />
                        <input type="password" name="pwd" id="user_pass" value="" size="20" tabindex="20"<?php echo $this->_styleclass($stylepassword,$classpassword); ?> /></label>
                    </p>
                    <?php do_action('login_form'); ?>
                    <?php if($rememberme) {?><p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90"<?php echo $this->_styleclass($stylerememberme,$classrememberme); ?> /> <?php _e('Remember Me'); ?></label></p><?php } ?>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" value="<?php _e('Log in'); ?>" tabindex="100" />
                        <?php if($redirect) {?><input type="hidden" name="redirect_to" value="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>" /><?php } ?>
                        <?php if($testcookie) {?><input type="hidden" name="testcookie" value="1" /><?php } ?>
                    </p>
				</form>
		<?php
		if($ulli) 
			echo "</li>";
	}
	
	public function loginform_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
			<input class="checkbox" value="1" type="checkbox" <?php checked($rememberme,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][rememberme]" />&nbsp;<?php _e('Remember Me');?><br />
			<input class="checkbox" value="1" type="checkbox" <?php checked($redirect,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][redirect]" />&nbsp;<?php _e('Enable WordPress redirect function','minimeta-widget');?><br />
			<input class="checkbox" value="1" type="checkbox" <?php checked($testcookie,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][testcookie]" />&nbsp;<?php _e('Enable WordPress Cookie Test for login Form','minimeta-widget');?><br />
			<input class="checkbox" value="1" type="checkbox" <?php checked($ulli,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][ulli]" />&nbsp;<?php _e('Form in &lt;ul&gt; &lt;il&gt; tag','minimeta-widget');?><br />
			<hr />
		<?php
		if (!isset($classlogin)) $classlogin='input'; //def. Css
		if (!isset($classpassword)) $classpassword='input'; 
		 _e('Stylesheet:','minimeta-widget');?><br /> 
		&lt;form id=&quot;loginform&quot;
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleform)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleform]" />&quot; 
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classform)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classform]" />&quot;
		&gt;<br />
		&lt;input type=&quot;text&quot; id=&quot;user_login&quot;
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelogin)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylelogin]" />&quot; 
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classlogin)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classlogin]" />&quot;
		&gt;<br />
		&lt;input type=&quot;password&quot; id=&quot;user_pass&quot;
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylepassword)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylepassword]" />&quot; 
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classpassword)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classpassword]" />&quot;
		&gt;<br />
		&lt;input type=&quot;checkbox&quot; id=&quot;rememberme&quot;
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylerememberme)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylerememberme]" />&quot; 
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classrememberme)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classrememberme]" />&quot;
		&gt;<br />
		<?PHP
	}
	
	//Seiteadmin Link
	private function seiteadmin_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (!isset($namedashboard))
			$namedashboard=false;
		if (!isset($styleseiteadmin))
			$styleseiteadmin='';			
		if (!isset($classseiteadmin))
			$classseiteadmin='';			
		$this->_ulopenclose(true);
		$linkname=__('Site Admin');
		if ($namedashboard) $linkname=__('Dashboard');
		echo "<li".$this->styleclassgeneralli."><a href=\"".admin_url()."\"".$this->_styleclass($styleseiteadmin,$classseiteadmin).">".$linkname."</a></li>";
	}

	public function seiteadmin_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
			<input class="checkbox" value="1" type="checkbox" <?php checked($namedashboard,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][namedashboard]" />&nbsp;<?php _e('Show Link Name as &quot;Dashboard&quot; <b>not</b> &quot;Site Admin&quot;','minimeta-widget');?><br />
			<hr />
		<?php
		_e('Stylesheet:','minimeta-widget');?><br />
		&lt;a href=&quot;...&quot; 
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleseiteadmin)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleseiteadmin]" />&quot; 
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classseiteadmin)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classseiteadmin]" />&quot;
		&gt;<br />
		<?PHP
	}
	
	//Loginout Link
	private function linkloginout_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (!isset($redirect) or !$redirect)
			$redirect='';
		else 
			$redirect=$_SERVER['REQUEST_URI'];
		if (!isset($styleloginout))
			$styleloginout='';
		if (!isset($classloginout))
			$classloginout='';
		$this->_ulopenclose(true);
		if($this->inorout=="in") {
			echo "<li".$this->styleclassgeneralli."><a href=\"".wp_logout_url($redirect)."\"".$this->_styleclass($styleloginout,$classloginout).">".__('Log out')."</a></li>";
		} else {
			echo "<li".$this->styleclassgeneralli."><a href=\"".wp_login_url($redirect)."\"".$this->_styleclass($styleloginout,$classloginout).">".__('Log in')."</a></li>";
		}
	}
	
	public function linkloginout_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
		<input class="checkbox" value="1" type="checkbox" <?php checked($redirect,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][redirect]" />&nbsp;<?php _e('Enable WordPress redirect function','minimeta-widget');?><br />
		<hr />
		<?php 
		if (!isset($styleloginout)) $styleloginout='color:red;'; //def. Css
		 _e('Stylesheet:','minimeta-widget');?><br />
		&lt;a href=&quot;...&quot; 
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleloginout)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleloginout]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classloginout)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classloginout]" />&quot;
		&gt;<br />
		<?PHP
	}

	//Adminlinks
	private function adminlinks_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$this->_ulopenclose(true);
        if (sizeof($adminlinks)>0 or $notselected) { //show only if a Admin Link is selectesd
			if (!is_array($adminlinks)) $adminlinks[]="";
			$minimeta_adminlinks=get_option('minimeta_adminlinks'); 
			foreach ($minimeta_adminlinks as $menu) {
				$output="";
				foreach ($menu as $submenu) {
					if(current_user_can($submenu[1]) and is_array($submenu) and ((!$notselected and in_array(wp_specialchars($submenu[2]),$adminlinks)) or ($notselected and !in_array(wp_specialchars($submenu[2]),$adminlinks)))) {
						$output.= "<li".$this->_styleclass($styleadminlinksli,$classadminlinksli)."><a href=\"".admin_url("/".$submenu[2])."\" title=\"".$submenu[0]."\"".$this->_styleclass($styleadminlinkshref,$classadminlinkshref).">".$submenu[0]."</a></li>";
					}
				}
				if (!empty($output) and !$notopics) {
					echo "<li".$this->_styleclass($styleadminlinkslitopic,$classadminlinkslitopic).">".$menu['menu']."<ul".$this->_styleclass($styleadminlinksul,$classadminlinksul).">".$output."</ul></li>";
				} else {
					echo $output;
				}    
			}
        }
	}	
	
	public function adminlinks_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
        <input class="checkbox" value="1" type="checkbox" <?php checked($notopics,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][notopics]" />&nbsp;<?php _e('Do not show Admin Links Topics','minimeta-widget');?><br />
		<input class="checkbox" value="1" type="checkbox" <?php checked($notselected,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][notselected]" />&nbsp;<?php _e('Display <b>not</b> selected Admin Links','minimeta-widget');?><br />
		 <?php _e('Select Admin Links:','minimeta-widget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?> > optgroup >option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?> > optgroup > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
         <select style="height:120px;font-size:11px;" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][adminlinks][]" id="minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?>" multiple="multiple">
         <?PHP
			$minimeta_adminlinks=get_option('minimeta_adminlinks');
            foreach ($minimeta_adminlinks as $menu) {
             echo "<optgroup label=\"".$menu['menu']."\">";
             foreach ($menu as $submenu) {
              if (is_array($submenu)) {
               ?> <option value="<?PHP echo $submenu[2];?>"<?PHP selected(in_array($submenu[2],(array)$adminlinks),true);?>><?PHP echo $submenu[0];?></option> <?PHP
              }
             }
             echo "</optgroup>";
            }        
         ?>  
         </select>
		<hr />
		<?php 
		if (!isset($styleadminlinkslitopic)) $styleadminlinkslitopic='font-weight:bold;font-style:italic;'; //def. Css
		if (!isset($styleadminlinksli)) $styleadminlinksli='font-weight:normal;font-style:normal;';
		if (!isset($styleadminlinksselect)) $styleadminlinksselect='font-size:10px;';
		
		_e('Stylesheet:','minimeta-widget'); echo '<br />';
		_e('topic','minimeta-widget');?>&nbsp;&lt;li
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinkslitopic)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleadminlinkslitopic]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classadminlinkslitopic)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classadminlinkslitopic]" />&quot;
		&gt;<br />
		&lt;ul
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksul)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleadminlinksul]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classadminlinksul)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classadminlinksul]" />&quot;
		&gt;<br />
		&lt;li
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksli)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleadminlinksli]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classadminlinksli)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classadminlinksli]" />&quot;
		&gt;<br />
		&lt;a href=&quot;...&quot; 
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinkshref)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleadminlinkshref]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classadminlinkshref)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classadminlinkshref]" />&quot;
		&gt;<br />
		<?PHP
	}	
	
	//Adminlinks
	private function adminselect_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$this->_ulopenclose(true);
        if (sizeof($adminlinks)>0 or $notselected) { //show only if a Admin Link is selectesd
			if (!is_array($adminlinks)) $adminlinks[]="";
            echo "<li".$this->styleclassgeneralli."><select onchange=\"document.location.href=this.options[this.selectedIndex].value;\"".$this->_styleclass($styleadminlinksselect,$classadminlinksselect)."><option selected=\"selected\"".$this->_styleclass($styleadminlinksoption,$classadminlinksoption).">".__('Please select:','minimeta-widget')."</option>";
			$minimeta_adminlinks=get_option('minimeta_adminlinks'); 
            foreach ($minimeta_adminlinks as $menu) {
            $output="";
                foreach ($menu as $submenu) {
					if(current_user_can($submenu[1]) and is_array($submenu) and ((!$notselected and in_array(wp_specialchars($submenu[2]),$adminlinks)) or ($notselected and !in_array(wp_specialchars($submenu[2]),$adminlinks)))) {
						$output.= "<option value=\"".admin_url("/".$submenu[2])."\"".$this->_styleclass($styleadminlinksoption,$classadminlinksoption).">".$submenu[0]."</option>";
                    }
                }
                if (!empty($output) and !$notopics) {
                    echo "<optgroup label=\"".$menu['menu']."\"".$this->_styleclass($styleadminlinksoptgroup,$classadminlinksoptgroup).">".$output."</optgroup>";
                } else {
				     echo $output;
				}    
            }
            echo "</select></li>";
        }
	}	
	
	public function adminselect_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		?>
         <input class="checkbox" value="1" type="checkbox" <?php checked($notopics,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][notopics]" />&nbsp;<?php _e('Do not show Admin Links Topics','minimeta-widget');?><br />
		 <input class="checkbox" value="1" type="checkbox" <?php checked($notselected,true); ?> name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][notselected]" />&nbsp;<?php _e('Display <b>not</b> selected Admin Links','minimeta-widget');?><br />
		 <?php _e('Select Admin Links:','minimeta-widget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?> > optgroup >option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?> > optgroup > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
         <select style="height:120px;font-size:11px;" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][adminlinks][]" id="minimeta-adminlinks-<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?>" multiple="multiple">
         <?PHP
			$minimeta_adminlinks=get_option('minimeta_adminlinks');
            foreach ($minimeta_adminlinks as $menu) {
             echo "<optgroup label=\"".$menu['menu']."\">";
             foreach ($menu as $submenu) {
              if (is_array($submenu)) {
                ?> <option value="<?PHP echo $submenu[2];?>"<?PHP selected(in_array($submenu[2],(array)$adminlinks),true);?>><?PHP echo $submenu[0];?></option> <?PHP
              }
             }
             echo "</optgroup>";
            }        
         ?>  
         </select>
		<hr />
		<?php 
		if (!isset($styleadminlinksselect)) $styleadminlinksselect='font-size:10px;';
		
		_e('Stylesheet:','minimeta-widget'); 
		?><br />
		&lt;select
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksselect)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleadminlinksselect]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classadminlinksselect)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classadminlinksselect]" />&quot;
		&gt;<br />
		&lt;optiongroup
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksoptgroup)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleadminlinksoptgroup]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classadminlinksoptgroup)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classadminlinksoptgroup]" />&quot;
		&gt;<br />
		&lt;option
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($styleadminlinksoption)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][styleadminlinksoption]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classadminlinksoption)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classadminlinksoption]" />&quot;
		&gt;<br />
		<?PHP
	}	
	
	//Lostpw Link
	private function linklostpw_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		$this->_ulopenclose(true);
		echo "<li".$this->styleclassgeneralli."><a href=\"".site_url('wp-login.php?action=lostpassword', 'login')."\" title=\"".__('Password Lost and Found')."\"".$this->_styleclass($stylelinklostpw,$classlinklostpw).">".__('Lost your password?')."</a></li>";
	}	
	
	public function linklostpw_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		_e('Stylesheet:','minimeta-widget');?><br />
		&lt;a href=&quot;...&quot; 
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinklostpw)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylelinklostpw]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classlinklostpw)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classlinklostpw]" />&quot;
		&gt;<br />
		<?PHP
	}
	
	//register Link
	private function linkregister_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if(get_option('users_can_register')) {
			$this->_ulopenclose(true);
			echo "<li".$this->styleclassgeneralli."><a href=\"".site_url('wp-login.php?action=register', 'login')."\"".$this->_styleclass($stylelinkregister,$classlinkregister).">" . __('Register') . "</a></li>";
		}
	}		

	public function linkregister_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if(!get_option('users_can_register')) { 
			?>
			<span style="color:red;"><?PHP _e('Register is not allowed in this Blog! Activate it to use this Link.','minimeta-widget');?></span>
			<hr />
			<?PHP
		}
		_e('Stylesheet:','minimeta-widget');?><br />
		&lt;a href=&quot;...&quot; 
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinkregister)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylelinkregister]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classlinkregister)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classlinkregister]" />&quot;
		&gt;<br />
		<?PHP
	}
	
	
	//WP Bokmarks Categorys
	private function bookmarkscat_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (is_array($categorys)) {
			$this->_ulopenclose(true);
			wp_list_bookmarks('echo=1&title_li=&before=<li'.$this->styleclassgeneralli.'>&categorize=0&category='.implode(',',$categorys).'&show_images=0&orderby=name');
		}
	}		
	
	public function bookmarkscat_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Select Links to Display:','minimeta-widget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-links<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?> > option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-links<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?> > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
        <select style="height:70px;font-size:11px;" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][categorys][]" id="minimeta-links<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?>" multiple="multiple">
        <?PHP
        $cats=get_terms( 'link_category', array( 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => false ) );
		foreach ($cats as $catsdisplay) {
            ?> <option value="<?PHP echo $catsdisplay->term_id;?>"<?PHP selected(in_array($catsdisplay->term_id,(array)$categorys),true);?>><?PHP echo wp_specialchars($catsdisplay->name);?></option> <?PHP
        }        
        ?>  
        </select>
		<?PHP
	}
	
	//WP Bokmarks 
	private function bookmarks_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (is_array($links)) {
			$this->_ulopenclose(true);
			wp_list_bookmarks('echo=1&title_li=&before=<li'.$this->styleclassgeneralli.'>&categorize=0&show_images=0&show_private=1&hide_invisible=0&orderby=name&include='.implode(',',$links));
		}
	}		
	
	public function bookmarks_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		 _e('Select Links to Display:','minimeta-widget');?> <input type="button" value="<?php _e('All'); ?>" onclick='jQuery("#minimeta-links<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?> > option").attr("selected","selected")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /> <input type="button" value="<?php _e('None'); ?>" onclick='jQuery("#minimeta-links<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?> > option").attr("selected","")' style="font-size:9px;"<?php echo $disabeld; ?> class="button" /><br />
        <select style="height:70px;font-size:11px;" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][links][]" id="minimeta-links<?php echo $optionname; ?>-<?php echo $this->inorout; ?>-<?php echo $ordering; ?>" multiple="multiple">
        <?PHP
        $bookmarks=get_bookmarks(array('hide_invisible' => 0,'orderby' =>'name'));
		foreach ($bookmarks as $linksdisplay) {
            ?> <option value="<?PHP echo $linksdisplay->link_id; ?>"<?PHP selected(in_array($linksdisplay->link_id,(array)$links),true);?>><?PHP echo wp_specialchars($linksdisplay->link_name).' - '.wp_specialchars($linksdisplay->link_url);?></option><?PHP
        }        
        ?>  
        </select>
		<?PHP
	}

	//RSS Link
	private function linkrss_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (empty($linktext))
			$linktext=__('Entries <abbr title="Really Simple Syndication">RSS</abbr>');
		if (!isset($stylelinkrss))
			$stylelinkrss='';
		if (!isset($classlinkrss))
			$classlinkrss='';
		$this->_ulopenclose(true);
		echo "<li".$this->styleclassgeneralli."><a href=\"".get_bloginfo('rss2_url')."\" title=\"".esc_attr(__('Syndicate this site using RSS 2.0'))."\"".$this->_styleclass($stylelinkrss,$classlinkrss).">".stripslashes($linktext)."</a></li>";
	}			
	
	public function linkrss_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (empty($linktext))
			$linktext=__('Entries <abbr title="Really Simple Syndication">RSS</abbr>');
		_e('Link Text:','minimeta-widget'); ?> <input class="text" type="text" value="<?php echo htmlentities(stripslashes($linktext)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][linktext]" />	
		<hr />
		<?PHP
		_e('Stylesheet:','minimeta-widget');?><br />
		&lt;a href=&quot;...&quot; 
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinkrss)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylelinkrss]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classlinkrss)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classlinkrss]" />&quot;
		&gt;<br />
		<?PHP
	}
		
	//Comment RSS Link
	private function linkcommentrss_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (empty($linktext))
			$linktext=__('Comments <abbr title="Really Simple Syndication">RSS</abbr>');
		if (!isset($stylelinkcommentrss))
			$stylelinkcommentrss='';
		if (!isset($classlinkcommentrss))
			$classlinkcommentrss='';
		$this->_ulopenclose(true);
		echo "<li".$this->styleclassgeneralli."><a href=\"".get_bloginfo('comments_rss2_url')."\" title=\"".esc_attr(__('The latest comments to all posts in RSS'))."\"".$this->_styleclass($stylelinkcommentrss,$classlinkcommentrss).">".stripslashes($linktext)."</a></li>";
	}	
	
	public function linkcommentrss_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (empty($linktext))
			$linktext=__('Comments <abbr title="Really Simple Syndication">RSS</abbr>');
		_e('Link Text:','minimeta-widget'); ?> <input class="text" type="text" value="<?php echo htmlentities(stripslashes($linktext)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][linktext]" />	
		<hr />
		<?PHP
		_e('Stylesheet:','minimeta-widget');?><br />
		&lt;a href=&quot;...&quot; 
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinkcommentrss)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylelinkcommentrss]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classlinkcommentrss)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classlinkcommentrss]" />&quot;
		&gt;<br />
		<?PHP
	}
		
	//Wordpress Link
	private function linkwordpress_display($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		if (!isset($stylelinkwordpress))
			$stylelinkwordpress='';
		if (!isset($classlinkwordpress))
			$classlinkwordpress='';
		$this->_ulopenclose(true);
		echo "<li".$this->styleclassgeneralli."><a href=\"http://wordpress.org/\" title=\"".esc_attr(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.'))."\"".$this->_styleclass($stylelinkwordpress,$classlinkwordpress).">WordPress.org</a></li>";
	}
	
	public function linkwordpress_options($args) {
		if(is_array($args)) 
			extract($args, EXTR_SKIP );
		_e('Stylesheet:','minimeta-widget');?><br />
		&lt;a href=&quot;...&quot; 
		style=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($stylelinkwordpress)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][stylelinkwordpress]" />&quot;
		class=&quot;<input class="textinput" type="text" value="<?php echo htmlentities(stripslashes($classlinkwordpress)); ?>" name="widget-options[<?php echo $this->inorout; ?>][<?php echo $ordering; ?>][args][classlinkwordpress]" />&quot;
		&gt;<br />
		<?PHP
	}
	
	//action wp_meta
	private function actionwpmeta_display() {
		if(has_action('wp_meta')) {
			$this->_ulopenclose(true);
			do_action('wp_meta');
		}
	}		
}



?>