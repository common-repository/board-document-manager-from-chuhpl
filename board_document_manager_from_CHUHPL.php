<?PHP
/*
Plugin Name: Board Document Manager from CHUHPL
Plugin URI: https://wordpress.org/plugins/board-document-manager-from-chuhpl/
Description: Board Document Manager from CHUHPL manages and presents agendas, meetings notes and more. 
Version: 1.9.1
Author: Colin Tomele
Author URI: http://heightslibrary.org
Text Domain: board-document-manager-from-chuhpl
License: GPLv2 or later
*/
global $boardDocumentManager_db_version;
$boardDocumentManager_db_version = "1";

define( 'BOARDDOCMAN_CHUHPL_PATH', plugin_dir_path( __FILE__ ) );
define( 'BOARDDOCMAN_CHUHPL_FILE_PATH', WP_CONTENT_DIR . '/uploads/board-document-manager-from-chuhpl' );

require_once( BOARDDOCMAN_CHUHPL_PATH . 'bdmAdd.php' );
require_once( BOARDDOCMAN_CHUHPL_PATH . 'bdmView.php' );

# start main menu

$bdmchuhpl_Add = new bdmchuhpl_Add;
$bdmchuhpl_View = new bdmchuhpl_View;

register_activation_hook( __FILE__, 'boardDocumentManage_activate' );
register_deactivation_hook( __FILE__, 'boardDocumentManage_deactivate' );
register_uninstall_hook( __FILE__, 'boardDocumentManage_uninstall' );

add_action( 'wp_enqueue_scripts', 'bdmchuhpl_script_enqueuer' );
add_action( 'wp_enqueue_scripts', 'bdmchuhpl_load_textdomain' );

add_action('admin_menu', array( 'boardDocumentManage_settings', 'add_settingsPage' ) );
add_action('admin_menu', array( $bdmchuhpl_Add, 'add_menu' ) );
add_action('admin_menu', array( $bdmchuhpl_View, 'add_menu' ) );


function bdmchuhpl_script_enqueuer() {
	global $bdmchuhpl_View;
    
    wp_register_style( 'bdm_style', plugins_url( 'board-document-manager-from-chuhpl/bdmStyle.css' ) );
    wp_enqueue_style( 'bdm_style' );
	
	add_shortcode( 'showBoardDocumentManager',	array( 'bdmMain', 'showPublic' )  );
	if( !empty( $_GET['action'] ) && $_GET['action'] == 'showMe' ):
		$docInfo = $bdmchuhpl_View->getDocumentInfo( $_GET['id'] );
		if( empty( $docInfo ) ):
			wp_die( 'Bad ID. Can\'t display document' );
		endif;
		include( BOARDDOCMAN_CHUHPL_PATH . 'showMe.php' );
	endif;

}

function bdmchuhpl_load_textdomain() {
  load_plugin_textdomain( 'board-document-manager-from-chuhpl', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

class boardDocumentManage_settings
{
	public static function add_settingsPage()
	{
		# Add settings page
		global $bdmchuhpl_Add, $bdmchuhpl_View;

		add_menu_page( 'Board Document Manager', 'Board Docs', 'publish_pages', 'bdm_main',  array( $bdmchuhpl_Add, 'formAdd' ), 'dashicons-media-text', 200 );
	}
	
}


function boardDocumentManage_activate()
# this is only run when hooked by activating plugin
{
	
	global $wpdb;
	global $boardDocumentManager_db_version;
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$sql	= "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}boardDocumentManager` (
				  `bc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `bc_type` enum('agen', 'mins') COLLATE utf8_unicode_ci NOT NULL,
				  `bc_date` int(11) unsigned NOT NULL,
				  `bc_fileName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  `bc_special` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
				  `bc_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  `bc_staff` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`bc_id`),
				  KEY `bc_id` (`bc_id`,`bc_type`,`bc_date`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";

	dbDelta( $sql );
	
	# defaults
	add_option( "boardDocumentManager_db_version", $boardDocumentManager_db_version );
	add_user_meta( get_current_user_id(), 'bdmchuhpl_formCode', NULL );
	
	# folder
	if( !is_dir( BOARDDOCMAN_CHUHPL_FILE_PATH ) ):
		mkdir( BOARDDOCMAN_CHUHPL_FILE_PATH );
	endif;
}
	
function boardDocumentManage_deactivate()
# this is only run when hooked by activating plugin
{
	# 
}
	
function boardDocumentManage_uninstall()
# this is only run when hooked by activating plugin
{
	global $wpdb, $bdmchuhpl_View;

	$query = "SELECT bc_fileName FROM {$wpdb->prefix}boardDocumentManager";
	$final = $wpdb->get_results( $query, ARRAY_A );
	
	foreach( $final as $val ):
		unlink( BOARDDOCMAN_CHUHPL_FILE_PATH . '/' . $val['bc_fileName'] );
	endforeach;
			
	if( is_dir( BOARDDOCMAN_CHUHPL_FILE_PATH ) ):
		rmdir( BOARDDOCMAN_CHUHPL_FILE_PATH );
	endif;
	
	$wpdb->query( "DROP TABLE {$wpdb->prefix}boardDocumentManager" );
	delete_option( "boardDocumentManager_db_version" );
	delete_user_meta( NULL, 'bdmchuhpl_formCode', NULL );
}

class bdmMain {
	function setup_sourcesArr()
	{
		$final = array('file' =>  __( 'File Upload', 'board-document-manager-from-chuhpl' ), 'box' =>  __( 'Text Entry', 'board-document-manager-from-chuhpl' ) );
		return $final;
	}
	function setup_typesArr()
	{
		#$final = array('mins' => __( 'Minutes', 'board-document-manager-from-chuhpl' ), 'agen' => __( 'Agenda', 'board-document-manager-from-chuhpl' ), 'mnot' => __( 'Meeting Notice', 'board-document-manager-from-chuhpl' ) );
		$final = array('mins' => __( 'Minutes', 'board-document-manager-from-chuhpl' ), 'agen' => __( 'Agenda', 'board-document-manager-from-chuhpl' ) );
		return $final;
	}
	public function showPublic()
	{
		global $wpdb;
		include( BOARDDOCMAN_CHUHPL_PATH . 'templates/show_shortcode.php' );	
	}
}

?>
