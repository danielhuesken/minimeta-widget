<?PHP
function minmeta_adminliks() {
 global $wpdb;
 //active plugins 
 $plugins=get_option('active_plugins');
 for ($i=0;$i<sizeof($plugins);$i++) {
  $plugins[$i]=strtolower(plugin_basename($plugins[$i]));
 }
 //adminlinks
 $adminlinks[1]['menu'] =  __('Dashboard'); //menu group
 $adminlinks[1][5] = array(__('Dashboard'), '1', 'index.php'); //menu link
 if (in_array("wp-serverinfo/wp-serverinfo.php",$plugins)) $adminlinks[1][10] = array(__('WP-ServerInfo', 'wp-serverinfo'), 1, 'index.php?page=wp-serverinfo/wp-serverinfo.php'); //menu link
 if (in_array("wp-stats/wp-stats.php",$plugins)) $adminlinks[1][11] = array(__('WP-Stats', 'wp-stats'), 1, 'index.php?page=wp-stats/wp-stats.php'); //menu link
 if (in_array("wp-useronline/wp-useronline.php",$plugins)) $adminlinks[1][12] = array(__('WP-UserOnline', 'wp-useronline'), 1, 'index.php?page=wp-useronline/useronline.php'); //menu link
 if (in_array("stats.php",$plugins)) $adminlinks[1][13] = array(__('Blog Stats'), 'manage_options', 'index.php?page=stats'); //menu link
 $adminlinks[5]['menu'] =  __('Write'); //menu group
 $adminlinks[5][5] =    array(__('Write Post'), 'edit_posts', 'post-new.php'); //menu link
 $adminlinks[5][10] =   array(__('Write Page'), 'edit_pages', 'page-new.php'); //menu link
 $adminlinks[10]['menu'] = __('Manage');
 $adminlinks[10][5] =   array(__('Posts'), 'edit_posts', 'edit.php');
 $adminlinks[10][10] =  array(__('Pages'), 'edit_pages', 'edit-pages.php');
 $adminlinks[10][12] =  array(__('Uploads'), 'upload_files', 'upload.php');
 $adminlinks[10][15] =  array(__('Categories'), 'manage_categories','categories.php');
 $adminlinks[10][30] =  array(__('Files'), 'edit_files', 'templates.php');
 $adminlinks[10][35] =  array(__('Import'), 'import', 'import.php');
 $adminlinks[10][40] =  array(__('Export'), 'import', 'export.php');
 if (in_array("simple-forum/sf-control.php",$plugins)) $adminlinks[10][50] =  array(__('Simple Forum', 'sforum'), 8, 'edit.php?page=simple-forum/sf-admin.php');
 if (in_array("wp-ban/wp-ban.php",$plugins)) $adminlinks[10][51] =  array(__('Ban', 'wp-ban'), 'manage_options', 'edit.php?page=wp-ban/ban-options.php');
 $adminlinks[15]['menu'] = __('Comments');
 $adminlinks[15][5] =   array(__('Comments'), 'edit_posts', 'edit-comments.php');
 $adminlinks[15][25] =  array(sprintf(__("Awaiting Moderation (%s)"), $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'")), 'edit_posts', 'moderation.php');
 if (in_array("akismet/akismet.php",$plugins)) $adminlinks[15][30] = array(sprintf(__('Akismet Spam (%s)'), akismet_spam_count()), 'moderate_comments', 'edit-comments.php?page=akismet-admin');
 $adminlinks[20]['menu'] = __('Blogroll');
 $adminlinks[20][5] = array(__('Manage Blogroll'), 'manage_links', 'link-manager.php');
 $adminlinks[20][10] = array(__('Add Link'), 'manage_links', 'link-add.php');
 $adminlinks[20][20] = array(__('Import Links'), 'manage_links', 'link-import.php');
 $adminlinks[20][30] = array(__('Categories'), 'manage_links', 'edit-link-categories.php');
 $adminlinks[25]['menu'] = __('Presentation');
 $adminlinks[25][5] = array(__('Themes'), 'switch_themes', 'themes.php');
 if (function_exists('dynamic_sidebar')) $adminlinks[25][7] = array(__( 'Widgets' ), 'switch_themes', 'widgets.php');
 $loggtinlinks[25][10] = array(__('Theme Editor'), 'edit_themes', 'theme-editor.php');
 if (get_current_theme()=="K2") $adminlinks[25][15] = array(__('K2 Options','k2_domain'), 'edit_themes', 'themes.php?page=k2-options');
 if (get_current_theme()=="K2" and K2_USING_SBM) $adminlinks[25][7] = array(__('K2 Sidebar Manager','k2_domain'), 'edit_themes', 'themes.php?page=k2-sbm-manager');
 $adminlinks[25][50] = array(__('Custom Image Header'), 'edit_themes', 'themes.php?page=custom-header');
 $adminlinks[30]['menu'] = __('Plugins');
 $adminlinks[30][5] = array(__('Plugins'), 'activate_plugins', 'plugins.php');
 $loggtinlinks[30][10] = array(__('Plugin Editor'), 'edit_plugins', 'plugin-editor.php');
 if (in_array("sphere-related-content/sphere-related-content.php",$plugins)) $adminlinks[30][10] = array(__('Sphere Configuration'), 'manage_options', 'plugins.php?page=sphere-related-content/sphere-related-content.php');
 if (in_array("stats.php",$plugins)) $adminlinks[30][15] = array(__('WordPress.com Stats Plugin'), 'manage_options', 'plugins.php?page=wpstats');
 if (in_array("akismet/akismet.php",$plugins)) $adminlinks[30][20] = array(__('Akismet Configuration'), 'manage_options', 'plugins.php?page=akismet-key-config');
 if (current_user_can('edit_users')) {
  $adminlinks[35]['menu'] = __('Users'); 
 } else {
  $adminlinks[35]['menu'] = __('Profile');
 } 
 $adminlinks[35][5] = array(__('Authors &amp; Users'), 'edit_users', 'users.php');
 $adminlinks[35][10] = array(__('Your Profile'), 'read', 'profile.php');
 $adminlinks[40]['menu'] = __('Options');
 $adminlinks[40][10] = array(__('General'), 'manage_options', 'options-general.php');
 $adminlinks[40][15] = array(__('Writing'), 'manage_options', 'options-writing.php');
 $adminlinks[40][20] = array(__('Reading'), 'manage_options', 'options-reading.php');
 $adminlinks[40][25] = array(__('Discussion'), 'manage_options', 'options-discussion.php');
 $adminlinks[40][30] = array(__('Privacy'), 'manage_options', 'options-privacy.php');
 $adminlinks[40][35] = array(__('Permalinks'), 'manage_options', 'options-permalink.php');
 $adminlinks[40][40] = array(__('Miscellaneous'), 'manage_options', 'options-misc.php');
 if (in_array("wp-pagenavi/wp-pagenavi.php",$plugins)) $adminlinks[40][50] = array(__('PageNavi', 'wp-pagenavi'), 'manage_options', 'options-general.php?page=wp-pagenavi/pagenavi-options.php');
 if (in_array("wp-postviews/wp-postviews.php",$plugins)) $adminlinks[40][51] = array(__('Post Views', 'wp-postviews'), 'manage_options', 'options-general.php?page=wp-postviews/postviews-options.php');
 if (in_array("wp-print/wp-print.php",$plugins)) $adminlinks[40][52] = array(__('Print', 'wp-print'), 'manage_options', 'options-general.php?page=wp-print/print-options.php');
 if (in_array("wp-stats/wp-stats.php",$plugins)) $adminlinks[40][53] = array(__('Stats', 'wp-stats'), 'manage_options', 'options-general.php?page=wp-stats/stats-options.php');
 if (in_array("wp-sticky/wp-sticky.php",$plugins)) $adminlinks[40][54] = array(__('Sticky', 'wp-sticky'), 'manage_options', 'options-general.php?page=wp-sticky/sticky-options.php');
 if (in_array("wp-useronline/wp-useronline.php",$plugins)) $adminlinks[40][55] = array(__('Useronline', 'wp-useronline'), 'manage_options', 'options-general.php?page=wp-useronline/useronline-options.php');
 if (in_array("wp-cache/wp-cache.php",$plugins)) $adminlinks[40][56] = array('WP-Cache', 5, 'options-general.php?page=wp-cache/wp-cache.php');
 if (in_array("all-in-one-seo-pack/all_in_one_seo_pack.php",$plugins)) $adminlinks[40][57] = array(__('All in One SEO', 'all_in_one_seo_pack'), 'manage_options', 'options-general.php?page=all-in-one-seo-pack/all_in_one_seo_pack.php');
 if (in_array("get-recent-comments/get-recent-comments.php",$plugins)) $adminlinks[40][58] = array('Recent Comments', 8, 'options-general.php?page=get-recent-comments.php');
 if (in_array("google-sitemap-generator/sitemap.php",$plugins)) $adminlinks[40][59] = array(__('XML-Sitemap','sitemap'), 'administrator', 'options-general.php?page=sitemap.php');
 if (in_array("shutter-reloaded/shutter-reloaded.php",$plugins)) $adminlinks[40][60] = array('Shutter Reloaded', 9, 'options-general.php?page=shutter-reloaded/shutter-reloaded.php');
 if (in_array("raz-captcha/raz-captcha.php",$plugins)) $adminlinks[40][61] = array('Raz-Captcha', 8, 'options-general.php?page=raz-captcha.php');
 if (in_array("podpress/podpress.php",$plugins)) {
  $adminlinks[44]['menu'] = 'podPress';
  $adminlinks[44][10]=array(__('Stats'),1,'admin.php?page=podpress/podpress_stats.php');
  $adminlinks[44][20]=array(__('Feed/iTunes Settings', 'podpress'),1,'admin.php?page=podpress/podpress_feed.php');
  $adminlinks[44][30]=array(__('General Settings', 'podpress'),1,'admin.php?page=podpress/podpress_general.php');
  $adminlinks[44][40]=array(__('Player Settings', 'podpress'),1,'admin.php?page=podpress/podpress_players.php');
  $adminlinks[44][50]=array(__('Podango Settings', 'podpress'),1,'admin.php?page=podpress/podpress_podango.php');
 }
 if (in_array("nextgen-gallery/nggallery.php",$plugins)) {
  $adminlinks[45]['menu'] = __('Gallery', 'nggallery');
  $adminlinks[45][10]=array(__('Gallery', 'nggallery'),'','admin.php?page=nggallery-gallery');
  $adminlinks[45][20]=array(__('Add Gallery', 'nggallery'),'','admin.php?page=nggallery-add-gallery');
  $adminlinks[45][30]=array(__('Manage Gallery', 'nggallery'),'','admin.php?page=nggallery-manage-gallery');
  $adminlinks[45][40]=array(__('Album', 'nggallery'),'','admin.php?page=nggallery-manage-album');
  $adminlinks[45][50]=array(__('Options', 'nggallery'),'','admin.php?page=nggallery-options');
  $adminlinks[45][60]=array(__('Style', 'nggallery'),'','admin.php?page=nggallery-style');
  $adminlinks[45][70]=array(__('Setup Gallery', 'nggallery'),'activate_plugins','admin.php?page=nggallery-setup');
  $adminlinks[45][80]=array(__('Roles', 'nggallery'),'activate_plugins','admin.php?page=nggallery-roles');
 }
 if (in_array("wp-dbmanager/wp-dbmanager.php",$plugins)) {
  $adminlinks[46]['menu'] = __('Database', 'wp-dbmanager');
  $adminlinks[46][10]=array(__('Database', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/database-manager.php');
  $adminlinks[46][20]=array(__('Backup DB', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/database-backup.php');
  $adminlinks[46][30]=array(__('Manage Backup DB', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/database-manage.php');
  $adminlinks[46][40]=array(__('Optimize DB', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/database-optimize.php');
  $adminlinks[46][50]=array(__('Repair DB', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/database-repair.php');
  $adminlinks[46][60]=array(__('Empty/Drop Tables', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/database-empty.php');
  $adminlinks[46][70]=array(__('Run SQL Query', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/database-run.php');
  $adminlinks[46][80]=array(__('DB Options', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/wp-dbmanager.php');
  //$adminlinks[46][90]=array(__('Uninstall WP-DBManager', 'wp-dbmanager'),'manage_database','admin.php?page=wp-dbmanager/database-uninstall.php');
 }
 if (in_array("wp-downloadmanager/wp-downloadmanager.php",$plugins)) {
  $adminlinks[47]['menu'] = __('Downloads', 'wp-downloadmanager');
  $adminlinks[47][20]=array(__('Manage Downloads', 'wp-downloadmanager'),'manage_downloads','admin.php?page=wp-downloadmanager/download-manager.php');
  $adminlinks[47][30]=array(__('Add File', 'wp-downloadmanager'),'manage_downloads','admin.php?page=wp-downloadmanager/download-add.php');
  $adminlinks[47][40]=array(__('Download Options', 'wp-downloadmanager'),'manage_downloads','admin.php?page=wp-downloadmanager/download-options.php');
  $adminlinks[47][50]=array(__('Download Templates', 'wp-downloadmanager'),'manage_downloads','admin.php?page=wp-downloadmanager/download-templates.php');
  //$adminlinks[47][60]=array(__('Uninstall WP-DownloadManager', 'wp-downloadmanager'),'manage_downloads','admin.php?page=wp-downloadmanager/download-uninstall.php');
 }
 if (in_array("wp-email/wp-email.php",$plugins)) {
  $adminlinks[48]['menu'] = __('E-Mail', 'wp-email');
  $adminlinks[48][10]=array(__('Manage E-Mail', 'wp-email'),'manage_email','admin.php?page=wp-email/email-manager.php');
  $adminlinks[48][20]=array(__('E-Mail Options', 'wp-email'),'manage_email','admin.php?page=wp-email/email-options.php');
  //$adminlinks[48][30]=array(__('Uninstall WP-EMail', 'wp-email'),'manage_email','admin.php?page=wp-email/email-uninstall.php');
 }
 if (in_array("wp-polls/wp-polls.php",$plugins)) {
  $adminlinks[49]['menu'] = __('Polls', 'wp-polls');
  $adminlinks[49][10]=array(__('Manage Polls', 'wp-polls'),'manage_polls','admin.php?page=wp-polls/polls-manager.php');
  $adminlinks[49][20]=array(__('Add Poll', 'wp-polls'),'manage_polls','admin.php?page=wp-polls/polls-add.php');
  $adminlinks[49][30]=array(__('Poll Options', 'wp-polls'),'manage_polls','admin.php?page=wp-polls/polls-options.php');
  $adminlinks[49][40]=array(__('Poll Templates', 'wp-polls'),'manage_polls','admin.php?page=wp-polls/polls-templates.php');
  $adminlinks[49][50]=array(__('Poll Usage', 'wp-polls'),'manage_polls','admin.php?page=wp-polls/polls-usage.php');
  //$adminlinks[49][60]=array(__('Uninstall WP-Polls', 'wp-polls'),'manage_polls','admin.php?page=wp-polls/polls-uninstall.php');
 }
 if (in_array("wp-postratings/wp-postratings.php",$plugins)) {
  $adminlinks[50]['menu'] = __('Ratings', 'wp-postratings');
  $adminlinks[50][10]=array( __('Manage Ratings', 'wp-postratings'),'manage_ratings','admin.php?page=wp-postratings/postratings-manager.php');
  $adminlinks[50][20]=array( __('Ratings Options', 'wp-postratings'),'manage_ratings','admin.php?page=wp-postratings/postratings-options.php');
  $adminlinks[50][30]=array( __('Ratings Usage', 'wp-postratings'),'manage_ratings','admin.php?page=wp-postratings/postratings-usage.php');
  //$adminlinks[50][40]=array( __('Uninstall WP-PostRatings', 'wp-postratings'),'manage_ratings','admin.php?page=wp-postratings/postratings-uninstall.php');
 }
return $adminlinks;
}
?>