<?PHP
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
delete_option('minimeta_widget_wp');
delete_option('minimeta_widget_options');
delete_option('minimeta_adminlinks');
if (defined('K2_LOAD_SBM') and K2_LOAD_SBM) sbm_delete_option('minimeta_widget');
?>