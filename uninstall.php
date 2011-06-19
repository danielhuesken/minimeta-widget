<?PHP
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	header("Status: 404 Not Found");
	die();
}
delete_option('minimeta_widget_wp');
delete_option('minimeta_widget_options');
delete_option('minimeta_adminlinks');
?>
