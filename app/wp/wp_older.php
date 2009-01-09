<?PHP
//Compatibility fpr WP 2.6

	if (!defined('WP_PLUGIN_DIR'))
		define( 'WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins' );
	if (!defined('WP_PLUGIN_URL'))	
		define( 'WP_PLUGIN_URL', get_option( 'siteurl' ) . 'wp-content/plugins' );
	
	if (!function_exists('site_url')) {
		function site_url($path = '', $scheme = null) { 
			return get_bloginfo('wpurl').'/'.$path;
		}
	}
	
	if (!function_exists('admin_url')) {
		function admin_url($path = '') {
			return get_bloginfo('wpurl').'/wp-admin/'.$path;
		}
	}
	
	if (!function_exists('plugins_url')) {
		function plugins_url($path = '') { 
			return get_option('siteurl') . '/wp-content/plugins/'.$path;
		}
	}

	if (!function_exists('wp_logout_url')) {
		function wp_logout_url($redirect = '') {
			if ( strlen($redirect) )
				$redirect = "&redirect_to=$redirect";
			return wp_nonce_url( site_url("wp-login.php?action=logout$redirect", 'login'), 'log-out' );
		}
	}
		
	if (!function_exists('wp_login_url')) {	
		function wp_login_url($redirect = '') {
			if ( strlen($redirect) )
				$redirect = "?redirect_to=$redirect";
			return site_url("wp-login.php$redirect", 'login');
		}
	}
?>