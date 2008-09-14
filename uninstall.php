<?PHP
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
delete_option('widget_minimeta');
delete_option('widget_minimeta_adminlinks');
delete_option('widget_minimeta_options');
delete_option('widget_minimeta_seidbar_widget');
if (defined('K2_LOAD_SBM') and K2_LOAD_SBM) sbm_delete_option('widget_minimeta');
?>