<?PHP
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
define( 'BDM_CHUHPL_DOC_PATH',  $_SERVER['DOCUMENT_ROOT'] );

if( empty( $_GET['id'] ) || $_GET['id'] === 0 ):
	?>
    <h1><?PHP _e( 'A Document ID wasn\'t included in the link, so you won\'t be able to view a document.', 'board-document-manager-from-chuhpl' ); ?></h1>
    <a href="#" onclick="setTimeout(function(){var ww = window.open(window.location, '_self'); ww.close(); }, 1000);"><?PHP _e( 'Click here to close this window.', 'board-document-manager-from-chuhpl' ); ?></a>
  <?PHP
endif;

# is valid?
$error = false;
if( !is_numeric( $_GET['id'] ) ):
	$error = true;
else:
	$query = "SELECT bc_id, bc_type, bc_date, bc_fileName, bc_special, bc_changed, bc_staff FROM `{$wpdb->prefix}boardDocumentManager` WHERE `bc_id` = '{$_GET['id']}'";
	$document = $wpdb->get_row( $query, ARRAY_A );
	if( empty( $document ) ):
		$error = true;
	endif;
endif;
if( $error ):
	?>
    <h1><?PHP _e( 'A bad Document ID was included in the link, so you won\'t be able to view a document', 'board-document-manager-from-chuhpl' ); ?></h1>
    <a href="#" onclick="setTimeout(function(){var ww = window.open(window.location, '_self'); ww.close(); }, 1000);"><?PHP _e( 'Click here to close this window.', 'board-document-manager-from-chuhpl' ); ?></a>
  <?PHP
endif;

if( !file_exists( BOARDDOCMAN_CHUHPL_FILE_PATH . '/' . $document['bc_fileName'] ) ):
	wp_die( "<h1>Error: {$document['bc_fileName']} doesn't exists." );
endif;

$path = BOARDDOCMAN_CHUHPL_FILE_PATH . '/' . $document['bc_fileName'];

header("Content-Length: " . filesize ( $path ) ); 
header("Content-type: application/pdf"); 
header("Content-disposition: inline; filename=".basename($path));
header('Expires: 0');
header('Accept-Ranges: bytes');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
ob_clean();
flush();
readfile($path);
die();




header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="'.$document['bc_fileName'] .'"');

readfile( BOARDDOCMAN_CHUHPL_FILE_PATH . '/' . $document['bc_fileName'] );
die();
?>