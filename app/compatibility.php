<?PHP
/**
 * Implement a few of the functions available only in recent versions of 
 * WordPress.  I'd much rather reimplement these functions here, and keep the 
 * rest of the plugin code clean.
 */

 // don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

/* since 2.5 */
if (!function_exists('is_front_page')):
function is_front_page() {
	return is_home();
}
endif;	
 
/* since 2.6 */
if (!defined('WP_PLUGIN_DIR')):
	define( 'WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins' );
endif;

/* since 2.6 */
if (!defined('WP_PLUGIN_URL')):
	define( 'WP_PLUGIN_URL', plugins_url());
endif;

/* since 2.6 */
if (!function_exists('site_url')):
function site_url($path = '', $scheme = null) {
	$url =  get_option('siteurl');
	if ( !empty($path) && is_string($path) && strpos($path, '..') === false ) {
		$url .= '/' . ltrim($path, '/');
	}
	return $url;
}
endif;

/* since 2.6 */
if (!function_exists('admin_url')):
function admin_url($path = '') {
	$url = site_url('wp-admin/', 'admin');
	if ( !empty($path) && is_string($path) && strpos($path, '..') === false ) {
		$url .= ltrim($path, '/');
	}
	return $url;
}
endif;

/* since 2.6 */
if (!function_exists('plugins_url')):
function plugins_url($path = '') {
	$url = site_url(PLUGINDIR);
	if ( !empty($path) && is_string($path) && strpos($path, '..') === false ) {
		$url .= '/' . ltrim($path, '/');
	}
	return $url;
}
endif;

/* since 2.7 */
if (!function_exists('wp_logout_url')):
function wp_logout_url($redirect = '') {
	if ( strlen($redirect) )
		$redirect = "&redirect_to=$redirect";
	return wp_nonce_url( site_url("wp-login.php?action=logout$redirect", 'login'), 'log-out' );
}
endif;

/* since 2.7 */		
if (!function_exists('wp_login_url')):
function wp_login_url($redirect = '') {
	if ( strlen($redirect) )
		$redirect = "?redirect_to=$redirect";
	return site_url("wp-login.php$redirect", 'login');
}
endif;

/* since 2.7 */	
if (!function_exists('add_contextual_help')):	
function add_contextual_help($screen = '', $help) {
  unset($help);
  return $help;
}
endif;

?>