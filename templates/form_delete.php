<?PHP if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
<div id="icon-plugins" class="icon32"></div>
<h2><?php _e( 'Board Document Management', 'board-document-manager-from-chuhpl' ); ?></h2>
</div>
<h2><?PHP _e( 'Delete a document', 'board-document-manager-from-chuhpl' ); ?></h2>
<table class="bdmNiceTable">
  <tr>
    <td colspan="2"><?PHP _e( 'Board Documents - Delete a document', 'board-document-manager-from-chuhpl' ); ?></td>
  </tr>
  
  <tr>
    <td>Type</td>
    <td colspan="2"><span class="smaller"><strong>
	<?PHP 
echo $this->typesArr[$info['type']]; 
$special = ( true == $info['special'] ) ? " ".__( ' (Special)', 'board-document-manager-from-chuhpl' ) : NULL;
echo $special;
?>
	</strong></span></td>
</tr>
<tr>
  <td><?PHP _e( 'Date', 'board-document-manager-from-chuhpl' ); ?></td>
  <td colspan="2"><span class="smaller"><?PHP echo date_i18n( get_option( 'date_format' ), $info['date'] ); ?></span></td>
</tr>
<tr>
  <td><?PHP _e( 'Created', 'board-document-manager-from-chuhpl' ); ?></td>
  <td colspan="2"><strong><?PHP echo $info['staff']; ?></strong> - <em><?PHP echo date_i18n( get_option( 'date_format' ), $info['changed'] ); ?></em></td>
</tr>
<tr>
  <td colspan="2"><a href="?page=bdm_mainShow&action=delete_it&id=<?PHP echo $_GET['id']; ?>"><strong>Click here to delete. This cannot be undone! </strong></a></td>
</tr>
</table>
<p><a href="?page=bdm_mainShow"><?PHP _e( 'Return to document list', 'board-document-manager-from-chuhpl' ); ?> >></a></p>
